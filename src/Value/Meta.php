<?php namespace Orchestra\Model\Value;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class Meta extends Fluent
{
    /**
     * Get an attribute from the container.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * Set a value from a key.
     *
     * @param  string  $key    A string of key to add the value.
     * @param  mixed   $value  The value.
     * @return $this
     */
    public function put($key, $value = '')
    {
        Arr::set($this->attributes, $key, $value);

        return $this;
    }
}
