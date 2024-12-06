# Upload Files

## Overview

Prosta strona z formularzem za pomocą którego można przesyłać pliki do 1GB na serwer.
Po załadowaniu pliku skrypt generuje link do pobrania pliku.
Skrypt zapisuję nazwę przesłanego pliku w bazie danych PostgreSQL.

## How to start

Uruchom projekt lokalnie na swoim pc:

- włącz XAMPP Control Panel oraz uruchom Apache serwer,
- upewnij się, że Twój Apache serwer akceptuje bazę danych PostgresSQL (odpowiednia konfiguracja pliku php.ini),
- pobierz repozytorium na swój lokalny komputer do folderu /htdocs,

```bash
  git clone https://github.com/mateusz-przybyla/upload-files.git
```

- utwórz nową bazę danych na pgAdmin o nazwie upload_files oraz uruchom kod z pliku queries.sql w Query Tool,
- wpisz hasło do pgAdmin w pliku config.php,
- otwórz przeglądarkę i uruchom stronę lokalnie: http://localhost/upload-files/

## Screenshots

- Formularz:

![](./readme/formularz.jpg)

- Wygenerowany link po załadowaniu pliku:

![](./readme/link.jpg)

- Walidacja sprawdzająca wielkość pliku:

![](./readme/walidacja.jpg)
