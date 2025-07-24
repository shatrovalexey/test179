<?php
namespace Alexe\Test179;

/**
* Кодек
*/
class Codec extends Base
{
    /**
    * Хэш из строки
    *
    * @param array<string> - значения
    *
    * @return string
    */
    public static function getHash(string ... $values): string
    {
        return array_reduce($values, fn (string $rest, string $value): string => sha1($rest . ($value ?? '')), '');
    }

    /**
    * JSON и строки
    *
    * @param string - строка
    *
    * @return mixed
    * @throws \Throwable
    */
    public static function getDecodeJson(string $value)
    {
        return json_decode($value, true, \JSON_THROW_ON_ERROR);
    }

    /**
    * Строка из JSON
    *
    * @param mixed - данные
    *
    * @return ?string
    */
    public static function getEncodeJson($data): ?string
    {
        return json_encode($data);
    }

    /**
    * Полнотекстовый запрос из строки
    *
    * @param ?string $value - строка
    * @param string $rx - рег.выр. для поиска слова
    * @param string $result - шаблон для подстановки слова в синтаксис FTS
    *
    * @return ?string
    */
    public static function getFts(?string $value, string $rx = '{[\wа-яА-ЯёЁ]{3,}}usx', string $result = '+%s* '): ?string
    {
        return $value && preg_match_all($rx, mb_strtolower($value), $matches)
            ? implode('', array_map(fn (string $word): string => sprintf($result, $word)
                , array_unique(array_shift($matches))
            ))
            : null;
    }
}