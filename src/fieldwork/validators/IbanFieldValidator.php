<?php

namespace fieldwork\validators;

class IbanFieldValidator extends RegexFieldValidator
{

    const PATT      = "/^[A-Z]{2}[0-9]{2} [A-Z0-9]{4} [0-9]{4} [0-9]{4}( [0-9]{4})?( [0-9]{2})?$/";
    const PATT_BBAN = "/^([A-Z]{2}[0-9]{2} [A-Z0-9]{4} [0-9]{4} [0-9]{4}( [0-9]{4})?( [0-9]{2})?|[0-9]{1,10})$/";
    const ERROR     = "Not a valid IBAN";

    public function __construct ($convertBban = false, $errorMsg = self::ERROR)
    {
        parent::__construct(
            self::PATT, $errorMsg, $convertBban ? self::PATT_BBAN : self::PATT
        );
    }

    public function isValid ($value)
    {
        $sanitized = preg_replace('/\s/', '', $value);
        return verify_iban($sanitized, true);
    }
}
