<?php

namespace Despark\Cms\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Seo extends Model
{
    protected $table = 'seo';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'facebook_title',
        'facebook_description',
        'twitter_title',
        'twitter_description',
    ];

    protected $rules = [
        'meta_title' => 'max:60',
        'meta_description' => 'max:156',
        'facebook_title' => 'max:60',
        'facebook_description' => 'max:156',
        'twitter_title' => 'max:60',
        'twitter_description' => 'max:156',
        'facebook_image' => 'image|max:5000',
        'twitter_image' => 'image|max:5000',
        'seo_social_image' => 'image|max:5000',
    ];

    public function validate()
    {
        Validator::make(request()->all(), $this->rules)->validate();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function seo()
    {
        return $this->morphTo('seo', 'resource_model', 'resource_id');
    }
}
