<?php
/**
 * Nova Hotel — tek dosyada veritabanı bağlantısı (XAMPP varsayılanları).
 * phpMyAdmin'de veritabanı oluşturduktan sonra aşağıdaki sabitleri düzenleyin.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = '127.0.0.1';
$db_name = 'nova_hotel';
$db_user = 'root';
$db_pass = '';
$db_charset = 'utf8mb4';

$mysqli = @new mysqli($db_host, $db_user, $db_pass);
if ($mysqli->connect_errno) {
    $mysqli = null;
} else {
    $mysqli->set_charset($db_charset);
    if (!$mysqli->select_db($db_name)) {
        /* phpMyAdmin'de veritabanını oluşturana kadar $mysqli kullanılamayabilir */
    }
}
