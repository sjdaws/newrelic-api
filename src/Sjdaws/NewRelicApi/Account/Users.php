<?php

namespace Sjdaws\NewRelicApi\Account;

use Sjdaws\NewRelicApi\Resource;

class Users extends Resource
{
    /**
     * The type of filter to apply to the user list, can be 'ids' or 'email'
     *
     * @param string
     */
    private $filterType;

    /**
     * The value for the filter. Can be a single id, comma separated list of ids, an email address or part of an email address
     *
     * @param string
     */
    private $filterValue;

    /**
     * Add a filter to the request
     *
     * @param  string    $type
     * @param  mixed     $value  Can be an array or string
     * @throws Exception If an invalid parameter is passed
     * @return Users
     */
    public function filter($type, $value)
    {
        $type = mb_strtolower($type);

        /**
         * Make sure our type is either ids or email
         * @see https://docs.newrelic.com/docs/apis/rest-api-v2/account-examples-v2/listing-users-your-account#list_by_mail
         * @see https://docs.newrelic.com/docs/apis/rest-api-v2/account-examples-v2/listing-users-your-account#list_by_userid
         */
        if (!$this->checkString('filter type', $type) || !in_array($type, ['ids', 'email'])) {
            $this->throwException('Invalid filter type specified: ' . $type . '. Must be one of: [ids, email].');
        }

        // Parse filter values correctly so we always end up with a string
        $value = $this->parseFilterValues($type, $value);

        // If we don't have a string something is wrong
        if (!is_string($value)) {
            // If we are using the type ids we can accept arrays and strings, an array would've already
            // been converted to a string at this point but notify the end user
            $canBeArray = ($type == 'ids') ? ' or array' : '';

            $this->throwException('Invalid value type used: ' . gettype($value) . '. Must be a string' . $canBeArray . '.');
        }

        $this->filterType = $type;
        $this->filterValue = $value;

        $this->addLog('debug', "Setting filter type to '" . $this->filterType . "'");
        $this->addLog('debug', "Setting filter value to '" . $this->filterValue . "'");

        // Ensure we can chain methods
        return $this;
    }

    /**
     * Perform API request
     *
     * @throws GuzzleHttp\Exception\ClientException Exception thrown by Guzzle
     * @return GuzzleHttp\Psr7\Stream               The guzzle response body
     */
    public function get()
    {
        // Set filter if there is one, otherwise all users will be returned
        if ($this->filterType && $this->filterValue) {
            $this->addData('filter[' . $this->filterType . ']', $this->filterValue);
        }

        return $this->request('users.json');
    }

    /**
     * Parse filter values
     *
     * @param  string   $type
     * @param  string   $value
     * @return string
     */
    private function parseFilterValues($type, $value)
    {
        // Ids accepts multiple values so we might have a comma separated string or an array, either
        // way we want to filter the values to make sure it's legit
        if ($type == 'ids') {
            // If we don't have an array create on
            if (!is_array($value)) {
                $value = explode(',', $value);
            }

            // Convert all array values to integers
            $value = array_map('intval', $value);

            // Convert back to string
            $value = implode(',', $value);
        }

        return $value;
    }
}
