<?php

namespace App\Controllers;

use App\Models\News;

final class HomeController extends Controller
{
    private const MAS_CONTENT_LENGTH = 200;

    public function index(): void
    {
        $news = $this->model->getNewsList();

        $news = array_map(static function (News $item): array {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'content' => strlen($item->content) > self::MAS_CONTENT_LENGTH
                    ? substr($item->content, 0, self::MAS_CONTENT_LENGTH) . '...'
                    : $item->content,
                'published_at' => $item->published_at,
            ];
        }, $news);

        echo $this->twig->render('home/index.html.twig', [
            'news' => $news ?? [],
        ]);
    }
}
