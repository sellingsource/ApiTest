<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var \Api\Interfaces\FuelStationsInterface
     */
    protected $api;

    /**
     * @var Guzzle\Http\Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var string|array
     */
    protected $location = null;

    /**
     * @var null
     */
    protected $response = null;

    /**
     * @var string
     */
    protected $fuelType = 'all';

    /**
     * @var int
     */
    protected $phoneCount = 0;

    /**
     * @var null|int
     */
    protected $countResult = null;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->api = new \Api\FuelStations();
        if (!($this->api instanceof \Api\Interfaces\FuelStationsInterface)) {
            throw new RuntimeException("Invalid FuelStation Api provided");
        }
        $this->client = new Guzzle\Http\Client();
        $this->api->setClient($this->client);
    }

    /**
    * @Given I have an api endpoint :apiUrl
    */
    public function iHaveAnApiEndpoint($apiUrl)
    {
        $this->api->setApiUrl($apiUrl);
    }

    /**
     * @Given I have an api key :key
     */
    public function iHaveAnApiKey($key)
    {
        $this->api->setApiKey($key);
    }

    /**
     * @When I submit an api request
     */
    public function iSubmitAnApiRequest()
    {
        try {
            $this->response = $this->api->findPhoneNumbersByLocation('test');
        } catch (Exception $ex) {
            $this->errors[] = $ex;
        }
    }

    /**
     * @Then I should see an exception :message
     */
    public function iShouldSeeAnException($message)
    {
        if (count($this->errors) <= 0) {
            throw new Exception("An exception was expected but not found");
        }

        $exception = array_pop($this->errors);
        if (stripos($exception->getMessage(), $message) === false) {
            throw new Exception("An expected message was not found in the exception got:" . $exception->getMessage());
        }
    }

    /**
     * @Given I use location :location
     */
    public function iUseLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @When I request the phone numbers
     */
    public function iRequestThePhoneNumbers()
    {
        $this->response = $this->api->findPhoneNumbersByLocation($this->location, $this->fuelType);
        return $this->response;
    }

    /**
     * @Then I should get an array of phone numbers
     */
    public function iShouldGetAnArrayOfPhoneNumbers()
    {
        $pattern = "/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?.*$/";
        if (!is_array($this->response)) {
            throw new Exception("Response expected to be an array");
        }

        if (count($this->response) <= 0) {
            throw new Exception("count of phone numbers should be greater than 0");
        }

        foreach ($this->response as $phoneNumber) {
            if (!preg_match($pattern, $phoneNumber)) {
                    throw new Exception("Results do not seem to be phone numbers:" . $phoneNumber);
            }
        }

        $this->phoneCount = count($this->response);
    }

    /**
     * @When I count the stations
     */
    public function iCountTheStations()
    {
        $this->countResult = $this->api->countByLocation($this->location, $this->fuelType);
        return $this->countResult;
    }

    /**
     * @Then the number of phone numbers should equal the number of locations
     */
    public function theNumberOfPhoneNumbersShouldEqualTheNumberOfLocations()
    {
        if ($this->phoneCount !== $this->countResult) {
            throw new Exception('Number of phone records does not equal the number of records returned:' . $this->phoneCount . ' != ' . $this->countResult);
        }
    }

    /**
     * @Given I use locations:
     */
    public function iUseLocations(TableNode $table)
    {
        $this->location = array();
        foreach ($table as $row) {
            $this->location[] = $row;
        }
    }

    /**
     * @Given I have the response :
     */
    public function iHaveTheResponse(PyStringNode $string)
    {
        $expectedResponse = json_decode($string, true);
        $refl = new \ReflectionObject($this->api);
        $property = $refl->getProperty('response');
        $property->setAccessible(true);
        $property->setValue($this->api, $expectedResponse);
    }

    /**
     * @Then I should get :arg1 for a phone number
     */
    public function iShouldGetForAPhoneNumber($arg1)
    {
        $result = $this->iRequestThePhoneNumbers();
        if ($result != array($arg1)) {
            throw new Exception("expected phone number returned");
        }
    }

    /**
     * @Then I should have :arg1 as the number of stations
     */
    public function iShouldHaveAsTheNumberOfStations($arg1)
    {
        $countResult = $this->iCountTheStations();
        if ($countResult != intval($arg1)) {
            throw new Exception("expected number of stations not returned");
        }
    }
}
