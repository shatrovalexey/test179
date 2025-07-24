<?php
namespace Alexe\Test179;

/**
* Базовый класс
*/
abstract class Base
{
    /**
    * Конструктор
    *
    * @param array - настройки класса
    */
    public function __construct(protected array $_config) {}
}