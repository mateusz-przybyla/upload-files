<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

function specifyAudioFileFormat($fileExtension)
{
  switch ($fileExtension) {
    case "mpeg" || "mp3":
      return new FFMpeg\Format\Audio\Mp3();
      break;
    case "wav":
      return new FFMpeg\Format\Audio\Wav();
      break;
  }
}

if (!empty($_FILES['file'])) {
  $gigaBytetoByte = 1073741824;

  // backend file size validation
  if (
    $_FILES['file']['size'] >=
    $gigaBytetoByte
  ) {
    echo "Sorry, your file is too large.";
    exit;
  }

  $targetDir = __DIR__ . "/../uploads/";
  $filename = basename($_FILES['file']['name']);
  $fileTypeInfo = explode("/", $_FILES['file']['type']);

  // nazwa pliku w zależności od jego typu
  $fileTypeInfo[0] == "audio" ? $targetFilePath = $targetDir . "@" . $filename : $targetFilePath = $targetDir . $filename;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
    $query = $db->prepare('INSERT INTO files (file_name, uploaded_on) VALUES (:file_name, NOW())');
    $query->bindValue(':file_name', $filename, PDO::PARAM_STR);
    $query->execute();

    // skróć plik audio do 120s
    if ($fileTypeInfo[0] == "audio") {
      $ffmpeg = FFMpeg\FFMpeg::create(
        [
          'ffmpeg.binaries'  => $_ENV['FFMPEG_BINARIES'],
          'ffprobe.binaries' => $_ENV['FFPROBE_BINARIES'],
          'timeout'          => 3600,
          'ffmpeg.threads'   => 12,
        ]
      );

      $audio = $ffmpeg->open($targetFilePath, "audio"); // dodałem drugi parametr do metody open, bo wbudowane metody błędnie odczytywały format pliku
      $format = specifyAudioFileFormat($fileTypeInfo[1]);
      $audio->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds($_ENV['AUDIO_DURATION']));
      $audio->save($format, $targetDir . $filename); // zapisz skrócony plik audio

      unlink($targetFilePath); // usuń oryginalny plik audio
    }

    echo "File Uploaded";
  } else {
    echo "File not uploaded";
  }
}
