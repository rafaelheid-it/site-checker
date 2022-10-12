<?php


namespace Heidtech\SiteChecker\Logging;


class PageInfo
{
    protected string $url;

    protected int $statusCode;

    protected string $errorMessage;

    protected array $linksInPage = [];


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
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
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