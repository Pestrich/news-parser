<?php

use App\Controllers as Controllers;
use App\Models as Models;
use App\Parsers as Parsers;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new League\Container\Container;

$container
    ->add(PDO::class)
    ->addArgument('mysql:host=localhost;dbname=news_parser;charset=UTF8')
    ->addArgument('root')
    ->addArgument('root');

$container
    ->add(Environment::class)
    ->addArgument(new FilesystemLoader(__DIR__ . '/../resources/views'));

/* Controllers */
$container
    ->add(Controllers\NewsController::class)
    ->addArgument(Models\News::class)
    ->addArgument(Environment::class);

$container
    ->add(Controllers\HomeController::class)
    ->addArgument(Models\News::class)
    ->addArgument(Environment::class);

/* Models */
$container->add(Models\News::class)->addArgument(PDO::class);

/* Parsers */
$container->add(Parsers\RbcNewsParser::class)->addArgument(Models\News::class);

return $container;
