<?php

namespace KiboIT\VATVerification;

class VIESClientTest extends VATVerificationTestCase {

    private $validVAT = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><checkVatResponse xmlns="urn:ec.europa.eu:taxud:vies:services:checkVat:types"><countryCode>NL</countryCode><vatNumber>100</vatNumber><requestDate>2017-03-20+01:00</requestDate><valid>true</valid><name>John Doe</name><address>123 Main St, Anytown, UK</address></checkVatResponse></soap:Body></soap:Envelope>';

    private $invalidVAT = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><checkVatResponse xmlns="urn:ec.europa.eu:taxud:vies:services:checkVat:types"><countryCode>NL</countryCode><vatNumber>200</vatNumber><requestDate>2017-03-20+01:00</requestDate><valid>false</valid><name>---</name><address>---</address></checkVatResponse></soap:Body></soap:Envelope>';

    private $faultResonse = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><soap:Fault><faultcode>soap:Server</faultcode><faultstring>INVALID_INPUT</faultstring></soap:Fault></soap:Body></soap:Envelope>';

    private $vatNumber;

    public function setUp() {
        $this->vatNumber = new VATNumber('NL', '100');
    }

    public function testSuccessfulRequest() {
        $client = $this->getClientWithMock(200, $this->validVAT);
        $result = $client->validateVAT($this->vatNumber);
        $this->checkValidResult($result);
        $this->assertEquals('2017-03-20', $result->getRequestDate()->format('Y-m-d'));
    }

    public function testInvalidVat() {
        $client = $this->getClientWithMock(200, $this->invalidVAT);
        $result = $client->validateVAT($this->vatNumber);
        $this->assertFalse($result->isValid());
    }

    public function testExceptionOnSOAPFault() {
        $this->expectSOAPFault();
        $client = $this->getClientWithMock(200, $this->faultResonse);
        $client->validateVAT($this->vatNumber);
    }

    public function testExceptionOnBadXML() {
        $this->expectException(Exceptions\BadXML::class);
        $this->expectExceptionMessage('asdasd');
        $client = $this->getClientWithMock(200, 'asdasd');
        $client->validateVAT($this->vatNumber);
    }

    public function testExceptionOnBadHTTPCode() {
        $this->expectException(Exceptions\HTTPError::class);
        $this->expectExceptionMessage('asdasd');
        $this->expectExceptionCode(500);
        $client = $this->getClientWithMock(500, 'asdasd');
        $client->validateVAT($this->vatNumber);
    }

}
