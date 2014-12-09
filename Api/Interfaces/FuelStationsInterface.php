<?php
namespace Api\Interfaces;

use Guzzle\Http\ClientInterface;

/**
 * Interface FuelStationsInterface
 */
interface FuelStationsInterface
{

    /**
     * Sets the client for the api to use
     *
     * @param ClientInterface $clientInterface
     * @return mixed
     */
    public function setClient(ClientInterface $clientInterface);

    /**
     * gets the client the api is using
     *
     * @return ClientInterface
     */
    public function getClient();

    /**
     * sets the current api key to use
     *
     * @param string $apiKey
     * @return mixed
     */
    public function setApiKey($apiKey);

    /**
     * getApiKey
     *
     * @return mixed
     */
    public function getApiKey();

    /**
     * setApiUrl
     *
     * sets the url for the api to use
     *
     * @param string $url
     * @return mixed
     */
    public function setApiUrl($url);

    /**
     * getApiUrl
     * @return string|null url for the api to use
     */
    public function getApiUrl();

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
    public function findPhoneNumbersByLocation($location, $fuelType = 'all');

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
    public function countByLocation($location, $fuelType = 'all');

} 