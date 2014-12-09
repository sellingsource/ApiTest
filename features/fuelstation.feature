Feature:
  In order to find alternative fuel stations
  I need to be able to read from api.data.gov
  I need to be able to find phone numbers by location
  I need to be able to find the number of stations by location

  Background:
    Given I have an api endpoint "https://api.data.gov/nrel/alt-fuel-stations/v1/nearest.json"
    And I have an api key "Cdi0OYQVTBYz9ck0K6J7Fb3XfT8vmb9OS04exh5c"

  Scenario: A bad api key should result in an error
    Given I have an api key "BadApiKey"
    When I submit an api request
    Then I should see an exception "Forbidden"

  Scenario:  I should be able to search by zip code
    Given I use location "89101"
    When I request the phone numbers
    Then I should get an array of phone numbers
    When I count the stations
    Then the number of phone numbers should equal the number of locations

  Scenario: I should be able to search by city and state
    Given I use location "Las Vegas, NV"
    When I request the phone numbers
    Then I should get an array of phone numbers
    When I count the stations
    Then the number of phone numbers should equal the number of locations

  Scenario: I should be able to search by Street and Postal Code
    Given I use location "Warm Springs Rd, 89119"
    When I request the phone numbers
    Then I should get an array of phone numbers
    When I count the stations
    Then the number of phone numbers should equal the number of locations

  Scenario: I should be able to search by Street, City and State
    Given I use location "Warm Springs Rd, Las Vegas, NV"
    When I request the phone numbers
    Then I should get an array of phone numbers
    When I count the stations
    Then the number of phone numbers should equal the number of locations

  Scenario: I should be able to search by Street, City and State
    Given I use location "Warm Springs Rd, Las Vegas, NV, 89119"
    When I request the phone numbers
    Then I should get an array of phone numbers
    When I count the stations
    Then the number of phone numbers should equal the number of locations

  Scenario: I should be able to use an array of search locations
    Given I use locations:
    | 89119 |
    | 89101 |
    When I request the phone numbers
    Then I should get an array of phone numbers
    When I count the stations
    Then the number of phone numbers should equal the number of locations

  Scenario: With a given response I should see the correct phone number
    Given I have the response :
      """
{
  "total_results": 1,
  "offset": 0,
  "fuel_stations": [
    {
      "station_phone": "702-384-1360"
    }
  ]
}
      """
    Then I should get "702-384-1360" for a phone number
    And I should have "1" as the number of stations


  Scenario: With a given response missing a phone number I should see an exception
    Given I have the response :
    """
{

  "total_results": 1,
  "offset": 0,
  "fuel_stations": [
    {
      "ng_vehicle_class": null,
      "distance": 0.67762
    }
  ]
}
      """
    When I submit an api request
    Then I should see an exception "A phone number record was not found"