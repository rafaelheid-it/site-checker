<?php


namespace Heidtech\SiteChecker\Logging;


use TYPO3\CMS\Core\Core\Environment;

class Logger
{
    public static function logPageInfo(PageInfo $pageInfo)
    {
        $logFile = fopen(Environment::getPublicPath() . '/log.txt', 'a');
        fwrite($logFile, "{$pageInfo->getUrl()}: {$pageInfo->getStatusCode()}, {$pageInfo->getErrorMessage()}\n");
    }
}