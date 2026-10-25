<?php

namespace Tests\Unit;

use App\Core\Validator;
use Tests\TestRunner;

class ValidationTest
{
    public function testRequiredValidationPasses(TestRunner $runner): void
    {
        $v = Validator::make(['username' => 'budi'], ['username' => 'required']);
        $runner->assertFalse($v->fails(), "Validasi required harus lolos jika field terisi");
    }

    public function testRequiredValidationFails(TestRunner $runner): void
    {
        $v = Validator::make(['username' => ''], ['username' => 'required']);
        $runner->assertTrue($v->fails(), "Validasi required harus gagal jika field kosong");
    }

    public function testNumericValidation(TestRunner $runner): void
    {
        $vValid = Validator::make(['harga' => '50000'], ['harga' => 'numeric']);
        $runner->assertFalse($vValid->fails(), "Validasi numeric harus lolos jika angka");

        $vInvalid = Validator::make(['harga' => 'abcde'], ['harga' => 'numeric']);
        $runner->assertTrue($vInvalid->fails(), "Validasi numeric harus gagal jika string huruf");
    }

    public function testEmailValidation(TestRunner $runner): void
    {
        $vValid = Validator::make(['email' => 'admin@example.com'], ['email' => 'email']);
        $runner->assertFalse($vValid->fails(), "Email valid harus lolos");

        $vInvalid = Validator::make(['email' => 'bukan-email'], ['email' => 'email']);
        $runner->assertTrue($vInvalid->fails(), "Format email salah harus gagal");
    }
}
