<?php
$dsn = "mysql:host=localhost;dbname=evenements;charset=utf8";
$user = "Admin";
$pass = "123456";
$pdo = new PDO($dsn, $user, $pass);

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>