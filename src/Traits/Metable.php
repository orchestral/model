<?php

namespace Orchestra\Model\Traits;

use Orchestra\Model\Value\Meta;
use Illuminate\Contracts\Support\Arrayable;

trait Metable
{
    /**
     * `meta` field accessor.
     *
     * @param  mixed  $value
     *
     * @return \Orchestra\Model\Value\Meta
     */
    public function getMetaAttribute($value): Meta
    {
        return $this->accessMetaAttribute($value);
    }

    /**
     * `meta` field mutator.
     *
     * @param  mixed  $value
     *
     * @return void
     */
    public function setMetaAttribute($value = null)
    {
        $this->attributes['meta'] = $this->mutateMetaAttribute($value);
    }

    /**
     * Get original meta data.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function getOriginalMetaData(string $key, $default = null)
    {
        $meta = $this->accessMetaAttribute($this->getOriginal('meta'));

        return $meta->get($key, $default);
    }

    /**
     * Get meta data.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function getMetaData(string $key, $default = null)
    {
        $meta = $this->getAttribute('meta');

        return $meta->get($key, $default);
    }

    /**
     * Put meta data.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function putMetaData(string $key, $value = null)
    {
        if (! is_array($key)) {
            $meta = $this->getAttribute('meta');
            $meta->put($key, $value);
            $this->setMetaAttribute($meta);

            return;
        }

        foreach ($key as $name => $value) {
            $this->putMetaData($name, $value);
        }
    }

    /**
     * Access meta attribute.
     *
     * @param  mixed  $value
     *
     * @return \Orchestra\Model\Value\Meta
     */
    protected function accessMetaAttribute($value): Meta
    {
        $meta = [];

        if (! is_null($value)) {
            $meta = $this->fromJson($value);
        }

        return new Meta($meta);
    }

    /**
     * Get value from mixed content.
     *
     * @param  mixed  $value
     *
     * @return string|null
     */
    protected function mutateMetaAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }

        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        } elseif (! is_array($value)) {
            $value = (array) $value;
        }

        return $this->castAttributeAsJson('meta', $value);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    abstract public function getAttribute($key);
}
