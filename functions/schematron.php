<?php

include_once(__DIR__ . '/doctype.php');

function validate_schematron($inputFile, $schematron) {
    set_time_limit(300);

    validateDoctypeIsSupported($inputFile);

    $schematronPath = __DIR__ . '/../html/' . $schematron . '.xsl'; // TODO: sanitise?

    $saxonProcessor = new Saxon\SaxonProcessor();

    $catalog = getenv('XML_CATALOG_FILES');
//    $saxonProcessor->setConfigurationProperty('http://saxon.sf.net/feature/timing', true);
    $saxonProcessor->setCatalog($catalog, true);

    $processor = $saxonProcessor->newXslt30Processor();
//    $processor->setParameter('phase', $saxonProcessor->createAtomicValue('warning'));
    $result = $processor->transformFileToString($inputFile, $schematronPath);

    if ($result) {
//        header('Content-Type: application/xml');
//        print $result;

        $errors = [];
        $warnings = [];
//        $recoverableErrors = [];

        $inputDoc = new DOMDocument();
        $inputDoc->load($inputFile, LIBXML_NONET | LIBXML_NOENT);
        $inputXPath = new DOMXPath($inputDoc);

        $resultDoc = new DOMDocument();
        $resultDoc->loadXML($result, LIBXML_NONET | LIBXML_NOENT);
        $resultXPath = new DOMXPath($resultDoc);
        $asserts = $resultXPath->query('svrl:failed-assert');
        $reports = $resultXPath->query('svrl:successful-report');

        if ($asserts) {
            /** @var DOMElement $assert */
            foreach ($asserts as $assert) {
                $inputNodes = $inputXPath->query($assert->getAttribute('location'));
                /** @var DOMElement $inputNode */
                $inputNode = $inputNodes[0];

                $data = [
                    'line' => $inputNode->getLineNo(),
                    'path' => $assert->getAttribute('location'),
                    'test' => $assert->getAttribute('test'),
                    'type' => $assert->getAttribute('role'),
                    'message' => trim($assert->textContent),
                ];

                switch ($data['type']) {
                    case 'error':
                        $errors[] = $data;
                        break;

                    case 'warning':
                    default:
                        $warnings[] = $data;
                        break;
                }
            }
        }

        if ($reports) {
            /** @var DOMElement $report */
            foreach ($reports as $report) {
                $inputNodes = $inputXPath->query($report->getAttribute('location'));
                /** @var DOMElement $inputNode */
                $inputNode = $inputNodes[0];

                $data = [
                    'line' => $inputNode->getLineNo(),
                    'path' => $report->getAttribute('location'),
                    'test' => $report->getAttribute('test'),
                    'type' => $report->getAttribute('role'),
                    'message' => trim($report->textContent),
                ];

                switch ($data['type']) {
                    case 'error':
                        $errors[] = $data;
                        break;

                    case 'warning':
                    default:
                        $warnings[] = $data;
                        break;
                }
            }
        }

//        $processor->clearParameters();
//        $executable->clearProperties();

        return [
            'results' => [
                'errors' => $errors,
                'warnings' => $warnings,
            ]
        ];
    } else {
        $errors = [];
        $errorCount = $processor->getExceptionCount();

        for ($i = 0; $i < $errorCount; $i++) {
            $errors[] = [
                'code' => $processor->getErrorCode($i),
                'message' => $processor->getErrorMessage($i),
            ];
        }

//        if($executable->exceptionOccurred()) {
//            $errors[] = [
//                'code' => $executable->getErrorCode(),
//                'message' => $executable->getErrorMessage(),
//            ];
//            $processor->exceptionClear();
//        }

        return [
            'errors' => $errors
        ];
    }
}
