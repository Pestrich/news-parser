<?php

namespace App\Parsers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom;

final class RbcNewsParser extends AbstractNewsParser
{
    private const URL = 'https://www.rbc.ru/v10/ajax/get-news-feed/project/rbcnews/limit/15';

    public function parse(): void
    {
        $newsList = $this->getNewsList();

        if (!empty($newsList)) {
            $newsList = $this->prepareNewsList($newsList);

            $this->model->clearTable();

            foreach ($newsList as $news) {
                $this->model->addNews($news);
            }
        }
    }

    private function getNewsList(): array
    {
        $client = new Client();

        try {
            $response = $client->get(self::URL);
        } catch (GuzzleException $exception) {
            throw new \Exception('Не удалось получить ответ от сайта новостей: ' . self::URL);
        }

        $content = $response->getBody()->getContents();
        $decodedContent = json_decode($content, true);

        return $decodedContent['items'] ?? [];
    }

    private function prepareNewsList(array $newsList): array
    {
        $result = [];

        foreach ($newsList as $news) {
            try {
                $url = $this->getNewsDetailUrl($news['html']);

                $newsParams = $this->getNewsDetail($url);
                $newsParams['published_at'] = date('Y-m-d H:i:s', $news['publish_date_t']);

                $result[] = $newsParams;
            } catch (\Exception $exception) {
                $this->logError($exception);

                continue;
            }
        }

        return $result;
    }

    private function getNewsDetail(string $url): array
    {
        $html = $this->getNewsDetailHtml($url);
        $dom = (new Dom())->loadStr($html);

        return [
            'title' => $this->getNewsDetailTitle($dom),
            'image_url' => $this->getNewsDetailImageUrl($dom),
            'content' => $this->getNewsDetailContent($dom),
        ];
    }

    private function getNewsDetailUrl(string $html): string
    {
        $dom = (new Dom())->loadStr($html);

        return $dom->find('a')->getAttribute('href');
    }

    private function getNewsDetailHtml(string $url): string
    {
        $client = new Client();

        try {
            $response = $client->get($url);
        } catch (GuzzleException $exception) {
            throw new \Exception('Не удалось получить ответ от сайта новостей: ' . $url);
        }

        return $response->getBody();
    }

    private function getNewsDetailTitle(Dom $dom): string
    {
        $node = $dom->find('.article__header__title .article__header__title-in');

        if ($node->count() === 0) {
            throw new \Exception('Не удалось получить заголовок новости');
        }

        return $node->text;
    }

    private function getNewsDetailImageUrl(Dom $dom): ?string
    {
        $node = $dom->find('.article__main-image__wrap img');

        if ($node->count() > 0) {
            return $node->getAttribute('src');
        }

        return null;
    }

    private function getNewsDetailContent(Dom $dom): ?string
    {
        $divNodes = $dom->find('.article__text div');

        if ($divNodes->count() > 0) {
            $divNodes->each(static function ($item) {
                $item->delete();
            });
        }

        $blockquoteNodes = $dom->find('.article__text blockquote');

        if ($blockquoteNodes->count() > 0) {
            $blockquoteNodes->each(static function ($item) {
                $item->delete();
            });
        }

        $iframeNodes = $dom->find('iframe');

        if ($iframeNodes->count() > 0) {
            $iframeNodes->each(static function ($item) {
                $item->delete();
            });
        }

        $node = $dom->find('.article__text');

        if ($node->count() > 0) {
            $html = '';

            $node->each(static function ($item) use (&$html) {
                $html .= $item->outerHTML;
            });

            return strip_tags($html, ['p', 'ul', 'li']);
        }

        return null;
    }
}
