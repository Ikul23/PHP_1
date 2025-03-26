<?php

abstract class Book
{
  protected $title;
  protected $author;
  protected $readCount = 0;

  public function __construct($title, $author)
  {
    $this->title = $title;
    $this->author = $author;
  }

  abstract public function getForHands(): string;

  public function incrementReadCount()
  {
    $this->readCount++;
  }

  public function getReadCount(): int
  {
    return $this->readCount;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getAuthor(): string
  {
    return $this->author;
  }
}

class DigitalBook extends Book
{
  private $downloadUrl;

  public function __construct($title, $author, $downloadUrl)
  {
    parent::__construct($title, $author);
    $this->downloadUrl = $downloadUrl;
  }

  public function getForHands(): string
  {
    $this->incrementReadCount();
    return $this->downloadUrl;
  }
}

class PaperBook extends Book
{
  private $libraryAddress;

  public function __construct($title, $author, $libraryAddress)
  {
    parent::__construct($title, $author);
    $this->libraryAddress = $libraryAddress;
  }

  public function getForHands(): string
  {
    $this->incrementReadCount();
    return $this->libraryAddress;
  }
}

// Пример использования
$digitalBook = new DigitalBook("PHP для профессионалов", "Иван Иванов", "https://example.com/download/php-book");
echo $digitalBook->getForHands(); // Выведет ссылку на скачивание

$paperBook = new PaperBook("Изучаем SQL", "Петр Петров", "ул. Ленина, 123, Библиотека №5");
echo $paperBook->getForHands(); // Выведет адрес библиотеки

class A
{
  public function foo()
  {
    static $x = 0;
    echo ++$x . "\n";
  }
}

class B extends A {}

$a1 = new A();
$b1 = new B();

echo "a1->foo(): ";
$a1->foo(); // Выведет: 1

echo "b1->foo(): ";
$b1->foo(); // Выведет: 1

echo "a1->foo(): ";
$a1->foo(); // Выведет: 2

echo "b1->foo(): ";
$b1->foo(); // Выведет: 2