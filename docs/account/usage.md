# Account/Usage

<sup>[Back to readme](https://github.com/sjdaws/newrelic-api/blob/master/readme.md)</sup>

## Documentation

- [Usage](#usage)
- [Methods](#methods)
- [Options](#options)
- [Response](#response)

### Usage

To get usage metrics you must specify an product type:

```php
<?php

$apiKey = 'thisisnotrealyouwillneedanapikey';

// Specify application using productType
$client = new Sjdaws\NewRelicApi\Account\Usage($apiKey);
$usage = $client->productType('apm')->get();

// Specify application on get
$client = new Sjdaws\NewRelicApi\Account\Usage($apiKey);
$usage = $client->get('apm');
```

Valid product types are [apm, browser and mobile](https://docs.newrelic.com/docs/apis/rest-api-v2/account-examples-v2/retrieving-account-usage-metrics-rest-api#product_names).

### Methods

|Method|Description|Parameters|
|---|---|---|
|`get($type)`|Get a list of usage metrics for the account|`$type`: optional, string, one of [apm, browser, mobile], optional if already set via `productType()`|

### Options

By default usage metrics will be returned for the current day without including subaccounts. This behaviour can be changed by toggling the following options:

|Method|Description|Parameters|
|---|---|---|
|`productType($type)`|Set the product type to get usage metrics for|`$type`: string, one of [apm, browser, mobile]|
|`startDate($date)`|Set the date to get usage metrics from|`$date`: mixed, a timestamp or string parseable by `strtotime`|
|`endDate($date)`|Set the date to get usage metrics to|`$date`: mixed, a timestamp or string parseable by `strtotime`|
|`includeSubAccounts($include)`|Whether or not to also get information for sub accounts|`$include`: boolean|

All methods return the `Usage` instance and therefore are chainable.

### Response

The response will be a JSON string.

Depending on which application is requested, the output for usage includes:
* The product for which you requested usage metrics
* The date range chosen for the report
* The units in which the usage is reported
* The usage for each day in the range

Sample response:
```json
{
   "usage_data":{
      "product":"apm",
      "from":"2000-01-01T00:00:00+00:00",
      "to":"2000-01-01T23:59:59+00:00",
      "unit":"hosts",
      "usages":[
         {
            "from":"2000-01-01T00:00:00+00:00",
            "to":"2000-01-01T23:59:59+00:00",
            "usage":100
         }
      ]
   }
}
```

### Errors

If an error is encountered an exception will be thrown.

