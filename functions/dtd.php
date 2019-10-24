<?php

include_once(__DIR__ . '/doctype.php');

function validate_dtd($inputFile) {
    libxml_use_internal_errors(true);

    validateDoctypeIsSupported($inputFile);

    $doc = new \DOMDocument;

    // validate against the DTD
    $doc->load($inputFile, LIBXML_DTDLOAD | LIBXML_DTDVALID | LIBXML_NONET);

    return [
        'errors' => libxml_get_errors()
    ];
}
