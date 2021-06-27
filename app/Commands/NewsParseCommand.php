<?php

use App\Parsers as Parsers;
use App\Parsers\NewsParserInterface;
use League\Container\Container;

/** @var Container $container */
$container = require __DIR__ . '/../../bootstrap/app.php';

$parsers = [
    Parsers\RbcNewsParser::class,
];

echo 'Парсинг новостей начался...' . PHP_EOL;

foreach ($parsers as $parserName) {
    try {
        $parser = $container->get($parserName);

        if ($parser instanceof NewsParserInterface) {
            $parser->parse();
        } else {
            throw new Exception(sprintf(
                'Некорректный класс парсера %s. Будет пропущен.' . PHP_EOL,
                $parserName,
            ));
        }
    } catch (Exception $exception) {
        echo sprintf(
            'Ошибка: %s. Файл: %s. Строка: %u.' . PHP_EOL,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}

echo 'Парсинг новостей окончен.' . PHP_EOL;
