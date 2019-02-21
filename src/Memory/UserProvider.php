<?php

namespace Orchestra\Model\Memory;

use Illuminate\Support\Arr;
use Orchestra\Memory\Provider;

class UserProvider extends Provider
{
    /**
     * Get value of a key.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function get(string $key = null, $default = null)
    {
        $key = \str_replace('.', '/user-', $key);
        $value = Arr::get($this->items, $key);

        // We need to consider if the value pending to be deleted,
        // in this case return the default.
        if ($value === ':to-be-deleted:') {
            return \value($default);
        }

        // If the result is available from data, simply return it so we
        // don't have to fetch the same result again from the database.
        if (! \is_null($value)) {
            return $value;
        }

        if (\is_null($value = $this->handler->retrieve($key))) {
            return \value($default);
        }

        $this->put($key, $value);

        return $value;
    }

    /**
     * Set a value from a key.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    public function put(string $key, $value = '')
    {
        $key = \str_replace('.', '/user-', $key);
        $value = \value($value);

        $this->set($key, $value);

        return $value;
    }

    /**
     * Delete value of a key.
     *
     * @param  string   $key
     *
     * @return bool
     */
    public function forget(string $key = null): bool
    {
        $key = \str_replace('.', '/user-', $key);

        Arr::set($this->items, $key, ':to-be-deleted:');

        return true;
    }
}
