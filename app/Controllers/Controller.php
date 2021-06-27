<?php

namespace App\Controllers;

use App\Models\Model;
use Twig\Environment;

abstract class Controller
{
    protected Model $model;

    protected Environment $twig;

    public function __construct(Model $model, Environment $twig)
    {
        $this->model = $model;
        $this->twig = $twig;
    }
}
