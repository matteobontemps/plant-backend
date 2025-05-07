<?php
namespace App\Service;

class CryptoService
{
    private string $key;
    private string $cipher = 'AES-256-CBC';
    private string $iv;

    public function __construct(string $key)
    {
        $this->key = $key;
        // IV fixe ou stockée séparément — attention, elle doit faire 16 octets pour AES-256-CBC
        $this->iv = substr(hash('sha256', 'iv_constant'), 0, 16);
    }

    public function encrypt(string $data): string
    {
        return base64_encode(openssl_encrypt($data, $this->cipher, $this->key, 0, $this->iv));
    }

    public function decrypt(string $encrypted): string
    {
        return openssl_decrypt(base64_decode($encrypted), $this->cipher, $this->key, 0, $this->iv);
    }
}
