<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            $model->generateUniqueSlug();
        });
    }

    protected function generateUniqueSlug()
    {
        if (empty($this->slug)) {
            $slug = Str::slug($this->name);
            $i = 1;
            while ($this->otherRecordExistsWithSlug($slug) || $slug === '') {
                $slug = $slug . '-'  . $i;
                $i++;
            }
            $this->slug = $slug;
        }
    }

    /**
     * Determine if a record exists with the given slug.
     */
    protected function otherRecordExistsWithSlug($slug)
    {
        return (bool)static::withoutGlobalScopes()->whereSlug($slug)->first();
    }
}
