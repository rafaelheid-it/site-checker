<?php


namespace Heidtech\SiteChecker\Logging;


use TYPO3\CMS\Core\Core\Environment;

class Logger
{
    public static function logPageInfo(PageInfo $pageInfo)
    {
        $logFile = fopen(Environment::getPublicPath() . '/log.txt', 'a');
        fwrite($logFile, "{$pageInfo->getUrl()}: {$pageInfo->getStatusCode()}, {$pageInfo->getStatusMessage()}\n");
    }

    public static function logPageInfoToJson(PageInfo $pageInfo)
    {
        $jsonFilePath = Environment::getPublicPath() . '/log.json';
        if (!file_exists($jsonFilePath)) {
            file_put_contents($jsonFilePath, '[]');
        }
        $json = json_decode(file_get_contents($jsonFilePath), true);
        $json[] = $pageInfo->getVars();
        file_put_contents($jsonFilePath, json_encode($json));
    }
}