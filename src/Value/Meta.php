<?php

namespace Orchestra\Model\Value;

use Illuminate\Support\Arr;
use Orchestra\Support\Fluent;

class Meta extends Fluent
{
    /**
     * Get an attribute from the container.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * Set a value from a key.
     *
     * @param  string  $key    a string of key to add the value
     * @param  mixed   $value  the value
     *
     * @return $this
     */
    public function put(string $key, $value = '')
    {
        Arr::set($this->attributes, $key, $value);

        return $this;
    }

    /**
     * Forget a key.
     *
     * @param  string  $key
     *
     * @return $this
     */
    public function forget(string $key)
    {
        Arr::forget($this->attributes, $key);

        return $this;
    }
}
