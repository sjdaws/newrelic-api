<?php

namespace Sjdaws\NewRelicApi\Application;

use Sjdaws\NewRelicApi\Application;

class Deployments extends Application
{
    /**
     * Record a new deployment
     *
     * @param  string                               $revision
     * @param  string                               $changelog
     * @param  string                               $description
     * @param  string                               $user
     * @throws Exception                            If appId is missing or any parameters is not a string or too long
     * @throws GuzzleHttp\Exception\ClientException Exception thrown by Guzzle
     * @return GuzzleHttp\Psr7\Stream               The guzzle response body
     */
    public function add($revision, $changelog = '', $description = '', $user = '')
    {
        // We must have an app id set or a deployment can't be recorded
        $this->checkAppId();

        // Check parameters
        $this->checkString('revision', $revision, 127);
        $this->checkString('changelog', $changelog, 65535);
        $this->checkString('description', $description, 65535);
        $this->checkString('user', $user, 31);

        $this->addData('deployment', array(
            'revision'    => $revision,
            'changelog'   => $changelog,
            'description' => $description,
            'user'        => $user,
        ));

        $this->addLog('debug', "Setting revision to '" . $revision . "'");
        $this->addLog('debug', "Setting changelog to '" . $changelog . "'");
        $this->addLog('debug', "Setting description to '" . $description . "'");
        $this->addLog('debug', "Setting user to '" . $user . "'");

        return $this->request('applications/' . $this->appId . '/deployments.json', 'POST');
    }

    /**
     * Get a list of deployments made for an application
     *
     * @throws GuzzleHttp\Exception\ClientException Exception thrown by Guzzle
     * @return GuzzleHttp\Psr7\Stream               The guzzle response body
     */
    public function get()
    {
        // We must have an app id set or a deployment can't be recorded
        $this->checkAppId();

        return $this->request('applications/' . $this->appId . '/deployments.json');
    }

    /**
     * Get a list of deployments made for an application
     *
     * @throws GuzzleHttp\Exception\ClientException Exception thrown by Guzzle
     * @return GuzzleHttp\Psr7\Stream               The guzzle response body
     */
    public function delete($deploymentId)
    {
        // We must have an app id set or a deployment can't be recorded
        $this->checkAppId();

        // Deployment id must be a positive integer
        $this->checkInteger('deployment id', $deploymentId, true);

        return $this->request('applications/' . $this->appId . '/deployments/' . $deploymentId . '.json', 'DELETE');
    }
}
