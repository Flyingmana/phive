<?php
namespace PharIo\Phive;

class Curl implements HttpClient {

    /**
     * @var CurlConfig
     */
    private $config;

    /**
     * @param CurlConfig $curlConfig
     */
    public function __construct(CurlConfig $curlConfig) {
        $this->config = $curlConfig;
    }

    /**
     * @todo add optional progresshandler
     */

    /**
     * @param Url   $url
     * @param array $params
     *
     * @return HttpResponse
     */
    public function get(Url $url, array $params = []) {
        $ch = curl_init($url . '?' . http_build_query($params));
        curl_setopt_array($ch, $this->config->asCurlOptArray());
        $hostname = parse_url((string)$url, PHP_URL_HOST);
        if ($this->config->hasLocalSslCertificate($hostname)) {
            curl_setopt($ch, CURLOPT_CAINFO, $this->config->getLocalSslCertificate($hostname));
        }
        return new HttpResponse(curl_exec($ch), curl_getinfo($ch, CURLINFO_HTTP_CODE), curl_error($ch));
    }

}
