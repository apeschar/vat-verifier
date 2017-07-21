<?php

namespace KiboIT\VATVerification;

class VATNumebrTest extends \PHPUnit_Framework_TestCase {

    public function testBuildFromConstructor() {
        $code = 'NL';
        $number = '803851595B01';
        $vat = new VATNumber($code, $number);
        $this->assertEquals($code, $vat->getCountryCode());
        $this->assertEquals($number, $vat->getNumber());
    }

    public function testBuildFromString() {
        $code = 'NL803851595B01';
        $vat = VATNumber::fromString($code);
        $this->assertEquals('NL', $vat->getCountryCode());
        $this->assertEquals('803851595B01', $vat->getNumber());
    }

    public function testExceptionOnBadCountryCode() {
        $this->expectException(\InvalidArgumentException::class);
        new VATNumber('N', '123');
    }

    public function testExceptionOnBadNumber() {
        $this->expectException(\InvalidArgumentException::class);
        new VATNumber('NL', '1');
    }

    public function testSanitizeAndBuildFromString() {
        $vat = VATNumber::sanitizeAndBuildFromString('NL 8038 51595,B01');
        $this->assertEquals('NL', $vat->getCountryCode());
        $this->assertEquals('803851595B01', $vat->getNumber());
    }

    public function testToString() {
        $vat = VATNumber::fromString('NL803851595B01');
        $this->assertEquals('NL803851595B01', $vat->toString());
        $this->assertEquals('NL803851595B01', (string)$vat);
    }

}
