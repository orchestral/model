<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Orchestra\Model\Value\Meta;

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
        return $this->accessMetableAttribute($value);
    }

    /**
     * `meta` field mutator.
     *
     * @param  mixed  $value
     *
     * @return void
     */
    public function setMetaAttribute($value = null): void
    {
        $this->attributes['meta'] = $this->mutateMetableAttribute('meta', $value);
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
        $meta = $this->accessMetableAttribute($this->getOriginal('meta'));

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
     * @param  string|array  $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function putMetaData($key, $value = null): void
    {
        $meta = $this->getAttribute('meta');

        if (\is_array($key)) {
            foreach ($key as $name => $value) {
                $meta->put($name, $value);
            }
        } else {
            $meta->put($key, $value);
        }

        $this->setMetaAttribute($meta);
    }

    /**
     * Forget meta data.
     *
     * @param  string|array  $key
     *
     * @return void
     */
    public function forgetMetaData($key): void
    {
        $meta = $this->getAttribute('meta');

        if (\is_array($key)) {
            foreach ($key as $name) {
                $meta->forget($name);
            }
        } else {
            $meta->forget($key);
        }

        $this->setMetaAttribute($meta);
    }

    /**
     * Access meta attribute.
     *
     * @param  mixed  $value
     *
     * @return \Orchestra\Model\Value\Meta
     */
    protected function accessMetableAttribute($value): Meta
    {
        $meta = [];

        if ($value instanceof Meta) {
            return $value;
        } elseif (! \is_null($value)) {
            $meta = $this->fromJson($value);
        }

        return new Meta($meta);
    }

    /**
     * Get value from mixed content.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return string|null
     */
    protected function mutateMetableAttribute(string $key, $value): ?string
    {
        if (\is_null($value)) {
            return $value;
        }

        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        } elseif (! \is_array($value)) {
            $value = (array) $value;
        }

        return $this->castAttributeAsJson($key, $value);
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
