<?php

session_start();

require_once "database.php";

if (!empty($_FILES['file'])) {
  $gigaBytetoByte = 1073741824;

  //backend file size validation
  if (
    $_FILES['file']['size'] >=
    $gigaBytetoByte
  ) {
    echo "Sorry, your file is too large.";
    exit;
  }

  $targetDir = "uploads/";
  $filename = basename($_FILES['file']['name']);
  $targetFilePath = $targetDir . $filename;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
    $query = $db->prepare('INSERT INTO files (file_name, uploaded_on) VALUES (:file_name, NOW())');
    $query->bindValue(':file_name', $filename, PDO::PARAM_STR);
    $query->execute();

    echo "File Uploaded";
  } else {
    echo "File not uploaded";
  }
}
