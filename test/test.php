<?php
/**
 * @author: mix
 * @date: 20.01.15
 */
include __DIR__ . "/../vendor/autoload.php";

$parser = new \HTMLParser\Parser();
print_r($parser->parseFile(__DIR__ . "/test.html"));