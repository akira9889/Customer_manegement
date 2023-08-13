<?php
namespace Traits;

trait EncryptTrait
{
    private function encrypt(string $string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }
}
