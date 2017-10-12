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

        $input = request()->all();

        $this->seoModel->setRawAttributes([
            'meta_description' => $input['meta_description'],
            'facebook_title' => $input['facebook_title'] ?? $this->seoModel->facebook_title,
            'facebook_description' => $input['facebook_description'] ?? $this->seoModel->facebook_description,
            'twitter_title' => $input['twitter_title'] ?? $this->seoModel->twitter_title,
            'twitter_description' => $input['twitter_description'] ?? $this->seoModel->twitter_description,
        ]);

        $model->seo()->save($this->seoModel);

        $model->saveSeoImages('facebook_image');
        $model->saveSeoImages('twitter_image');
    }

    /**
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function deleted(Seoable $model)
    {
        $model->seo()->delete($model->seo);
    }
}
