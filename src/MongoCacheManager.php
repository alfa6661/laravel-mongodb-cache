<?php

namespace Alfa6661\Mongodb\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Arr;

class MongoCacheManager extends CacheManager
{

    /**
     * Create an instance of the mongodb cache driver.
     *
     * @param array $config
     * @return \Illuminate\Cache\Repository
     */
    protected function createMongodbDriver(array $config)
    {
        $connection = $this->app['db']->connection(Arr::get($config, 'connection'));

        return $this->repository(
            new MongoStore(
                $connection, $config['table'], $this->getPrefix($config)
            )
        );
    }
}
