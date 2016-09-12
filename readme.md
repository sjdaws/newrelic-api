# PHP Wrapper for New Relic API v2

[![Latest Stable Version](https://poser.pugx.org/sjdaws/newrelic-api/version.png)](https://packagist.org/packages/sjdaws/newrelic-api) [![License](https://poser.pugx.org/sjdaws/newrelic-api/license.png)](https://packagist.org/packages/sjdaws/newrelic-api) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sjdaws/newrelic-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sjdaws/newrelic-api/?branch=master) [![composer.lock available](https://poser.pugx.org/sjdaws/newrelic-api/composerlock)](https://packagist.org/packages/sjdaws/newrelic-api)

Copyright (c) 2014-2015 [Scott Dawson](https://github.com/sjdaws).

## Documentation

- [Installation](#installation)
- [Usage](#usage)
- [Endpoints](#endpoints)

### Installation

The wrapper is available on Packagist ([sjdaws/newrelic-api](http://packagist.org/packages/sjdaws/newrelic-api))
and can be installed using [Composer](http://getcomposer.org/):

```bash
composer require sjdaws/newrelic-api
```

### Usage

You will need an [Admin or REST API key from New Relic](https://docs.newrelic.com/docs/apis/rest-api-v2/requirements/new-relic-rest-api-v2-getting-started#api_key) to use the wrapper. The wrapper can either be used by instantiating endpoints directly or by instantiating a master client and accessing endpoints via chainable methods:

```php
<?php

$apiKey = 'thisisnotrealyouwillneedanapikey';

// Directly
$client = new Sjdaws\NewRelicApi\Account\Users($apiKey);
$users = $client->get();

// Using master client
$client = new Sjdaws\NewRelicApi\Client($apiKey);
$users = $client->account()->users()->get();
```

For the sake of simplicity the documents only use the direct method.

#### Debugging and Logging

For logging purposes the construct accepts a second parameter of a [Monolog](https://github.com/Seldaek/monolog) instance:

```php
<?php

$apiKey = 'thisisnotrealyouwillneedanapikey';

$logger = new Monolog\Logger('NewRelicApi');
$handler = new Monolog\Handler\StreamHandler('/path/to/file', Monolog\Logger::DEBUG);
$logger->pushHandler($handler);

$client = new Sjdaws\NewRelicApi\Account\Users($apiKey, $logger);
/**
 * filter() will log debug messages:
 * - Setting filter type to 'ids',
 * - Setting filter value to '123,456'
 */
$users = $client->filter('ids', [123, 456])->get();
```

### Endpoints

|Application|Endpoint|
|---|---|
|Account|[Usage metrics](https://github.com/sjdaws/newrelic-api/blob/master/docs/account/usage.md)|
|Account|[Listing users for your account](https://github.com/sjdaws/newrelic-api/blob/master/docs/account/users.md)|
|Application|[Recording deployments](https://github.com/sjdaws/newrelic-api/blob/master/docs/application/deployments.md)|
