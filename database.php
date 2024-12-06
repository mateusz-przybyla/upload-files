<?php

require_once __DIR__ . '/config.php';

try {
  $dsn = "pgsql:host=$host;port=5432;dbname=$database;";

  $db = new PDO($dsn, $user, $password, [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);

  if ($db) {
    echo "Connected to the $database database successfully!";
  }
} catch (PDOException $error) {
  echo $error->getMessage();
  exit('Unable to connect to database.');
}
