<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Heidtech\SiteChecker\Task\CheckSiteTask::class] = [
    'extension' => 'site_checker',
    'title' => 'Check Site',
    'description' => 'Check site for inaccessible pages.',
];