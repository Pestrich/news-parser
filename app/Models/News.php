<?php

namespace App\Models;

final class News extends Model
{
    protected string $table = 'news';

    public function getNewsList(): array
    {
        $sth = $this->pdo->prepare('SELECT id, title, content, published_at FROM ' . $this->table);
        $sth->execute();

        return $sth->fetchAll(
            \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
            News::class,
            [
                $this->pdo,
            ]
        );
    }

    public function getNews(int $id): ?News
    {
        $sth = $this->pdo->prepare('SELECT id, title, content, image_url, published_at FROM ' . $this->table . ' WHERE id = ?');
        $sth->execute([$id]);

        $sth->setFetchMode(
            \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
            News::class,
            [
                $this->pdo,
            ]
        );

        return $sth->fetch();
    }

    public function addNews(array $params): bool
    {
        $sth = $this->pdo->prepare(
            'INSERT INTO ' . $this->table . ' (title, content, image_url, published_at) VALUES (:title, :content, :image_url, :published_at)'
        );

        return $sth->execute([
            ':title' => $params['title'],
            ':content' => $params['content'],
            ':image_url' => $params['image_url'],
            ':published_at' => $params['published_at'],
        ]);
    }

    public function clearTable(): bool
    {
        $sth = $this->pdo->prepare('TRUNCATE TABLE ' . $this->table);

        return $sth->execute();
    }
}
