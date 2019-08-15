<?php

include_once(__DIR__ . '/../lib/doctype.php');

$inputFile = $_FILES['xml']['tmp_name'];
$schematronFile = $_POST['schematron'];

if ($inputFile && $schematronFile) {
    validateDoctypeIsSupported($inputFile);

    $schematronPath = __DIR__ . '/../' . $schematronFile . '.xsl'; // TODO: sanitise?

    $saxonProcessor = new Saxon\SaxonProcessor();

    $catalog = getenv('XML_CATALOG_FILES');
    $saxonProcessor->setCatalog($catalog, true);

    $processor = $saxonProcessor->newXsltProcessor();
    $processor->setSourceFromFile($inputFile);
    $processor->compileFromFile($schematronPath);
    $result = $processor->transformToString();

    if ($result) {
//        header('Content-Type: application/xml');
//        print $result;
//
        $inputDoc = new DOMDocument();
        $inputDoc->loadXML($inputFile, LIBXML_NONET | LIBXML_NOENT);

        $resultDoc = new DOMDocument();
        $resultDoc->loadXML($result, LIBXML_NONET | LIBXML_NOENT);
        $resultXPath = new DOMXPath($resultDoc);
        $asserts = $resultXPath->query('svrl:failed-assert');
        $reports = $resultXPath->query('svrl:successful-report');

        $errors = [];
        $warnings = [];

        if ($asserts) {
            /** @var DOMElement $assert */
            foreach ($asserts as $assert) {
                $errors[] = [
                    'line' => $assert->getLineNo(),
                    'path' => $assert->getAttribute('location'),
                    'test' => $assert->getAttribute('test'),
                    'message' => trim($assert->textContent),
                ];
            }
        }

        if ($reports) {
            /** @var DOMElement $report */
            foreach ($reports as $report) {
                $errors[] = [
                    'line' => $report->getLineNo(),
                    'path' => $report->getAttribute('location'),
                    'test' => $report->getAttribute('test'),
                    'message' => trim($report->textContent),
                ];
            }
        }

        $output = [
            'results' => [
                'errors' => $errors,
                'warnings' => $warnings,
            ]
        ];

        header('Content-Type: application/json');
        print json_encode($output, JSON_PRETTY_PRINT);
    } else {
        $errors = [];
        $errorCount = $processor->getExceptionCount();

        for ($i = 0; $i < $errorCount; $i++) {
            $errors[] = [
                'code' => $processor->getErrorCode($i),
                'message' => $processor->getErrorMessage($i),
            ];
        }

        header('Content-Type: application/json');
        print json_encode($errors, JSON_PRETTY_PRINT);
    }
}
