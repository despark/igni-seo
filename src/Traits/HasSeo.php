<?php

namespace Despark\Cms\Seo\Traits;

use Despark\Cms\Seo\Contracts\Seoable;
use Despark\Cms\Seo\Observers\SeoModelObserver;

/**
 * Class HasSeo.
 */
trait HasSeo
{
    /**
     * Bootstrap the trait.
     */
    public static function bootHasSeo()
    {
        static::observe(SeoModelObserver::class);
    }

    /**
     * @return MorphOne
     */
    public function seo()
    {
        $seoModel = app(Seoable::class);

        /* @var Model $this */
        return $this->morphOne(get_class($seoModel), 'seo', 'resource_model', 'resource_id');
    }
}
