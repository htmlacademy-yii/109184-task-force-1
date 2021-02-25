<?php

declare(strict_types=1);

namespace TaskForce;
use \Exceptions\CustomException;

class Converter
{
    const DELIMITER = ',';

    private $filename;
    private $fp;
    private $tableName;

    private $result = [];
    private $error = null;

    function __construct(string $filename, string $tableName)
    {
        $this->filename = $filename;
        $this->tableName = $tableName;
    }

    public function import() : void
    {
        if (!file_exists($this->filename)) {
            throw new CustomException("Файл не существует");
        }

        $this->fp = file_get_contents($this->filename);

        if (!$this->fp) {
            throw new CustomException("Не удалось открыть файл на чтение");
        }

        $this->fp = explode(PHP_EOL, $this->fp);

        foreach ($this->fp as $key => $row) {
            if ($key == 0) continue;
            $this->result[] = $row;
        }
    }

    public function getSQL() : void 
    {
        $query = "INSERT INTO `{$this->tableName}` VALUES ";
        $queryBody = [];
        $id = 1;

        foreach ($this->result as $key => $value) {
            $params = $this->getNextLine($value);
            if ($params[0] == "") continue;

            $queryBody[] = "($id, '{$params[0]}', {$params[1]}, {$params[2]})";
            $id++;
        }

        if (file_put_contents($this->tableName . ".sql", $query . implode(self::DELIMITER, $queryBody)) === false) {
            throw new CustomException("Не удалось записать файл");
        }
    }

    private function getHeaderData() : ?array 
    {
    	$this->fp->rewind();

        $data = $this->fp->current();

        return $data;
    }

    private function getNextLine(string $str) : array 
    {
        return explode(self::DELIMITER, $str);
    }
}