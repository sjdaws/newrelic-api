<?php

namespace Sjdaws\NewRelicApi;

use Sjdaws\NewRelicApi\Account\Usage;
use Sjdaws\NewRelicApi\Account\Users;

class Account extends Resource
{
    /**
     * Create a new usage instance
     *
     * @return Usage
     */
    public function usage()
    {
        return new Usage($this->apiKey, $this->logger);
    }

    /**
     * Create a new users instance
     *
     * @return Users
     */
    public function users()
    {
        return new Users($this->apiKey, $this->logger);
    }
}
