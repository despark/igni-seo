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

    public function getImageOptions($type)
    {
        if ($type === 'facebook_image') {
            return [
                'thumbnails' => [
                    'admin' => [
                        'width' => 400,
                        'height' => 210,
                        'type' => 'resize',
                    ],
                    'normal' => [
                        'width' => 1200,
                        'height' => 630,
                        'type' => 'resize',
                    ],
                ],
            ];
        }

        return [
            'thumbnails' => [
                'admin' => [
                    'width' => 400,
                    'height' => 210,
                    'type' => 'resize',
                ],
                'normal' => [
                    'width' => 1024,
                    'height' => 512,
                    'type' => 'resize',
                ],
            ],
        ];
    }

    public function saveSeoImages($type)
    {
        if (request()->has($type.'_delete')) {
            $this->deleteImages($type);
        }

        if (request()->hasFile($type)) {
            // Get image model
            $imageModel = $this->images()->getRelated()->newInstance();
            // First delete unused images
            $this->deleteImages($type);
            // Set retina factor
            $this->setRetinaFactor(false);
            // Save images physically
            $images = $this->manipulateImage(request()->file($type), $this->getImageOptions($type));
            // Save images in database
            $sourceFile = $images['original']['source'];

            $imageModel->setRawAttributes([
                'original_image' => $sourceFile->getFilename(),
                'retina_factor' => $this->getRetinaFactor() === false ? null : $this->getRetinaFactor(),
                'image_type' => $type,
            ]);

            $this->images()->save($imageModel);
        }
    }

    protected function deleteImages($type)
    {
        $image = $this->getImages($type)->first();

        if ($image) {
            \File::delete([$image->getSourceImagePath(), $image->getOriginalImagePath(), $image->getOriginalImagePath('normal'), $image->getOriginalImagePath('admin')]);

            $image->delete();
        }
    }
}
