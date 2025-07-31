<?php
// Clave y IV (inicializar desde .env o constantes)
define('AES_KEY', 'clave-secreta-1234567890123456'); // 32 bytes
define('AES_IV', '1234567890123456'); // 16 bytes

function cifrarAES($data) {
    return openssl_encrypt($data, 'AES-256-CBC', AES_KEY, 0, AES_IV);
}

function descifrarAES($data) {
    return openssl_decrypt($data, 'AES-256-CBC', AES_KEY, 0, AES_IV);
}

function generarFirma($data, $secret = 'clave-hmac') {
    return hash_hmac('sha256', $data, $secret);
}

function verificarFirma($data, $firma, $secret = 'clave-hmac') {
    return hash_equals(generarFirma($data, $secret), $firma);
}
