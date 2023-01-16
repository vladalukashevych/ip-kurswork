<?php

namespace core;

class DB
{
    protected $pdo;

    public function __construct($hostname, $login, $password, $database)
    {
        $this->pdo = new \PDO("mysql: host={$hostname};dbname={$database}", $login, $password);
    }

    public function select($tableName, $fieldsList = "*", $conditionArray = null)
    {
        if (is_string($fieldsList))
            $fieldsListString = $fieldsList;
        if (is_array($fieldsList))
            $fieldsListString = implode(', ', $fieldsList);
        $wherePart = '';
        if (is_array($conditionArray)) {
            $parts = [];
            foreach ($conditionArray as $key => $value) {
                $parts [] = "{$key} = :{$key}";
            }
            $wherePart = "where " . implode(' and ', $parts);
        }

        $res = $this->pdo->prepare(
            "select {$fieldsListString} from {$tableName} {$wherePart}"
        );
        $res->execute($conditionArray);
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update($tableName, $newValuesArray, $conditionArray)
    {
        $setParts = [];
        $paramsArray = [];
        foreach ($newValuesArray as $key => $value) {
            $setParts [] = "{$key} = :set{$key}";
            $paramsArray['set' . $key] = $value;
        }
        $setPartsString = implode(", ", $setParts);
        $whereParts = [];
        foreach ($conditionArray as $key => $value) {
            $whereParts [] = "{$key} = :{$key}";
            $paramsArray[$key] = $value;
        }
        $wherePartsString = "where " . implode(' and ', $whereParts);
        $res = $this->pdo->prepare("update {$tableName} set {$setPartsString} {$wherePartsString}");
        $res->execute($paramsArray);
    }

    public function insert($tableName, $newRowArray)
    {
        $fieldsArray = array_keys($newRowArray);
        $fieldsListString = implode(', ', $fieldsArray);
        $paramsArray = [];
        foreach ($newRowArray as $key => $value) {
            $paramsArray [] = ':' . $key;
        }
        $valuesListString = implode(', ', $paramsArray);
        $res = $this->pdo->prepare("insert into {$tableName} ({$fieldsListString}) values({$valuesListString})");
        $res->execute($newRowArray);
    }

    public function delete($tableName, $conditionArray)
    {
        $whereParts = [];
        foreach ($conditionArray as $key => $value)
            $whereParts [] = "{$key} = :{$key}";
        $wherePartsString = "where " . implode(' and ', $whereParts);
        $res = $this->pdo->prepare("delete from {$tableName} {$wherePartsString}");
        $res->execute($conditionArray);
    }

    public function selectLike($tableName, $fieldsList = "*", $likeField = null, $likeString = null)
    {
        if (is_string($fieldsList))
            $fieldsListString = $fieldsList;
        if (is_array($fieldsList))
            $fieldsListString = implode(', ', $fieldsList);
        $wherePart = "where {$likeField} like '%{$likeString}%'";

        $res = $this->pdo->prepare(
            "select {$fieldsListString} from {$tableName} {$wherePart}"
        );
        $res->execute();
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }
}