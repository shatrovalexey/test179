<?php
namespace Alexe\Test179;

/**
* Доступ к СУБД
*/
class DBA extends Base
{
    /**
    * var ?\PDO - подключение к СУБД
    */
    protected ?\PDO $_dbh;

    /**
    * Подключение к СУБД
    *
    * @return \PDO
    */
    public function dbh(): \PDO
    {
        if (!empty($this->_dbh))
            return $this->_dbh;

        $dbh = new \PDO(... array_map(fn (string $key): string => $this->_config[$key], ['dsn', 'user', 'passwd',]));
        if (!empty($this->_config['charset'])
            && preg_match('{^\w+$}uisx', $this->_config['charset'])) {
            $dbh->query("SET names {$this->_config['charset']}");
        }

        return $this->_dbh = $dbh;
    }

    /**
    * Вополнить SQL с аргументами
    *
    * @param string $sql - SQL
    * @param array $args - аргументы
    *
    * @return \PDOStatement
    */
    public function query(string $sql, array $args = []): \PDOStatement
    {
        $sth = $this->dbh()->prepare($sql);
        $sth->execute($args);

        return $sth;
    }
}