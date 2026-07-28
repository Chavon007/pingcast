<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=pingcast', 'pingcast_user', 'pingcast123', [
        PDO::MYSQL_ATTR_GET_SERVER_PUBLIC_KEY => true,
    ]);
    echo "Connected successfully!\n";
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage() . "\n";
}