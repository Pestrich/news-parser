<?php

namespace App\Models;

abstract class Model
{
    protected string $table;

    protected \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
