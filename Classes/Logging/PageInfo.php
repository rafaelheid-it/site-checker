<?php


namespace Heidtech\SiteChecker\Logging;


class PageInfo
{
    protected string $url;

    protected int $statusCode;

    protected string $statusMessage;

    protected array $linksInPage = [];

    public function getVars(): array
    {
        return get_object_vars($this);
    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    /**
     * @param string $statusMessage
     */
    public function setStatusMessage(string $statusMessage): void
    {
        $this->statusMessage = $statusMessage;
    }

    /**
     * @return array
     */
    public function getLinksInPage(): array
    {
        return $this->linksInPage;
    }

    /**
     * @param array $linksInPage
     */
    public function setLinksInPage(array $linksInPage): void
    {
        $this->linksInPage = $linksInPage;
    }
}