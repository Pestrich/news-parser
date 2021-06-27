<?php

namespace App\Parsers;

use App\Models\Model;

abstract class AbstractNewsParser implements NewsParserInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function logError(\Exception $exception): void
    {
        echo sprintf(
            'Ошибка: %s. Файл: %s. Строка: %u.' . PHP_EOL,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}
