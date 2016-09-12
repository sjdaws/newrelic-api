<?php

namespace Sjdaws\NewRelicApi;

use Sjdaws\NewRelicApi\Account;

class Client extends Resource
{
    /**
     * Create a new account instance
     *
     * @return Account
     */
    public function account()
    {
        return new Account($this->apiKey, $this->logger);
    }
}
