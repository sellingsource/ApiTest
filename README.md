# Coding Challenge

Welcome to the Selling Source PHP test. This will test your understanding of PHP, OOP, BDD, Unit Testing, and
consuming APIs. Please try to complete this test in under 4 hours and submit a pull request of your committed code.

Please use the [http://www.php-fig.org/](http://www.php-fig.org/ "PSR") syntax coding style.

## Goals

+ Show adaptability with new languages/libraries
+ Show learned knowledge of proper syntax
+ Show ability to create re-usable, flexible code

## Installing

1. Fork this repository
2. On a machine with php, clone your forked copy of this repository.
3. Install [http://getcomposer.org](http://getcomposer.org/ "Composer")
4. Install dependencies via composer
```
composer install
```

## Tests

1. PHPUnit

Please write PHPUnit tests for all of the functions in the Api\TestMe.php file.
Note: to run phpunit:
```
bin/phpunit
```

2. Api Fuel Station

In a flexible and re-usable manner, please implement the functionality to read from the
[http://developer.nrel.gov/docs/transportation/alt-fuel-stations-v1/nearest/](http://developer.nrel.gov/docs/transportation/alt-fuel-stations-v1/nearest/ "NREL") alternative fuel station api.
This project should implement the provided set of [http://docs.behat.org/en/v2.5/](http://docs.behat.org/en/v2.5/ "Behat")
features.

Note: to run phpunit:
```
bin/behat
```

## Submission
When you are happy with your results, please submit a pull request on gitHub.