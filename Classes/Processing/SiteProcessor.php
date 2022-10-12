<?php


namespace Heidtech\SiteChecker\Processing;


class SiteProcessor
{
    protected array $linkQueue = [];

    protected array $processedPages = [];

    public function processSite(string $siteStartUrl)
    {
        $pageProcessor = new PageProcessor();

        $pageInfo = $pageProcessor->processPage($siteStartUrl);
    }
}