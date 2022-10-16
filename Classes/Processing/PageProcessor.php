<?php


namespace Heidtech\SiteChecker\Processing;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Heidtech\SiteChecker\Logging\PageInfo;
use Psr\Http\Message\ResponseInterface;

class PageProcessor
{
    public function processInternalPage(string $page): PageInfo
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $page);
        } catch (GuzzleException $exception) {
            return $this->generatePageInfoFromException($page, $exception);
        }

        $statusCode = $response->getStatusCode();

        $pageInfo = $this->preparePageInfoFromResponse($page, $response);

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
            return $this->generatePageInfoFromException($page, $exception);
        }

        return $this->preparePageInfoFromResponse($page, $response);
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

    protected function generatePageInfoFromException(string $page, GuzzleException $exception): PageInfo
    {
        $statusCode = $exception->getCode();

        $pageInfo = new PageInfo();
        $pageInfo->setUrl($page);
        $pageInfo->setStatusCode($statusCode);
        $pageInfo->setErrorMessage($exception->getMessage());

        return $pageInfo;
    }

    protected function preparePageInfoFromResponse(string $page, ResponseInterface $response): PageInfo
    {
        $statusCode = $response->getStatusCode();

        $pageInfo = new PageInfo();
        $pageInfo->setUrl($page);
        $pageInfo->setStatusCode($statusCode);
        $pageInfo->setErrorMessage($response->getReasonPhrase());

        return $pageInfo;
    }

}