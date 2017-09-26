<?php
namespace SearchEngineAggregator\Engine;

use League\Uri\Schemes\Http as HttpUri;
use SearchEngineAggregator\Result\Result;
use SearchEngineAggregator\Result\ResultList;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;
use SplObjectStorage;

/**
 * Description of GoogleEngine
 * 
 * @package SearchEngineAggregator\Engine
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class GoogleEngine implements Engine
{
    /**
     * The GoogleClient instance.
     *
     * @var GoogleClient
     */
    private $client;

    /**
     * Creates a new GoogleEngine instance.
     *
     * @param GoogleClient $client The GoogleClient instance.
     * @param GoogleUrl $url The GoogleUrl instance.
     */
    public function __construct(GoogleClient $client, GoogleUrl $url)
    {
        $this->client = $client;
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function search($keyword)
    {
        // Setting search term.
        $this->url->setSearchTerm($keyword);
        // Querying results.
        $response = $this->client->query($this->url)->getNaturalResults();
        // Creating result list.
        $results = new ResultList(new SplObjectStorage);
        // Creating engine identifier list from the engine identifier.
        $engineIdentifierList = EngineIdentifierList::fromIdentifier($this->getIdentifier());
        // Looping through results.
        foreach ($response as $item) {
            // Retrieving result data.
            $data = $item->getData();
            // Instantiating result item and pushing to the list.
            $results->add(new Result(
                $data['title'],
                HttpUri::createFromString($data['url']),
                $engineIdentifierList
            ));
        }
        // Returning result list.
        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return EngineIdentifier::fromString('google');
    }
}
