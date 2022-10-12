<?php


namespace Heidtech\SiteChecker\Task;


use Heidtech\SiteChecker\Processing\SiteProcessor;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class CheckSiteTask extends AbstractTask
{

    public function execute()
    {
        $siteProcessor = new SiteProcessor();
        $siteProcessor->processSite('http://typo3-extensions.ddev.site/');
        return true;
    }
}