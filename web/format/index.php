<?php

include_once(__DIR__ . '/../lib/doctype.php');

if ($_FILES['xml']) {
    libxml_use_internal_errors(true);

    validateDoctypeIsSupported($_FILES['xml']['tmp_name']);

    $doc = new \DOMDocument;
    $doc->preserveWhiteSpace = false;
    $doc->load($_FILES['xml']['tmp_name'], LIBXML_DTDLOAD | LIBXML_NOENT | LIBXML_NONET | LIBXML_NOXMLDECL | LIBXML_NSCLEAN);
    $doc->formatOutput = true;
    $doc->encoding = 'UTF-8';

    print $doc->saveXML();
}
