<?php
$dsn = "mysql:host=localhost;dbname=evenements;charset=utf8";
$user = "celine";
$pass = "celine";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>