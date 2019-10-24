<?php

include_once(__DIR__ . '/../functions/schematron.php');

$options = getopt('', ['xml:', 'schematron:']);

if ($options) {
    $output = validate_schematron($options['xml'], $options['schematron']);

    // TODO: convert to GitHub Checks annotations
    print json_encode($output, JSON_PRETTY_PRINT);
}
