<?php

namespace KiboIT\VATVerification;

class VerificationResult {

    /**
     * @var VATNumber
     */
    private $vat;

    /**
     * @var bool
     */
    private $valid;

    /**
     * @var \DateTime
     */
    private $requestDate;

    /**
     * @var string
     */
    private $name;

    /**
     * @var address
     */
    private $address;

    /**
     * @param $obj
     * @return self
     */
    public static function fromObject($obj) {
        $result = new self();
        $result->vat = new VATNumber((string)$obj->countryCode, (string)$obj->vatNumber);
        $result->valid = (string)$obj->valid === 'true';
        $result->requestDate = new \DateTime((string)$obj->requestDate);
        $result->name = (string)$obj->name;
        $result->address = (string)$obj->address;
        return $result;
    }

    /**
     * @return VATNumber
     */
    public function getVAT() {
        return $this->vat;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return $this->valid;
    }

    /**
     * @return \DateTime
     */
    public function getRequestDate() {
        return $this->requestDate;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

}
