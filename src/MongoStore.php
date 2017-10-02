<?php

namespace Alfa6661\Mongodb\Cache;

use Closure;
use Illuminate\Cache\DatabaseStore;

class MongoStore extends DatabaseStore
{

    /**
     * Increment or decrement an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  \Closure  $callback
     * @return int|bool
     */
    protected function incrementOrDecrement($key, $value, Closure $callback)
    {
        $prefixed = $this->prefix.$key;
        $cache = $this->table()->where('key', $prefixed)->lockForUpdate()->first();

        if (is_null($cache)) {
            return false;
        }

        if (is_array($cache)) {
            $cache = (object) $cache;
        }

        $current = $cache->value;
        $new = $callback((int) $current, $value);

        if (! is_numeric($current)) {
            return false;
        }

        $this->table()->where('key', $prefixed)->update([
            'value' => $this->encrypter->encrypt($new)
        ]);

        return $new;
    }

}
