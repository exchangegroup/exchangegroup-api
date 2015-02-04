<?php
namespace ExchangeGroup;

class ClientException extends Exception
{
    // Original array of errors
    public $errors;

    public function __construct($errors)
    {
        $this->errors = (array) $errors;

        // Construct informative error message
        $errorMsg = 'Errors in API request - ';
        foreach($errors as $name, $msg) {
            $errorMsg .= $name .': ' . implode(',', $msg) .'; ';
        }
        parent::__construct($errorMsg);
    }
}
