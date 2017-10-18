<p align="center"><img src="https://despark.com/public/images/despark-logo.svg"></p>

<p align="center">
<a href="https://packagist.org/packages/despark/igni-seo"><img src="https://poser.pugx.org/despark/igni-seo/v/stable.svg" alt="Latest Stable Version"></a>
</p>

# Despark's igniCMS SEO Module for Laravle 5.3|5.4|5.5

## Installation

1. Run `composer require despark/igni-seo`.

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
  
3. Run the migrate command to add our seo table.
```shell
    php artisan migrate
```

4. In your entity file add the following code in your ```adminFormsField```:
```php
   'content' => [
      'type' => 'wysiwyg',
      'label' => 'Content',
      'additional_options' => ['init_instance_callback' => 'wysiwygTextChanged'],
    ],
    'seo' => [
      'type' => 'seo',
      'readability' => true, // true|false
      'actionVerb' => 'slug', // This field is optional. Use it only if your route parameter is not generated from the slug column
      'readabilityColumn' => 'content', // This field is optional. Use it only if your column for readability is not content
     ],
```

## Copyright and License

igniCMS was written by Despark for the Laravel framework and is released under the MIT License. See the LICENSE file for details.
