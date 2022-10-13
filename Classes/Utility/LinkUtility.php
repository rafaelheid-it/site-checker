<?php


namespace Heidtech\SiteChecker\Utility;


use TYPO3\CMS\Core\Http\Uri;

class LinkUtility
{
    public static function prefixLinkWithSchemeAndHost(string $link, string $scheme, string $host): string
    {
        $prefixedLink = $link;
        if (!self::hasLinkHost($link)) {
            $prefixedLink = str_starts_with($prefixedLink, '/') ? $prefixedLink : '/' . $prefixedLink;
            $prefixedLink = $scheme . '://' . $host . $prefixedLink;
        }
        return $prefixedLink;
    }

    public static function hasLinkHost(string $link): bool
    {
        $uri = new Uri($link);
        return !empty($uri->getHost());
    }

    public static function isLinkInternal(string $link, string $host): bool
    {
        $uri = new Uri($link);
        return $uri->getHost() === '' || $uri->getHost() === $host;
    }
}