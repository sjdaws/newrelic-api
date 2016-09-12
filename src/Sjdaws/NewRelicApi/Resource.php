<?php

namespace Sjdaws\NewRelicApi;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Monolog\Logger;

abstract class Resource
{
    /**
     * The API key used to access the New Relic API, should be an admin or REST api key
     * @see https://docs.newrelic.com/docs/apis/rest-api-v2/requirements/api-keys
     *
     * @param string
     */
    protected $apiKey;

    /**
     * The endpoint requests will be sent to
     * @see https://docs.newrelic.com/docs/apis/rest-api-v2/requirements/new-relic-rest-api-v2-getting-started#appid
     *
     * @param string
     */
    private $apiUrl = 'https://api.newrelic.com/v2/';

    /**
     * Payload to be sent with the request
     *
     * @param array
     */
    private $data = [];

    /**
     * Monolog instance to be used for logging messages
     *
     * @param Logger
     */
    protected $logger;

    /**
     * Create a new resource instance
     *
     * @param string $apiKey
     * @param Logger $logger
     */
    public function __construct($apiKey, Logger $logger = null)
    {
        $this->apiKey = $apiKey;
        $this->logger = $logger;
    }

    /**
     * Add some data to the payload to be sent with the request
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function addData($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Log a message
     *
     * @param  string $type
     * @param  string $message
     * @param  array  $data
     * @return void
     */
    protected function addLog($type, $message, array $data = [])
    {
        $type = mb_strtolower($type);

        // If we don't have a logger instance return to prevent fatal error
        if (!$this->logger instanceof Logger) {
            return;
        }

        // Make sure the type is valid
        if (!in_array($type, ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'])) {
            return;
        }

        $this->logger->$type($message, $data);
    }

    /**
     * Check a string is valid and within a set length
     *
     * @param  string      $name
     * @param  string      $string
     * @param  integer     $length
     * @throws Exception
     * @return boolean
     */
    protected function checkString($name, $string, $length = 0)
    {
        if (!is_string($string)) {
            $this->throwException('Invalid ' . $name . ' used: ' . gettype($string) . '. Must be a string.');
        }

        if ($length > 0 && mb_strlen($string) > $length) {
            $this->throwException(ucfirst($name) . ' is too long: ' . mb_strlen($string) . ' characters. ' . ucfirst($name) . ' can not be longer than ' . $length . ' characters.');
        }

        return true;
    }

    /**
     * Check if an integer is valid and positive
     *
     * @param  string      $name
     * @param  integer     $integer
     * @param  boolean     $positive
     * @throws Exception
     * @return boolean
     */
    protected function checkInteger($name, $integer, $positive = true)
    {
        $error = false;

        if (!is_integer($integer)) {
            $error = gettype($integer);
        }

        if ($positive && $integer <= 0) {
            $error = $integer;
        }

        if ($error) {
            $type = $positive ? 'a positive' : 'an';
            $this->throwException('Invalid ' . $name . ' specified: ' . $error . '. ' . ucfirst($name) . ' must be ' . $type . ' integer.');
        }

        return true;
    }

    /**
     * Convert most date/times to a timestamp
     *
     * @param  mixed  $date
     * @param  string $format
     * @return int
     */
    protected function getDate($date, $format = 'U')
    {
        // If date isn't a timestamp convert it to a timestamp
        if (!is_int($date)) {
            $date = strtotime($date);
        }

        return date($format, $date);
    }

    /**
     * Log a critical message and throw an exception
     *
     * @param  string      $message
     * @throws Exception
     * @return void
     */
    protected function throwException($message)
    {
        // Add critical log before throwing exception
        $this->addLog('critical', get_class($this) . ': ' . $message);
        throw new Exception($message);
    }

    /**
     * Send a request to the API endpoint and return json response
     *
     * @param  string                               $uri
     * @param  string                               $method
     * @throws GuzzleHttp\Exception\ClientException Exception thrown by Guzzle
     * @return string
     */
    protected function request($uri, $method = 'GET')
    {
        $guzzle = new GuzzleClient(['base_uri' => $this->apiUrl]);
        $params = ['headers' => ['X-Api-Key' => $this->apiKey]];

        if (count($this->data)) {
            $params['form_params'] = $this->data;
        }

        $this->addLog('debug', "Calling {$this->apiUrl}$uri using $method", $params);

        return $guzzle->request($method, $uri, $params)->getBody();
    }
}
