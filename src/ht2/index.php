<?php

// 1. Функция с основными арифметическими операциями
function calculateOperation($num1, $num2, $operation)
{
  switch ($operation) {
    case '+':
      return $num1 + $num2;
    case '-':
      return $num1 - $num2;
    case '*':
      return $num1 * $num2;
    case '/':

      return $num2 != 0 ? $num1 / $num2 : "Ошибка: деление на ноль";
    default:
      return "Неизвестная операция";
  }
}

// 2. Функция с использованием switch для выбора операции
function mathOperation($arg1, $arg2, $operation)
{
  switch ($operation) {
    case 'add':
      return calculateOperation($arg1, $arg2, '+');
    case 'subtract':
      return calculateOperation($arg1, $arg2, '-');
    case 'multiply':
      return calculateOperation($arg1, $arg2, '*');
    case 'divide':
      return calculateOperation($arg1, $arg2, '/');
    default:
      return "Неверная операция";
  }
}

// 3. Массив областей и городов
$regions = [
  'Московская область' => ['Москва', 'Зеленоград', 'Клин', 'Химки', 'Люберцы'],
  'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт', 'Гатчина'],
  'Рязанская область' => ['Рязань', 'Касимов', 'Скопин', 'Сасово', 'Ряжск']
];

// Вывод областей и городов
echo "Области и города:\n";
foreach ($regions as $region => $cities) {
  echo $region . ": " . implode(", ", $cities) . "\n";
}

// 4. Транслитерация
$translitMap = [
  'а' => 'a',
  'б' => 'b',
  'в' => 'v',
  'г' => 'g',
  'д' => 'd',
  'е' => 'e',
  'ё' => 'yo',
  'ж' => 'zh',
  'з' => 'z',
  'и' => 'i',
  'й' => 'y',
  'к' => 'k',
  'л' => 'l',
  'м' => 'm',
  'н' => 'n',
  'о' => 'o',
  'п' => 'p',
  'р' => 'r',
  'с' => 's',
  'т' => 't',
  'у' => 'u',
  'ф' => 'f',
  'х' => 'kh',
  'ц' => 'ts',
  'ч' => 'ch',
  'ш' => 'sh',
  'щ' => 'shch',
  'ъ' => '',
  'ы' => 'y',
  'ь' => '',
  'э' => 'e',
  'ю' => 'yu',
  'я' => 'ya'
];

function transliterate($text, $map)
{
  $text = mb_strtolower($text, 'UTF-8');
  $result = '';
  $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

  foreach ($chars as $char) {
    $result .= isset($map[$char]) ? $map[$char] : $char;
  }

  return $result;
}

// Пример использования транслитерации
$testString = "Привет, мир!";
echo "\nТранслитерация: " . transliterate($testString, $translitMap) . "\n";

// 5. Возведение в степень через рекурсию
function power($val, $pow)
{
  // Базовые случаи
  if ($pow == 0) return 1;
  if ($pow == 1) return $val;

  // Отрицательная степень
  if ($pow < 0) return 1 / power($val, -$pow);

  // Четная степень
  if ($pow % 2 == 0) {
    $halfPower = power($val, $pow / 2);
    return $halfPower * $halfPower;
  }

  // Нечетная степень
  return $val * power($val, $pow - 1);
}

// 6. Функция форматирования времени с правильными склонениями
function formatTime()
{
  $hours = date('H');
  $minutes = date('i');

  // Склонения для часов
  $hourWord = match (true) {
    $hours % 10 == 1 && $hours % 100 != 11 => 'час',
    $hours % 10 >= 2 && $hours % 10 <= 4 &&
      ($hours % 100 < 10 || $hours % 100 >= 20) => 'часа',
    default => 'часов'
  };

  // Склонения для минут
  $minuteWord = match (true) {
    $minutes % 10 == 1 && $minutes % 100 != 11 => 'минута',
    $minutes % 10 >= 2 && $minutes % 10 <= 4 &&
      ($minutes % 100 < 10 || $minutes % 100 >= 20) => 'минуты',
    default => 'минут'
  };

  return sprintf("%d %s %d %s", $hours, $hourWord, $minutes, $minuteWord);
}

// Демонстрация функций
echo "\nПример вычислений:\n";
echo "10 + 5 = " . calculateOperation(10, 5, '+') . "\n";
echo "Математическая операция (add): " . mathOperation(10, 5, 'add') . "\n";
echo "Возведение в степень (2^3): " . power(2, 3) . "\n";
echo "Текущее время: " . formatTime() . "\n";
