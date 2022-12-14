<?php


namespace Heidtech\SiteChecker\Processing;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Heidtech\SiteChecker\Logging\PageInfo;
use Psr\Http\Message\ResponseInterface;

class PageProcessor
{
    protected ProcessingConfiguration $configuration;

    public function __construct(ProcessingConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

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

        $contentType = $response->getHeader('Content-Type');
        if (!str_contains($contentType[0], 'html')) {
            return $pageInfo;
        }

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
        $anchorElements = $page->getElementsByTagName('a');

        /** @var \DOMNode $anchorElement */
        foreach ($anchorElements as $anchorElement) {
            $linkUrls[] = $anchorElement->attributes->getNamedItem('href')->nodeValue;
        }

        if ($this->configuration->isShouldProcessScript()) {
            $linkUrls = array_merge($linkUrls, $this->findElementUrlsByTagAndAttribute($page, 'script', 'src'));
        }

        if ($this->configuration->isShouldProcessImg()) {
            $linkUrls = array_merge($linkUrls, $this->findElementUrlsByTagAndAttribute($page, 'img', 'src'));
        }

        if ($this->configuration->isShouldProcessLink()) {
            $linkUrls = array_merge($linkUrls, $this->findElementUrlsByTagAndAttribute($page, 'link', 'href'));
        }

        if ($this->configuration->isShouldProcessIframe()) {
            $linkUrls = array_merge($linkUrls, $this->findElementUrlsByTagAndAttribute($page, 'iframe', 'src'));
        }

        if ($this->configuration->isShouldProcessPicture()) {
            $linkUrls = array_merge($linkUrls, $this->findElementUrlsByTagAndAttribute($page, 'source', 'srcset'));
        }

        if ($this->configuration->isShouldProcessAudioAndVideo()) {
            $linkUrls = array_merge($linkUrls, $this->findElementUrlsByTagAndAttribute($page, 'source', 'src'));
        }

        return $linkUrls;
    }

    protected function generatePageInfoFromException(string $page, GuzzleException $exception): PageInfo
    {
        $statusCode = $exception->getCode();

        $pageInfo = new PageInfo();
        $pageInfo->setUrl($page);
        $pageInfo->setStatusCode($statusCode);
        $pageInfo->setStatusMessage($exception->getMessage());

        return $pageInfo;
    }

    protected function preparePageInfoFromResponse(string $page, ResponseInterface $response): PageInfo
    {
        $statusCode = $response->getStatusCode();

        $pageInfo = new PageInfo();
        $pageInfo->setUrl($page);
        $pageInfo->setStatusCode($statusCode);
        $pageInfo->setStatusMessage($response->getReasonPhrase());

        return $pageInfo;
    }

    protected function findElementUrlsByTagAndAttribute(\DOMDocument &$page, string $tag, string $attribute)
    {
        $elementUrls = [];
        $linkElements = $page->getElementsByTagName($tag);

        /** @var \DOMNode $linkElement */
        foreach ($linkElements as $linkElement) {
            $src = $linkElement->attributes->getNamedItem($attribute);
            if ($src) {
                $elementUrls[] = $src->nodeValue;
            }
        }

        return $elementUrls;
    }

}