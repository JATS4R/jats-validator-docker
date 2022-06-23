<?php

$saxonProcessor = new Saxon\SaxonProcessor();
print "{$saxonProcessor->version()}\n";

/**
 * @param $processor \Saxon\Xslt30Processor
 */
//function showErrors($processor) {
//    $exceptionCount = $processor->getExceptionCount();
//
//    if ($exceptionCount > 0) {
//        for ($i = 0; $i < $exceptionCount; $i++) {
//            $error = $processor->getErrorMessage($i);
//            print_r($error);
//        }
//
//        exit(1);
//    }
//}

print "Converting with 'skeleton/iso_dsdl_include.xsl'\n";
$processor = $saxonProcessor->newXslt30Processor();
//$processor->setSourceFromFile($argv[1]);
//$processor->compileFromFile('skeleton/iso_dsdl_include.xsl');
//$processor->setOutputFile('tmp.xsl');
//$processor->transformToFile();
$processor->transformFileToFile($argv[1], 'skeleton/iso_dsdl_include.xsl', 'iso.xsl');
//showErrors($processor);

print "Converting with 'skeleton/iso_abstract_expand.xsl'\n";
$processor = $saxonProcessor->newXslt30Processor();
//$processor->setSourceFromFile('tmp.xsl');
//$processor->compileFromFile('skeleton/iso_abstract_expand.xsl');
//$processor->setOutputFile('tmp-expanded.xsl');
//$processor->transformToFile();
$processor->transformFileToFile('iso.xsl', 'skeleton/iso_abstract_expand.xsl', 'iso-expanded.xsl');
//showErrors($processor);

print "Converting with 'skeleton/iso_svrl_for_xslt2.xsl'\n";
$processor = $saxonProcessor->newXslt30Processor();
//$processor->setSourceFromFile('tmp-expanded.xsl');
//$processor->compileFromFile('skeleton/iso_svrl_for_xslt2.xsl');
//$processor->setOutputFile($argv[2]);
//$processor->transformToFile();
$processor->transformFileToFile('iso-expanded.xsl', 'skeleton/iso_svrl_for_xslt2.xsl', $argv[2]);
//showErrors($processor);
