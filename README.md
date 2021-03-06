<p align="center"><img src="https://despark.com/public/images/despark-logo.svg"></p>

<p align="center">
<a href="https://packagist.org/packages/despark/igni-seo"><img src="https://poser.pugx.org/despark/igni-seo/v/stable.svg" alt="Latest Stable Version"></a>
</p>

# Despark's igniCMS SEO Module for Laravle 5.3|5.4|5.5

## Installation

1. Run `composer require despark/igni-seo`

2. Add igniCMS SEO module service providers before the _application service providers_ in the `config/app.php`, as shown below **(Optional for Laravel 5.5)** 

  _Example_

  ```php
   ...
    /*
    * igniCMS Service Providers
    */
    Despark\Cms\Seo\Providers\IgniSeoServiceProvider::class,
    /*
    * Package Service Providers...
    */
    Laravel\Tinker\TinkerServiceProvider::class,
   ...
  ```
  
3. Run ```php artisan vendor:publish --provider="Despark\Cms\Seo\Providers\IgniSeoServiceProvider"```. If you are using Laravel 5.3|5.4, replace ```--provider``` with ```--class```.
  
4. Run ```php artisan migrate``` to add our seo table to your database.

5. In your entity file add the following code in your ```adminFormsField```:
```php
   'content' => [
      'type' => 'wysiwyg',
      'label' => 'Content',
    ],
    'readability' => [
      'type' => 'readability',
      'for' => 'content', // This field is optional. Use it only if your column, that is going to be checked for readability, is not called content
      'editorPosition' => 0, // The position of the desired editor, if you have more than one wysiwygs
     ],
    'seo' => [
      'type' => 'seo',
      'routeName' => 'articles.show',
      'meta_title_field' => 'title', // This field is optional. Use it only if your column, which is going to be checked is not called title
     ],
```

6. Add our Traits and Interfaces to your Model.
```php
use Despark\Cms\Admin\Interfaces\UploadImageInterface;
use Despark\Cms\Admin\Traits\AdminImage;
use Despark\Cms\Models\AdminModel;
use Despark\Cms\Seo\Contracts\Seoable;
use Despark\Cms\Seo\Traits\HasSeo;

class Article extends AdminModel implements Seoable, UploadImageInterface
{
    use HasSeo;
    use AdminImage;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    protected $rules = [
        'title' => 'required',
        'slug' => 'required',
        'content' => 'required',
    ];

    protected $identifier = 'article';
}
```

## Get your SEO data

```php
  $seoData = $model->seo;
```

## Copyright and License

igniCMS was written by Despark for the Laravel framework and is released under the MIT License. See the LICENSE file for details.
