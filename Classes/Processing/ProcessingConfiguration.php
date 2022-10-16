<?php


namespace Heidtech\SiteChecker\Processing;


class ProcessingConfiguration
{
    protected bool $shouldProcessScript = false;

    protected bool $shouldProcessImg = false;

    protected bool $shouldProcessLink = false;

    protected bool $shouldProcessIframe = false;

    protected bool $shouldProcessPicture = false;

    protected bool $shouldProcessAudioAndVideo = false;

    /**
     * @return bool
     */
    public function isShouldProcessScript(): bool
    {
        return $this->shouldProcessScript;
    }

    /**
     * @param bool $shouldProcessScript
     */
    public function setShouldProcessScript(bool $shouldProcessScript): void
    {
        $this->shouldProcessScript = $shouldProcessScript;
    }

    /**
     * @return bool
     */
    public function isShouldProcessImg(): bool
    {
        return $this->shouldProcessImg;
    }

    /**
     * @param bool $shouldProcessImg
     */
    public function setShouldProcessImg(bool $shouldProcessImg): void
    {
        $this->shouldProcessImg = $shouldProcessImg;
    }

    /**
     * @return bool
     */
    public function isShouldProcessLink(): bool
    {
        return $this->shouldProcessLink;
    }

    /**
     * @param bool $shouldProcessLink
     */
    public function setShouldProcessLink(bool $shouldProcessLink): void
    {
        $this->shouldProcessLink = $shouldProcessLink;
    }

    /**
     * @return bool
     */
    public function isShouldProcessIframe(): bool
    {
        return $this->shouldProcessIframe;
    }

    /**
     * @param bool $shouldProcessIframe
     */
    public function setShouldProcessIframe(bool $shouldProcessIframe): void
    {
        $this->shouldProcessIframe = $shouldProcessIframe;
    }

    /**
     * @return bool
     */
    public function isShouldProcessPicture(): bool
    {
        return $this->shouldProcessPicture;
    }

    /**
     * @param bool $shouldProcessPicture
     */
    public function setShouldProcessPicture(bool $shouldProcessPicture): void
    {
        $this->shouldProcessPicture = $shouldProcessPicture;
    }

    /**
     * @return bool
     */
    public function isShouldProcessAudioAndVideo(): bool
    {
        return $this->shouldProcessAudioAndVideo;
    }

    /**
     * @param bool $shouldProcessAudioAndVideo
     */
    public function setShouldProcessAudioAndVideo(bool $shouldProcessAudioAndVideo): void
    {
        $this->shouldProcessAudioAndVideo = $shouldProcessAudioAndVideo;
    }
}