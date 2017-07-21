<?php

namespace VATVerification;

use KiboIT\SimpleHTTP\SimpleHTTPClient;
use KiboIT\VATVerification\VATNumber;
use KiboIT\VATVerification\VATVerificationTestCase;
use KiboIT\VATVerification\VIESClient;

class VIESClientIntegrationTest extends VATVerificationTestCase {

    /**
     * @var VIESClient
     */
    private $client;

    public function setUp() {
        $http = new SimpleHTTPClient();
        $this->client = new VIESClient($http, true);
    }

    public function testValidResponse() {
        $result = $this->client->validateVAT(new VATNumber('NL', '100'));
        $this->checkValidResult($result);
        $this->assertEquals(date('Y-m-d'), $result->getRequestDate()->format('Y-m-d'));
    }

    public function testSOAPFault() {
        $this->expectSOAPFault();
        $this->client->validateVAT(new VATNumber('NL', '201'));
    }

}
