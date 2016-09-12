<?php

namespace Sjdaws\NewRelicApi\Account;

use Monolog\Logger;
use Sjdaws\NewRelicApi\Resource;

class Usage extends Resource
{
    /**
     * The end date in Y-m-d format
     *
     * @param string
     */
    private $endDate;

    /**
     * Whether sub accounts should be included in the request or not
     *
     * @param boolean
     */
    private $includeSubAccounts;

    /**
     * The product type which will be called by this resource
     *
     * @param string
     */
    private $productType;

    /**
     * The product types that can be used with this resource
     *
     * @param array
     */
    private $productTypes = ['apm', 'browser', 'mobile'];

    /**
     * The start date in Y-m-d format
     *
     * @param string
     */
    private $startDate;

    /**
     * Create a new usage resource and set the start and end to today
     *
     * @param string $apiKey
     * @param Logger $logger
     */
    public function __construct($apiKey, Logger $logger = null)
    {
        parent::__construct($apiKey, $logger);

        // Use today as default date range
        $this->startDate(time());
        $this->endDate(time());
    }

    /**
     * Set the date to get data to
     *
     * @param  mixed   $date
     * @return Usage
     */
    public function endDate($date)
    {
        return $this->setDate('end', $date);
    }

    /**
     * Perform API request
     *
     * @param  string                               $type
     * @throws GuzzleHttp\Exception\ClientException Exception thrown by Guzzle
     * @return GuzzleHttp\Psr7\Stream               The guzzle response body
     */
    public function get($type = '')
    {
        // If we have a product type passed, use it from here
        if ($type) {
            $this->productType($type);
        }

        // If no type has been specified the request won't complete
        if (!$this->productType) {
            $this->throwException('No product type specified. Must be one of: [' . implode(', ', $this->productTypes) . '].');
        }

        $this->addData('start_date', $this->startDate);
        $this->addData('end_date', $this->endDate);

        if ($this->includeSubAccounts) {
            // New Relic expects the boolean to be sent as string
            $this->addData('include_subaccounts', 'true');
        }

        return $this->request('usages/' . $this->productType . '.json');
    }

    /**
     * Toggle include sub accounts
     *
     * @param  boolean   $include
     * @throws Exception If $include isn't a boolean
     * @return Usage
     */
    public function includeSubAccounts($include)
    {
        // Make sure include is a boolean
        if (!is_bool($include)) {
            $this->throwException('Invalid value specified: ' . gettype($include) . '. Must be a boolean.');
        }

        $this->includeSubAccounts = $include;
        $this->addLog('debug', 'Setting include sub accounts to ' . $this->includeSubAccounts);

        return $this;
    }

    /**
     * Set the product type
     *
     * @param  string    $type
     * @throws Exception If $type isn't a string or isn't a valid product type
     * @return Usage
     */
    public function productType($type)
    {
        $type = mb_strtolower($type);

        /**
         * Make sure the product type is apm, browser or mobile
         * @see https://docs.newrelic.com/docs/apis/rest-api-v2/account-examples-v2/retrieving-account-usage-metrics-rest-api#product_names
         */
        if (!$this->checkString('product type', $type) || !in_array($type, $this->productTypes)) {
            $this->throwException('Invalid product type specified: ' . $type . '. Must be one of: [' . implode(', ', $this->productTypes) . '].');
        }

        $this->productType = $type;
        $this->addLog('debug', 'Setting product type to ' . $this->productType);

        return $this;
    }

    /**
     * Set a date to something
     *
     * @param  string    $type
     * @param  mixed     $date
     * @throws Exception If $date isn't an integer or string
     * @return Usage
     */
    private function setDate($type, $date)
    {
        $variable = $type . 'Date';

        // Make sure date is a string or integer
        if (!is_string($date) && !is_integer($date)) {
            $this->throwException('Invalid date specified: ' . gettype($date) . '. Must be a unix timestamp or parseable string.');
        }

        $this->$variable = $this->getDate($date, 'Y-m-d');
        $this->addLog('debug', 'Setting ' . $type . ' date to ' . $this->$variable);

        return $this;
    }

    /**
     * Set the date to get data from
     *
     * @param  mixed   $date
     * @return Usage
     */
    public function startDate($date)
    {
        return $this->setDate('start', $date);
    }
}
