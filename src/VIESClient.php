<?php

namespace KiboIT\VATVerification;

use KiboIT\SimpleHTTP\SimpleHTTPClient;
use KiboIT\VATVerification\Exceptions\BadXML;
use KiboIT\VATVerification\Exceptions\HTTPError;
use KiboIT\VATVerification\Exceptions\SOAPFault;

class VIESClient implements VATVerifier {

    private static $serviceURL = 'http://ec.europa.eu/taxation_customs/vies/services/checkVatService';

    private static $testServiceURL = 'http://ec.europa.eu/taxation_customs/vies/services/checkVatTestService';

    private static $verificationTemplate = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:ec.europa.eu:taxud:vies:services:checkVat:types">
    <SOAP-ENV:Body>
        <ns1:checkVat>
            <ns1:countryCode>%s</ns1:countryCode>
            <ns1:vatNumber>%s</ns1:vatNumber>
        </ns1:checkVat>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOT;

    /**
     * @var SimpleHTTPClient
     */
    private $client;

    /**
     * @var string
     */
    private $url;


    public function __construct(SimpleHTTPClient $client, $testMode = false) {
        $this->client = $client;
        $this->url = $testMode ? self::$testServiceURL : self::$serviceURL;
    }

    /**
     * @param VATNumber $vat
     * @return VerificationResult
     * @throws BadXML
     * @throws SOAPFault
     * @throws HTTPError
     */
    public function validateVAT(VATNumber $vat) {
        $requestBody = sprintf(self::$verificationTemplate, $vat->getCountryCode(), $vat->getNumber());
        $response = $this->client->post($this->url, $requestBody);
        if ($response['code'] != 200) {
            throw new HTTPError($response['body'], $response['code']);
        }
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response['body']);
        if ($xml === false) {
            throw new BadXML($response['body']);
        }
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $fault = $xml->xpath('soap:Body/soap:Fault');
        if (count($fault) > 0) {
            $template = 'FAULT CODE: %s | FAULT STRING: %s';
            $msg = sprintf($template, (string)$fault[0]->faultcode, (string)$fault[0]->faultstring[0]);
            throw new SOAPFault($msg);
        }
        $body = $xml->xpath('soap:Body')[0];
        return VerificationResult::fromObject($body->checkVatResponse);
    }

}
