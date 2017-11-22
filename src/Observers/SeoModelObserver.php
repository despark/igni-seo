<?php

namespace Despark\Cms\Seo\Observers;

use Despark\Cms\Seo\Contracts\Seoable;
use Despark\Cms\Seo\Models\Seo;

/**
 * Class SeoModelObserver.
 */
class SeoModelObserver
{
    /**
     * @var Seo
     */
    protected $seoModel;

    /**
     * SeoModelObserver constructor.
     *
     * @param Seo $seoModel
     */
    public function __construct(Seo $seoModel)
    {
        $this->seoModel = $seoModel;
    }

    public function saving(Seoable $model)
    {
        $this->seoModel->validate();
    }

    /**
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function saved(Seoable $model)
    {
        if ($model->seo) {
            $this->seoModel = $model->seo;
        }

        $input = request()->only(['meta_title', 'meta_description', 'facebook_title', 'facebook_description', 'twitter_title', 'twitter_description']);

        // For Laravel 5.3
        foreach ($input as $key => $value) {
            if (! $value) {
                $input[$key] = null;
            }
        }

        $this->seoModel->setRawAttributes($input);

        $model->seo()->save($this->seoModel);
    }

    /**
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function deleted(Seoable $model)
    {
        $model->seo()->delete($model->seo);
    }
}
