<?php

namespace KiboIT\VATVerification;

use KiboIT\SimpleHTTP\SimpleHTTPClient;

abstract class VATVerificationTestCase extends \PHPUnit_Framework_TestCase {

    protected function getClientWithMock($responseCode, $responseBody) {
        $httpMock = $this->createMock(SimpleHTTPClient::class);
        $httpMock->method('post')
                 ->willReturn(['code' => $responseCode, 'body' => $responseBody]);
        return new VIESClient($httpMock);
    }

    protected function createSourceForValidResult() {
        $obj = new \stdClass();
        $obj->countryCode = 'NL';
        $obj->vatNumber = '803851595B01';
        $obj->valid = 'false';
        $obj->requestDate = '2017-03-20+01:00';
        $obj->name = 'John Doe';
        $obj->address = '123 Main St, Anytown, UK';
        return $obj;
    }

    protected function checkValidResult($result) {
        $this->assertEquals('NL100', $result->getVAT()->toString());
        $this->assertTrue($result->isValid());
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals('123 Main St, Anytown, UK', $result->getAddress());
    }

    protected function expectSOAPFault() {
        $this->expectException(Exceptions\SOAPFault::class);
        $this->expectExceptionMessage('FAULT CODE: soap:Server | FAULT STRING: INVALID_INPUT');
    }
}
