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
    private $files;

    function __construct()
    {}

    public function createSQLFile(string $file) : void
    {
        $id = 1;
        $queryBody = [];

        if (!file_exists("data/" . $file)) {
            throw new CustomException("Файл не существует");
        }

        $this->fp = file_get_contents("data/" . $file);

        if (!$this->fp) {
            throw new CustomException("Не удалось открыть файл на чтение");
        }

        $this->fp = explode(PHP_EOL, $this->fp);

        foreach ($this->fp as $key => $row) {
            $params = $this->getNextLine($row);
            if ($key == 0 || $params[0] == "") continue;

            $queryBody[] = "($id, '".implode("','", $params)."')";
            $id++;
        }

        $this->tableName = $this->getTableName($file);

        $query = "INSERT INTO `$this->tableName` VALUES ";

        if (file_put_contents("dump/" . $this->tableName . ".sql", $query . implode(self::DELIMITER, $queryBody)) === false) {
            throw new CustomException("Не удалось записать файл");
        }
    }

    public function getFilesList() : ?array 
    {
    	if ($handle = opendir('data')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $this->files[] = $entry;
                }
            }
            closedir($handle);
        }

        return $this->files;
    }

    private function getNextLine(string $str) : array 
    {
        return explode(self::DELIMITER, $str);
    }

    private function getTableName(string $entry) : string 
    {
        $ext = pathinfo($entry, PATHINFO_EXTENSION);
        $entry = basename($entry, ".$ext");

        return $entry;
    }
}