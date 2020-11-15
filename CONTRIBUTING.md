# Contributing
---

Please note that the following requirements are only required if you wish to contribute to the module and develop locally. If you wish to only use the module, please refer to the [installation section](README.md#installation).

### Requirements
- GNU make
- GNU sed
- PHP >= 5.6
- Composer
- Git
- Curl

## Setting up your environment
Please ensure you have cloned the repository and have the [required tools](#requirements) installed. You can then use `make tools` to install the composer and node dependencies. Alternatively, you can run `composer install`.

### Running tests
Before running the tests please ensure you've 

We have three different types of tests;
- PHP Syntax/Sniffer `vendor/bin/phpcs`
- PHP Unit Tests `vendor/bin/phpunit`

To run both tests at once you can use `make test`.