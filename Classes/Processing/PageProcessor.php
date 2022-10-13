<?php


namespace Heidtech\SiteChecker\Processing;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Heidtech\SiteChecker\Logging\PageInfo;

class PageProcessor
{
    public function processInternalPage(string $page): PageInfo
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $page);
        } catch (GuzzleException $exception) {
            // @todo refactor (same code as in processExternalPage)
            $statusCode = $exception->getCode();
            $pageInfo = new PageInfo();
            $pageInfo->setUrl($page);
            $pageInfo->setStatusCode($statusCode);
            $pageInfo->setErrorMessage($exception->getMessage());
            return $pageInfo;
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

    public function processExternalPage(string $page): ?PageInfo
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $page);
        } catch (GuzzleException $exception) {
            $statusCode = $exception->getCode();
            $pageInfo = new PageInfo();
            $pageInfo->setUrl($page);
            $pageInfo->setStatusCode($statusCode);
            $pageInfo->setErrorMessage($exception->getMessage());
            return $pageInfo;
        }

        $statusCode = $response->getStatusCode();

        $pageInfo = new PageInfo();
        $pageInfo->setUrl($page);
        $pageInfo->setStatusCode($statusCode);
        $pageInfo->setErrorMessage($response->getReasonPhrase());

        return $pageInfo;
    }

    protected function findLinksInPage(\DOMDocument $page): array
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