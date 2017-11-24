<?php

namespace Despark\Cms\Seo\Observers;

use Despark\Cms\Seo\Contracts\Seoable;

/**
 * Class SeoModelObserver.
 */
class SeoModelObserver
{
    public function saving(Seoable $model)
    {
        $model->seo->validate();
    }

    /**
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function saved(Seoable $model)
    {
        $isEmpty = true;
        $input = request()->only([
            'meta_title',
            'meta_description',
            'facebook_title',
            'facebook_description',
            'twitter_title',
            'twitter_description',
        ]);

        foreach ($input as $key => $value) {
            if (! $value) {
                $input[$key] = null;
            } else {
                $isEmpty = false;
            }
        }

        if (! $isEmpty || $model->seo) {
            $model->seo->setRawAttributes($input);
            $model->seo()->save($model->seo);
        }
    }

    /**
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function deleted(Seoable $model)
    {
        $model->seo()->delete($model->seo);
    }
}
