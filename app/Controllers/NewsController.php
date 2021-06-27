<?php

namespace App\Controllers;

final class NewsController extends Controller
{
    public function show(int $id): void
    {
        $news = $this->model->getNews($id);

        $news = [
            'id' => $news->id,
            'title' => $news->title,
            'content' => $news->content,
            'image_url' => $news->image_url,
            'published_at' => $news->published_at,
        ];

        echo $this->twig->render('news/show.html.twig', [
            'news' => $news ?? [],
        ]);
    }
}
