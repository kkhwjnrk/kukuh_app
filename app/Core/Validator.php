<?php

namespace App\Core;

/**
 * Class Validator
 * 
 * Melakukan validasi data input formulir dengan pesan error berbahasa Indonesia
 * yang ramah pengguna.
 */
class Validator
{
    /** @var array Data yang akan divalidasi */
    private array $data;

    /** @var array Aturan validasi */
    private array $rules;

    /** @var array Kumpulan pesan error */
    private array $errors = [];

    /**
     * Konstruktor validator.
     *
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->data  = $data;
        $this->rules = $rules;
    }

    /**
     * Static helper untuk membuat dan menjalankan validasi.
     *
     * @param array $data
     * @param array $rules
     * @return static
     */
    public static function make(array $data, array $rules): self
    {
        $validator = new self($data, $rules);
        $validator->validate();
        return $validator;
    }

    /**
     * Menjalankan seluruh aturan validasi.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $rulesString) {
            $rulesList = explode('|', $rulesString);
            $value = $this->data[$field] ?? null;

            foreach ($rulesList as $ruleItem) {
                $params = [];
                if (strpos($ruleItem, ':') !== false) {
                    [$ruleName, $paramStr] = explode(':', $ruleItem, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $ruleItem;
                }

                $this->applyRule($field, $ruleName, $value, $params);
            }
        }

        return empty($this->errors);
    }

    /**
     * Menerapkan satu aturan validasi pada sebuah field.
     *
     * @param string $field
     * @param string $ruleName
     * @param mixed $value
     * @param array $params
     * @return void
     */
    private function applyRule(string $field, string $ruleName, $value, array $params): void
    {
        $readableField = ucfirst(str_replace('_', ' ', $field));

        switch ($ruleName) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "Bidang {$readableField} wajib diisi.");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "Bidang {$readableField} harus berupa angka numerik.");
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if (is_numeric($value) && $value < $min) {
                    $this->addError($field, "Bidang {$readableField} minimal bernilai {$min}.");
                } elseif (is_string($value) && strlen($value) < $min) {
                    $this->addError($field, "Bidang {$readableField} minimal berisi {$min} karakter.");
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 0);
                if (is_numeric($value) && $value > $max) {
                    $this->addError($field, "Bidang {$readableField} maksimal bernilai {$max}.");
                } elseif (is_string($value) && strlen($value) > $max) {
                    $this->addError($field, "Bidang {$readableField} maksimal berisi {$max} karakter.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Bidang {$readableField} harus berupa alamat email yang valid.");
                }
                break;

            case 'unique':
                // Format: unique:table,column,exceptId
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                $exceptId = $params[2] ?? null;

                if (!empty($value) && !empty($table)) {
                    $db = Database::getInstance()->getConnection();
                    $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :val";
                    if ($exceptId !== null) {
                        $sql .= " AND `id` != :exceptId";
                    }
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':val', $value);
                    if ($exceptId !== null) {
                        $stmt->bindValue(':exceptId', $exceptId);
                    }
                    $stmt->execute();
                    if ($stmt->fetchColumn() > 0) {
                        $this->addError($field, "{$readableField} '{$value}' sudah terdaftar dalam sistem.");
                    }
                }
                break;
        }
    }

    /**
     * Menambahkan pesan error ke daftar.
     *
     * @param string $field
     * @param string $message
     * @return void
     */
    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    /**
     * Mengecek apakah ada kegagalan validasi.
     *
     * @return bool
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Mengambil seluruh pesan error.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Mengambil pesan error pertama dari field tertentu.
     *
     * @param string $field
     * @return string|null
     */
    public function first(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Mengambil pesan error pertama dari kumpulan seluruh error.
     *
     * @return string
     */
    public function firstError(): string
    {
        if (empty($this->errors)) {
            return 'Terjadi kesalahan validasi input.';
        }
        return reset($this->errors);
    }
}
