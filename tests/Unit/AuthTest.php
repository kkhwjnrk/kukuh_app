<?php

namespace Tests\Unit;

use App\Core\Auth;
use Tests\TestRunner;

class AuthTest
{
    public function testPasswordHashingAndVerification(TestRunner $runner): void
    {
        $rawPassword = 'secretPassword123';
        $hashed = Auth::hashPassword($rawPassword);

        $runner->assertTrue(password_verify($rawPassword, $hashed), "Password mentah harus cocok dengan hash");
        $runner->assertFalse(password_verify('wrongPassword', $hashed), "Password yang salah tidak boleh cocok");
    }

    public function testRoleCheckingLogic(TestRunner $runner): void
    {
        // Simulasi pemeriksaan peran
        $userRole = 'admin';
        
        $isAdmin = in_array($userRole, ['admin', 'petugas'], true);
        $runner->assertTrue($isAdmin, "Role 'admin' harus diizinkan dalam daftar ['admin', 'petugas']");

        $isStaffOnly = in_array('pelanggan', ['admin', 'petugas'], true);
        $runner->assertFalse($isStaffOnly, "Role 'pelanggan' tidak boleh lolos otorisasi admin/petugas");
    }
}
