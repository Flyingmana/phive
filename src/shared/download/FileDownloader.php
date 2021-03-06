<?php
namespace PharIo\Phive;

class FileDownloader {

    /**
     * @var HttpClient
     */
    private $curl;

    /**
     * @var Cli\Output
     */
    private $output;

    /**
     * @param HttpClient $curl
     * @param Cli\Output $output
     */
    public function __construct(HttpClient $curl, Cli\Output $output) {
        $this->curl = $curl;
        $this->output = $output;
    }

    /**
     * @param Url $url
     *
     * @return File
     * @throws DownloadFailedException
     */
    public function download(Url $url) {
        $this->output->writeInfo(sprintf('Downloading %s', $url));
        $response = $this->curl->get($url);
        if ($response->getHttpCode() !== 200) {
            throw new DownloadFailedException(
                sprintf(
                    'Download failed (HTTP status code %s) %s',
                    $response->getHttpCode(),
                    $response->getErrorMessage()
                )
            );
        }
        if (empty($response->getBody())) {
            throw new DownloadFailedException('Download failed - response is empty');
        }
        return new File($this->getFilename($url), $response->getBody());
    }

    /**
     * @param Url $url
     *
     * @return Filename
     */
    private function getFilename(Url $url) {
        return new Filename(pathinfo($url, PATHINFO_BASENAME));
    }

}
