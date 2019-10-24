<?php

function validateDoctypeIsSupported ($filename) {
    $doc = new \DOMDocument;

    // load the document without resolving entities
//    libxml_disable_entity_loader(true);
    $doc->load($filename, LIBXML_NONET | LIBXML_NOENT);
//    libxml_disable_entity_loader(false);

    // check the doctype against a whitelist
    $doctype = $doc->doctype->publicId;

    $version = getenv('DTDS_VERSION');
    $doctypes = json_decode(file_get_contents("/dtds/jats-dtds-{$version}/schema/doctypes.json"), true);

    if (!$doctypes[$doctype]) {
        throw new Error('DOCTYPE not supported: ' . $doctype);
    }
}
