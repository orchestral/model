<?php namespace Orchestra\Model\Traits;

use Orchestra\Model\Value\Meta;

trait MetableTrait
{
    /**
     * `meta` field accessor.
     *
     * @param  mixed  $value
     * @return \Orchestra\Model\Value\Meta
     */
    public function getMetaAttribute($value)
    {
        $meta = [];

        if (! is_null($value)) {
            $meta = json_decode($value, true);
        }

        return new Meta($meta);
    }
    /**
     * `meta` field mutator.
     *
     * @param  \Orchestra\Model\Value\Meta|null  $value
     * @return void
     */
    public function setMetaAttribute(Meta $value = null)
    {
        if (is_null($value)) {
            return;
        }
        
        $this->attributes['meta'] = $value->toJson();
    }
}
