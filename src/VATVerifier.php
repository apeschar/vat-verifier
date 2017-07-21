<?php

namespace KiboIT\VATVerification;

interface VATVerifier {

    /**
     * @param VATNumber $vat
     * @return VerificationResult
     */
    public function validateVAT(VATNumber $vat);

}
