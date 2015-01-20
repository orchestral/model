<?php namespace Orchestra\Model\Traits;

use Orchestra\Model\Value\Meta;
use Illuminate\Contracts\Support\Arrayable;

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
     * @param  mixed  $value
     * @return void
     */
    public function setMetaAttribute($value = null)
    {
        $this->attributes['meta'] = $this->mutateMetaAttribute($value);
    }

    /**
     * Get value from mixed content.
     *
     * @param  mixed  $value
     * @return mixed
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

        return json_encode($value);
    }
}
