<?php
namespace Api;

include "Interfaces/FuelStationsInterface.php";

use Guzzle\Http\ClientInterface;



/**
 * Class FuelStations
 *
 * @todo Please add your functionality here to read from the fuel station search api
 */
class FuelStations implements Interfaces\FuelStationsInterface
{
    private $httpClient;    // The Guzzle Http Client           --default: null
    private $apiKey;          //  The API Developer key        --default: null
    private $apiUrl;            // The Base API URL                    --default: null

    /**
     * Sets the client for the api to use
     *
     * @param ClientInterface $clientInterface
     * @return mixed
     */
    public function setClient(ClientInterface $clientInterface)
    {
        $this->httpClient = $clientInterface;
    }

    /**
     * gets the client the api is using
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->httpClient;
    }

    /**
     * sets the current api key to use
     *
     * @param string $apiKey
     * @return mixed
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * getApiKey
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * setApiUrl
     *
     * sets the url for the api to use
     *
     * @param string $url
     * @return mixed
     */
    public function setApiUrl($url)
    {
        $this->apiUrl = $url;
    }

    /**
     * getApiUrl
     * @return string|null url for the api to use
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

      /**
     * finds all of the phone numbers of fuel stations by a search condition
     *
     * @param string|array $location A free-form input describing the address of the location. This may include
     * the address given in a variety of formats, such as:
     *
     *  street, city, state, postal code
     *  street, city, state
     *  street, postal code
     *  postal code
     *  city, state
     *
     * @param string $fuelType optional type of fuel to search for. options: all, BD, CNG, E85, ELEC, HY, LNG, LPG
     *
     * @return array of phone numbers matching the fuel stations within the given location
     */
    public function findPhoneNumbersByLocation($location, $fuelType = 'all')
    {
        $phones = array();
        if(is_array($location)) {
            print_r($location);
            foreach($location as $locStr) {
                echo "\nLooping on location\n";
                $sPhone = $this->findPhoneNumbersByLocation($locStr, $fuelType);
                $phones = array_merge($phones, $sPhone);
            }
        } else {
            //CREATE  REQUEST URL
            $reqUrl = $this->apiUrl . '?api_key=' . $this->apiKey . '&location=' . $location . '&fuel_type=' . $fuelType;

            //MAKE THE REQUEST AND GET RESPONSE
            $request = $this->getClient()->createRequest('GET', $reqUrl);
            $response = $this->httpClient->send($request);

            //PARSE JSON
            $resJson = $response->json();

            //EXTRACT PHONE NUMBERS
            foreach ($resJson['fuel_stations'] as $station) {
                if (!is_null($station['station_phone'])) {
                    array_push($phones, $station['station_phone']);
                }
            }
        }
        return $phones;
    }


    /**
     * finds out how many fuel stations are within the provided locattions
     *
     * @param string|array $location A free-form input describing the address of the location. This may include
     * the address given in a variety of formats, such as:
     *
     *  street, city, state, postal code
     *  street, city, state
     *  street, postal code
     *  postal code
     *  city, state
     *
     * @param string $fuelType optional type of fuel to search for. options: all, BD, CNG, E85, ELEC, HY, LNG, LPG
     *
     * @return int count of the fuelstations within the given location
     */
    public function countByLocation($location, $fuelType = 'all')
    {
        $statCnt = 0;
        if(is_array($location)) {
            foreach($location as $locStr) {
                $subCnt = $this->countByLocation($locStr, $fuelType);
                $statCnt += $subCnt;
            }
        } else {
            $reqUrl = $this->apiUrl . '?api_key=' . $this->apiKey . '&location=' . $location . '&fuel_type=' . $fuelType;
            $request = $this->getClient()->createRequest('GET', $reqUrl);
            $response = $this->httpClient->send($request);
            $resJson = $response->json();
            $statCnt = 0;
            if (is_array($resJson['fuel_stations'])) {
                $statCnt = count($resJson['fuel_stations']);
            }
        }
        return $statCnt;
    }
}
/*
$test = new FuelStations();
$test->setClient(new \Guzzle\Http\Client());
$test->setApiKey($TRENT_API_KEY);
//$test->setApiKey("BAD_KEY");
$test->setApiUrl('https://developer.nrel.gov/api/alt-fuel-stations/v1/nearest.json');
$phones = $test->findPhoneNumbersByLocation('89132');
print_r($phones);
*/