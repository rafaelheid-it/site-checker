<?php


namespace Heidtech\SiteChecker\Processing;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Heidtech\SiteChecker\Logging\PageInfo;

class PageProcessor
{
    public function processPage(string $page): ?PageInfo
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $page);
        } catch (GuzzleException $exception) {
            return null;
        }

        $statusCode = $response->getStatusCode();

        $pageInfo = new PageInfo();
        $pageInfo->setUrl($page);
        $pageInfo->setStatusCode($statusCode);
        $pageInfo->setErrorMessage($response->getReasonPhrase());

        $pageDocument = new \DOMDocument();
        @$pageDocument->loadHTML($response->getBody()->getContents());

        if ($statusCode >= 200 && $statusCode < 300) {
            $linksInPage = $this->findLinksInPage($pageDocument);
            $pageInfo->setLinksInPage($linksInPage);
        }


        return $pageInfo;
    }

    public function findLinksInPage(\DOMDocument $page): array
    {
        $linkUrls = [];
        $linkElements = $page->getElementsByTagName('a');

        /** @var \DOMNode $linkElement */
        foreach ($linkElements as $linkElement) {
            $linkUrls[] = $linkElement->attributes->getNamedItem('href')->nodeValue;
        }

        return $linkUrls;
    }
}