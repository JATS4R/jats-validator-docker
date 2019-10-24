<?php

include_once(__DIR__ . '/../functions/dtd.php');

$options = getopt('', ['xml:']);

if ($options) {
    $output = validate_dtd($options['xml']);

    // TODO: convert to GitHub Checks annotations
    print json_encode($output, JSON_PRETTY_PRINT);
}
