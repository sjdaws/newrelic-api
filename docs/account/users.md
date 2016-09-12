# Account/Users

<sup>[Back to readme](https://github.com/sjdaws/newrelic-api/blob/master/readme.md)</sup>

## Documentation

- [Usage](#usage)
- [Methods](#methods)
- [Options](#options)
- [Response](#response)
- [Errors](#errors)

### Usage

You can get all users by simply calling `get()`:

```php
<?php

$apiKey = 'thisisnotrealyouwillneedanapikey';

$client = new Sjdaws\NewRelicApi\Account\Users($apiKey);
$users = $client->get();
```

### Methods

|Method|Description|Parameters|
|---|---|---|
|`get()`|Get a list of users with access to the account||

### Options

By default all users will be returned though the user list can be filtered by ids or email.

|Method|Description|Parameters|
|---|---|---|
|`filter($type, $value)`|Filter the user list by email or ids|`$type`: string, one of [ids, email]<br>`$value`: mixed, string / integer or array (ids only)|

Filtering works differently based on the type:
* When filtering by ids `$value` can be a comma separated string of ids, a single integer or an array of integers
* When filtering by email `$value` must be a string but it can be part of an email, e.g. mywebsite.com to list all users from this domain

`filter()` returns the `User` instance and therefore is chainable.

### Response

The response will be a JSON string.

Sample response:
```json
{
   "users":[
      {
         "id":123456,
         "first_name":"My",
         "last_name":"Name",
         "email":"my.name@mywebsite.com",
         "role":"owner"
      },
      {
         "id":654321,
         "first_name":"Adam",
         "last_name":"Admin",
         "email":"adam.admin@mywebsite.com",
         "role":"admin"
      }
   ]
}
```

### Errors

If an error is encountered an exception will be thrown.

