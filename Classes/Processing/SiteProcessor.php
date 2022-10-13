<?php


namespace Heidtech\SiteChecker\Processing;


use Heidtech\SiteChecker\Logging\Logger;
use Heidtech\SiteChecker\Utility\LinkUtility;
use TYPO3\CMS\Core\Http\Uri;

class SiteProcessor
{
    protected array $linkQueue = [
        'internal' => [],
        'external' => []
    ];

    protected array $processedPages = [];

    protected PageProcessor $pageProcessor;

    protected Uri $startPage;

    public function __construct()
    {
        $this->pageProcessor = new PageProcessor();
    }

    public function processSite(string $siteStartUrl)
    {
        $this->startPage = new Uri($siteStartUrl);
        $this->linkQueue['internal'][] = $siteStartUrl;

        while(!empty($this->linkQueue['internal'])) {
            $pageUrl = array_shift($this->linkQueue['internal']);
            $pageInfo = $this->pageProcessor->processInternalPage($pageUrl);
            Logger::logPageInfo($pageInfo);

            foreach ($pageInfo->getLinksInPage() as $link) {
                if (!in_array($link, $this->processedPages)) {
                    $this->addLinkToQueue($link, $pageInfo->getUrl());
                }
            }

            $this->processExternalLinksForPage($pageUrl);
            $this->processedPages[] = $pageInfo->getUrl();
        }
    }

    protected function processExternalLinksForPage(string $page)
    {
        if (isset($this->linkQueue['external'][$page])) {
            $externalLinksForPage = $this->linkQueue['external'][$page];
            foreach ($externalLinksForPage as $externalLink) {
                $pageInfo = $this->pageProcessor->processExternalPage($externalLink);
                Logger::logPageInfo($pageInfo);
            }
            unset($this->linkQueue['external'][$page]);
        }
    }

    protected function addLinkToQueue(string $link, string $parentPage)
    {
        if (LinkUtility::isLinkInternal($link, $this->startPage->getHost())) {
            $prefixedInternalLink = LinkUtility::prefixLinkWithSchemeAndHost(
                $link,
                $this->startPage->getScheme(),
                $this->startPage->getHost()
            );

            $this->addInternalLinkToQueue($prefixedInternalLink);
        } else {
            $this->addExternalLinkToQueue($link, $parentPage);
        }
    }

    protected function addExternalLinkToQueue(string $externalLink, string $parentPage)
    {
        if (!isset($this->linkQueue['external'][$parentPage])) {
            $this->linkQueue['external'][$parentPage] = [];
        }

        $this->linkQueue['external'][$parentPage][] = $externalLink;
    }

    protected function addInternalLinkToQueue(string $internalLink)
    {
        $this->linkQueue['internal'][] = $internalLink;
    }
}