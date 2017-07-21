<?php

namespace KiboIT\VATVerification;

class VerificationResultTest extends VATVerificationTestCase {

    public function testFromObject() {

        $obj = $this->createSourceForValidResult();
        $result = VerificationResult::fromObject($obj);

        $vat = $result->getVAT();
        $this->assertEquals('NL', $vat->getCountryCode());
        $this->assertEquals('803851595B01', $vat->getNumber());

        $this->assertFalse($result->isValid());

        $date = $result->getRequestDate();
        $this->assertEquals('2017', $date->format('Y'));
        $this->assertEquals('03', $date->format('m'));
        $this->assertEquals('20', $date->format('d'));
        $this->assertEquals($obj->name, $result->getName());
        $this->assertEquals($obj->address, $result->getAddress());
    }
}
