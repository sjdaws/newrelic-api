# Application/Deployments

<sup>[Back to readme](https://github.com/sjdaws/newrelic-api/blob/master/readme.md)</sup>

## Documentation

- [Usage](#usage)
- [Options](#methods)
- [Options](#options)
- [Response](#response)

### Usage

To work with deployments you will need [an application id](https://docs.newrelic.com/docs/apis/rest-api-v2/requirements/finding-product-id#apm), if not application id is provided an exception will be thrown.

```php
<?php

$apiKey = 'thisisnotrealyouwillneedanapikey';
$appId = 123456;

$client = new Sjdaws\NewRelicApi\Application\Deployments($apiKey);
$deployments = $client->appId($appId)->get();
```

### Methods

|Method|Description|Parameters|
|---|---|---|
|`add($revision, $changelog, $description, $user)`|Add a new deployment to an application|`$revision`: string, a unique identifier for the deployment<br>`$changelog`: optional, string, a list of changes made<br>`$description`: optional, string, an executive overview of changes made<br>`$user`: optional, string, the user who made the change|
|`delete($deploymentId)`|Remove a deployment from an application|`$deploymentId`: integer, the id of the deployment to remove|
|`get()`|Get a list of deployments made to an application||

### Options

There are no options available when getting deployments.

### Response

The response will be a JSON string.

Sample response for `get()`:
```json
{
   "deployments":[
      {
         "id":1234567,
         "revision":"123",
         "changelog":"Added: /v2/deployments.rb, Removed: None",
         "description":"Added a deployments resource to the v2 API",
         "user":"Jim",
         "timestamp":"2000-01-01T00:00:00+00:00",
         "links":{
            "application":12345678
         }
      },
      {
         "id":1234568,
         "revision":"456",
         "changelog":"Added: /v3/deployments.rb, Removed: None",
         "description":"Added a deployments resource to the v3 API",
         "user":"Bob",
         "timestamp":"2000-01-01T00:00:00+00:00",
         "links":{
            "application":12345678
         }
      }
   ],
   "links":{
      "deployment.agent":"/v2/applications/{application_id}"
   }
}
```

Sample response for `add()` and `delete()`:
```json
{
   "deployment":{
      "id":1234567,
      "revision":"123",
      "changelog":"Added: /v2/deployments.rb, Removed: None",
      "description":"Added a deployments resource to the v2 API",
      "user":"Jim",
      "timestamp":"2000-01-01T00:00:00+00:00",
      "links":{
         "application":12345678
      }
   },
   "links":{
      "deployment.agent":"/v2/applications/{application_id}"
   }
}
```

### Errors

If an error is encountered an exception will be thrown.
