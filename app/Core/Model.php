<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Class Model (Abstract Base Model)
 * 
 * Fondasi pemetaan data objek ke tabel basis data (Active Record / Data Mapper).
 * Seluruh interaksi SQL dilakukan melalui Prepared Statements secara otomatis
 * demi keamanan maksimal.
 */
abstract class Model
{
    /** @var PDO Koneksi PDO aktif */
    protected PDO $db;

    /** @var string Nama tabel basis data */
    protected string $table = '';

    /** @var string Primary key tabel */
    protected string $primaryKey = 'id';

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Mengambil seluruh record dari tabel.
     *
     * @param string|null $orderBy Kolom pengurutan (default: primaryKey)
     * @param string $direction
     * @return array
     */
    public function all(?string $orderBy = null, string $direction = 'DESC'): array
    {
        $orderCol = $orderBy ?? $this->primaryKey;
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `{$orderCol}` {$direction}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mencari satu record berdasarkan primary key.
     *
     * @param int|string $id
     * @return array|false
     */
    public function find($id)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Mencari satu record berdasarkan kolom tertentu.
     *
     * @param string $column
     * @param mixed $value
     * @return array|false
     */
    public function findBy(string $column, $value)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :val LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':val', $value);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Menyimpan record baru ke dalam tabel.
     *
     * @param array $data Pasangan kolom => nilai
     * @return int ID dari record yang baru dibuat
     */
    public function create(array $data): int
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    /**
     * Memperbarui record berdasarkan primary key.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data): bool
    {
        $fields = [];
        foreach (array_keys($data) as $key) {
            $fields[] = "`{$key}` = :{$key}";
        }
        $fieldsSql = implode(', ', $fields);

        $sql = "UPDATE `{$this->table}` SET {$fieldsSql} WHERE `{$this->primaryKey}` = :primary_id";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':primary_id', $id);

        return $stmt->execute();
    }

    /**
     * Menghapus record berdasarkan primary key.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    /**
     * Menghitung total seluruh baris data pada tabel.
     *
     * @param string|null $whereSql
     * @param array $params
     * @return int
     */
    public function count(?string $whereSql = null, array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($whereSql) {
            $sql .= " WHERE {$whereSql}";
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Melakukan paginasi data secara terstruktur.
     *
     * @param int $page Halaman aktif
     * @param int $perPage Jumlah item per halaman
     * @param string|null $whereSql Klausa WHERE opsional
     * @param array $params Parameter binding
     * @param string $orderBy Kolom pengurutan
     * @param string $direction ASC atau DESC
     * @return array Berisi 'data', 'total', 'page', 'per_page', 'last_page'
     */
    public function paginate(
        int $page = 1,
        int $perPage = 10,
        ?string $whereSql = null,
        array $params = [],
        ?string $orderBy = null,
        string $direction = 'DESC'
    ): array {
        $orderCol = $orderBy ?? $this->primaryKey;
        $total = $this->count($whereSql, $params);
        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = max(1, min($page, $lastPage));
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM `{$this->table}`";
        if ($whereSql) {
            $sql .= " WHERE {$whereSql}";
        }
        $sql .= " ORDER BY `{$orderCol}` {$direction} LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        return [
            'data'        => $stmt->fetchAll(),
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'last_page'   => $lastPage,
        ];
    }

    /**
     * Mengeksekusi raw query yang aman dengan parameter prepared statement.
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
