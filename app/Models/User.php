<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class User
 * 
 * Model representasi entitas pengguna (Users) dengan hak akses berjenjang.
 */
class User extends Model
{
    protected string $table = 'users';

    /**
     * Mengambil daftar pengguna beserta urutan peran.
     *
     * @return array
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT id, username, nama_lengkap, role, created_at, updated_at 
                FROM `{$this->table}` 
                ORDER BY id ASC";
        return $this->query($sql);
    }
}
