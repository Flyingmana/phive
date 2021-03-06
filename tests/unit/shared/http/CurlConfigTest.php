<?php
namespace PharIo\Phive;

    /**
     * @covers PharIo\Phive\CurlConfig
     */
    class CurlConfigTest extends \PHPUnit_Framework_TestCase {

        public function testPutsUserAgentInCurlOptArray() {
            $config = new CurlConfig('Some Agent');
            $actual = $config->asCurlOptArray();
            $this->assertArrayHasKey(CURLOPT_USERAGENT, $actual);
            $this->assertSame('Some Agent', $actual[CURLOPT_USERAGENT]);
        }

        public function testSetsExpectedDefaultsInCurlOptArray() {
            $config = new CurlConfig('foo');
            $expectedDefaults = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT        => 60
            ];
            $actual = $config->asCurlOptArray();
            $this->assertArraySubset($expectedDefaults, $actual);
        }

        public function testPutsProxyWithoutCredentialsInCurlOptArray() {
            $config = new CurlConfig('foo');
            $config->setProxy('proxy.example.com');
            $expected = [
                CURLOPT_PROXY => 'proxy.example.com'
            ];
            $actual = $config->asCurlOptArray();
            $this->assertArraySubset($expected, $actual);
        }

        public function testPutsProxyWithCredentialsInCurlOptArray() {
            $config = new CurlConfig('foo');
            $config->setProxy('proxy.example.com', 'someuser', 'somepassword');
            $expected = [
                CURLOPT_PROXY        => 'proxy.example.com',
                CURLOPT_PROXYUSERPWD => 'someuser:somepassword'
            ];
            $actual = $config->asCurlOptArray();
            $this->assertArraySubset($expected, $actual);
        }

        public function testAddsLocalSslCertificate() {
            $config = new CurlConfig('foo');
            $url = 'example.com';
            $this->assertFalse($config->hasLocalSslCertificate($url));
            $config->addLocalSslCertificate($url, 'cert.pem');
            $this->assertTrue($config->hasLocalSslCertificate($url));
            $this->assertSame('cert.pem', $config->getLocalSslCertificate($url));
        }

        /**
         * @expectedException \PharIo\Phive\CurlException
         */
        public function testGetLocalSslCertificateThrowsExceptionIfCertificateDoesNotExist() {
            $config = new CurlConfig('foo');
            $config->getLocalSslCertificate('example.com');
        }

    }


