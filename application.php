<?php
include 'vendor/autoload.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('bruggen.pdf');
$lines = explode("\n", $pdf->getText());

$currentLocation = '';
$currentSubLocation = '';
$currentDateRange = '';
$currentDay = '';
$matches = [];

$months = [
    "januari",
    "februari",
    "maart",
    "april",
    "mei",
    "juni",
    "juli",
    "augustus",
    "september",
    "oktober",    
    "november",
    "december",
];

$days = [
    'mo',
    'di',
    'wo',
    'do',
    'vr',
    'za',
    'zo',
    'fstd',
    'dagelijks'
];

foreach($lines as $line) 
{
    // get rid of empty lines
    if(!preg_replace('/\s+/', '', $line)) {
        continue;
    }

    if($line[0] !== ' ' && preg_match('/\([0-9]+[a-z]?\)/', $line)) {
        // line contains location
        $currentLocation = $line;
    } elseif($line[0] == ' ' && preg_match('/\([0-9]+\.[0-9]*\)/', $line)) {
        // line contains sublocation
        $currentSubLocation = $line;
    } elseif(match($months, $line)) {
        // line contains date range
        $currentDateRange = $line;
    } elseif(match($days, $line)) {
        // line contains day(s)
        $currentDay = $line;
    } elseif(preg_match('/[0-9]{2}:[0-9]{2}-[0-9]{2}:[0-9]{2}/', $line) || strpos($line, 'Geen bediening')) {
        // line contains time range
        $time = $line;
        print_r(compact('currentLocation', 'currentSubLocation', 'currentDateRange', 'currentDay', 'time'));
    } else {
        // line contains something else
        // print_r($line . PHP_EOL);
    }

}

function match($needles, $haystack)
{
    foreach($needles as $needle){
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
    }
    return false;
}


