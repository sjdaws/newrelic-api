<?php

namespace Sjdaws\NewRelicApi;

use Sjdaws\NewRelicApi\Application\Deployments;

class Application extends Resource
{
    /**
     * The application id currently being worked on
     *
     * @param integer
     */
    protected $appId;

    /**
     * Set the application id
     *
     * @param  integer       $appId
     * @throws Exception     If appId is not a valid integer
     * @return Application
     */
    public function appId($appId)
    {
        // If app id isn't a positive integer it's invalid
        $this->checkInteger('application id', $appId, true);
        $this->appId = $appId;
    }

    /**
     * Check we have an application id before continuing
     *
     * @throws Exception
     * @return boolean
     */
    protected function checkAppId()
    {
        // We must have an app id set or a deployment can't be recorded
        if (!$this->appId) {
            $this->throwException('No application id specified. Application id is mandatory when using this method.');
        }

        return true;
    }

    /**
     * Create a new deployment instance
     *
     * @return Deployments
     */
    public function deployments()
    {
        $deployments = new Deployments($this->apiKey, $this->logger);

        if ($this->appId) {
            $deployments->appId($this->appId);
        }

        return $deployments;
    }
}
