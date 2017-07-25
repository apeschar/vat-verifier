<?php

namespace KiboIT\VATVerification;

class VATNumber {

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $number;

    /**
     * @param $string
     * @return static
     */
    public static function fromString($string) {
        return new static(substr($string, 0, 2), substr($string, 2));
    }

    /**
     * @param $string
     * @return static
     */
    public static function sanitizeAndBuildFromString($string) {
        return static::fromString(preg_replace('/[^0-9A-Za-z]/', '', $string));
    }

    /**
     * VATNumebr constructor.
     *
     * @param $countryCode string
     * @param $number string
     * @throws \InvalidArgumentException
     */
    public function __construct($countryCode, $number) {
        if (!preg_match('/^[A-Z]{2}$/', $countryCode)) {
            throw new \InvalidArgumentException('Bad arguments for country code: ' . $countryCode);
        }
        if (!preg_match('/^[0-9A-Za-z]{2,12}$/', $number)) {
            throw new \InvalidArgumentException('Bad arguments for number: ' . $number);
        }
        $this->countryCode = $countryCode;
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getNumber() {
        return $this->number;
    }

    public function toString() {
        return $this->countryCode . $this->number;
    }

    public function __toString() {
        return $this->toString();
    }

}
