<?php

include_once(__DIR__ . '/../functions/format.php');

$options = getopt('', ['xml:']);

if ($options) {
    print format_xml($options['xml']);
}
