
<?php

class FileStorage
{
  private $filename;

  public function __construct($filename)
  {
    $this->filename = $filename;
  }

  // 1. Обработка ошибок при вводе данных
  public function validateInput($name, $birthdate)
  {
    // Проверка имени
    if (empty($name) || !preg_match('/^[А-Яа-яЁё\s-]+$/u', $name)) {
      throw new InvalidArgumentException("Некорректное имя. Используйте только кириллицу.");
    }

    // Проверка даты рождения
    if (!$this->validateDate($birthdate)) {
      throw new InvalidArgumentException("Некорректный формат даты. Используйте ДД-ММ-ГГГГ.");
    }

    return true;
  }

  // Валидация формата даты
  private function validateDate($date)
  {
    // Проверка формата ДД-ММ-ГГГГ
    if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
      return false;
    }

    // Разбор даты
    list($day, $month, $year) = explode('-', $date);

    // Проверка корректности даты
    return checkdate($month, $day, $year)
      && $year > 1900
      && $year <= date('Y');
  }

  // 2. Поиск именинников по дате
  public function findBirthdaysToday()
  {
    $today = date('d-m');
    $birthdayPeople = [];

    if (!file_exists($this->filename)) {
      throw new Exception("Файл не найден");
    }

    $lines = file($this->filename, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
      $parts = explode(', ', $line);
      if (count($parts) == 2) {
        $birthdate = substr($parts[1], -5);
        if ($birthdate === $today) {
          $birthdayPeople[] = $parts[0];
        }
      }
    }

    return $birthdayPeople;
  }

  // 3. Удаление строки
  public function deleteLine($searchTerm)
  {
    if (!file_exists($this->filename)) {
      throw new Exception("Файл не найден");
    }

    $lines = file($this->filename, FILE_IGNORE_NEW_LINES);
    $originalCount = count($lines);

    $lines = array_filter($lines, function ($line) use ($searchTerm) {
      return strpos($line, $searchTerm) === false;
    });

    // Проверка, была ли удалена строка
    if (count($lines) == $originalCount) {
      return false;
    }

    // Запись обновленного содержимого
    file_put_contents($this->filename, implode(PHP_EOL, $lines) . PHP_EOL);
    return true;
  }

  // 4. Добавление записи
  public function addEntry($name, $birthdate)
  {
    try {
      $this->validateInput($name, $birthdate);

      $entry = "$name, $birthdate\n";
      file_put_contents($this->filename, $entry, FILE_APPEND);
      return true;
    } catch (InvalidArgumentException $e) {
      echo "Ошибка: " . $e->getMessage() . "\n";
      return false;
    }
  }

  // Вывод всех записей
  public function listEntries()
  {
    if (!file_exists($this->filename)) {
      echo "Файл пуст или не существует.\n";
      return [];
    }

    $entries = file($this->filename, FILE_IGNORE_NEW_LINES);
    return $entries;
  }
}

// Интерактивное меню
function displayMenu()
{
  echo "\nМеню:\n";
  echo "1. Добавить запись\n";
  echo "2. Найти именинников\n";
  echo "3. Удалить запись\n";
  echo "4. Показать все записи\n";
  echo "5. Выйти\n";
  echo "Выберите действие: ";
}

// Основная программа
function main()
{
  $filename = 'birthday_storage.txt';
  $storage = new FileStorage($filename);

  while (true) {
    displayMenu();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
      case '1':
        echo "Введите имя: ";
        $name = trim(fgets(STDIN));
        echo "Введите дату рождения (ДД-ММ-ГГГГ): ";
        $birthdate = trim(fgets(STDIN));

        if ($storage->addEntry($name, $birthdate)) {
          echo "Запись успешно добавлена.\n";
        }
        break;

      case '2':
        try {
          $birthdayPeople = $storage->findBirthdaysToday();
          if (empty($birthdayPeople)) {
            echo "Сегодня нет именинников.\n";
          } else {
            echo "Сегодня день рождения у: " . implode(", ", $birthdayPeople) . "\n";
          }
        } catch (Exception $e) {
          echo "Ошибка: " . $e->getMessage() . "\n";
        }
        break;

      case '3':
        echo "Введите имя или часть данных для удаления: ";
        $searchTerm = trim(fgets(STDIN));

        if ($storage->deleteLine($searchTerm)) {
          echo "Запись успешно удалена.\n";
        } else {
          echo "Запись не найдена.\n";
        }
        break;

      case '4':
        $entries = $storage->listEntries();
        if (empty($entries)) {
          echo "Нет записей.\n";
        } else {
          echo "Все записи:\n";
          foreach ($entries as $entry) {
            echo $entry . "\n";
          }
        }
        break;

      case '5':
        echo "Выход из программы.\n";
        exit(0);

      default:
        echo "Неверный выбор. Попробуйте снова.\n";
    }
  }
}

// Запуск программы
main();
?>