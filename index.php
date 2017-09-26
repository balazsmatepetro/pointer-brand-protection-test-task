<?php
require 'vendor/autoload.php';

use League\Uri\Schemes\Http as HttpUri;
use SearchEngineAggregator\EngineAggregator;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\GeneratedStaticResultEngine;
use SearchEngineAggregator\Engine\GoogleEngine;
use Serps\Core\Browser\Browser;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;

$browser = new Browser(
    new CurlClient,
    'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36',
    'en-GB'
);

$google = EngineIdentifier::fromString('google');
$yahoo = EngineIdentifier::fromString('yahoo');

$engine1 = new GeneratedStaticResultEngine($google, 'Mr Robot Article');
$engine2 = new GeneratedStaticResultEngine($google, 'Mr Robot Video');
$engine3 = new GeneratedStaticResultEngine($yahoo, 'Mr Robot Article');
$googleEngine = new GoogleEngine(new GoogleClient($browser), new GoogleUrl);

$engineAggregator = new EngineAggregator([
    $engine1,
    $googleEngine,
    $engine2,
    $engine3
]);

$results = $engineAggregator->search('mr robot');

foreach ($results as $item) {
    echo $item->getTitle(), PHP_EOL;
}

exit;
