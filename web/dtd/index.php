<?php

include_once(__DIR__ . '/../lib/doctype.php');

if ($_FILES['xml']) {
    libxml_use_internal_errors(true);

    validateDoctypeIsSupported($_FILES['xml']['tmp_name']);

    $doc = new \DOMDocument;

    // validate against the DTD
    $doc->load($_FILES['xml']['tmp_name'], LIBXML_DTDLOAD | LIBXML_DTDVALID | LIBXML_NONET);

    print json_encode(libxml_get_errors(), JSON_PRETTY_PRINT);
}
