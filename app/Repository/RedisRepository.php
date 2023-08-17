<?php

namespace App\Repository;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

class RedisRepository
{
    private Connection $redis;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setDialog($key, $value): mixed
    {
        $this->redis->set($key, json_encode($value), 'EX', 3000);

        return $this->getDialog($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getDialog($key): mixed
    {
        $dialog = $this->redis->get($key);

        return json_decode($dialog, true);
    }
}
