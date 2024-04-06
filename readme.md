# Translation Manager

[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

Translation Manager provides a simple user interface to help you deal with translations in your Backpack application.
At a quick glance, some of the most relevant features are:

- View a list of all translations present in your application's language files (including vendor translations).
- Edit translations directly from the interface.
- Search and filter translations for easy management.

This package uses the battle tested [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader) under the hood.

## Preview

![lm_list_view](https://github.com/Laravel-Backpack/language-manager/assets/1032474/a60b3204-e3f7-48f2-bb83-e300b01da481)

## Demo

Try it right now, edit some translations in [our online demo](https://demo.backpackforlaravel.com/admin/translation-manager).  

## Installation

In your Laravel + Backpack project:

**1) Install the package using Composer**:

```bash
composer require backpack/translation-manager
```

**2) Configure the application**

> _If you already had [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader) installed and configured, you can skip to the next step. Otherwise, follow along._

2.1) In your `config/app.php` you must replace Laravel's translation service provider:

```diff
-Illuminate\Translation\TranslationServiceProvider::class,
+Spatie\TranslationLoader\TranslationServiceProvider::class,
```

2.2) You must publish and run the migrations to create the `language_lines` table:
```bash
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="migrations"
php artisan migrate
```

**3) Optional setup options**

3.1) Add a menu item to `menu_items.blade.php` for easy access:

```bash
php artisan backpack:add-menu-content "<x-backpack::menu-item title=\"Translation Manager\" icon=\"la la-stream\" :link=\"backpack_url('translation-manager')\" />"
```

3.2) Publish the config files:

```bash
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="config"
php artisan vendor:publish --provider="Backpack\TranslationManager\AddonServiceProvider" --tag="config"
```

**NOTE:** We highly recommend you to use this package alongside [Language Switcher](https://github.com/Laravel-Backpack/language-switcher) package, so that you can easily switch between languages in your panel.


## Usage

### List View:

![lm_list_view2](https://github.com/Laravel-Backpack/language-manager/assets/1032474/f65a24ea-473d-4fec-8ffc-b8137bcb1b9f)

The list view displays a comprehensive list of all translations within your application. By default, all translations including vendor translations are displayed in the list view. If you don't want to see vendor translations, you can set the config option `load_all_registered_translation_paths` to `false` in `config/backpack/translation-manager.php`.

Additionally, if you have [Backpack Pro](https://backpackforlaravel.com/products/pro-for-unlimited-projects) installed, your admin can also see and use the filters, to quickly narrow down translations.

### Edit View

![lm_edit_view](https://github.com/Laravel-Backpack/language-manager/assets/1032474/13fa216a-24e0-4a82-b949-d24124c8ee2a)

Any translation can be edited by clicking the Edit button. It will open a page where the admin can input the new value, for all languages. 

Once edited, the changes are saved to the database for persistence. All translations on the database have priority over the ones in the language files. This means that you can safely let your admin edit translations, in production. Your auto-deploys will continue working, because no files get edited, only DB entries.

### Editable Columns

![lm_editable_columns_view](https://github.com/Laravel-Backpack/language-manager/assets/1032474/88996f7c-6807-4c54-a3f8-10ab18afaa24)

If you have the [Editable Columns](https://backpackforlaravel.com/products/editable-columns) package installed, the admin can directly edit translations within the List View. This provides an excellent UX - because they can translate everything much much faster, without having to switch pages.

If you don't want that behavior you can disable it in the `config/backpack/translation-manager.php` file by setting `use_editable_columns => false`. 
If you don't find that file, see above the optional steps to publish the config files.

## Security

If you discover any security related issues, please email cristian.tabacitu@backpackforlaravel.com instead of using the issue tracker.

## Credits

- [Antonio Almeida](https://github.com/promatik)
- [Pedro Martins](https://github.com/pxpm)
- [Cristian Tabacitu](https://github.com/tabacitu)
- [All Contributors][link-contributors]

## License

This project was released under MIT License, so you can install it on top of any Backpack & Laravel project. Please see the [license file](https://backpackforlaravel.com/products/translation-manager/license.md) for more information. 

[ico-version]: https://img.shields.io/packagist/v/backpack/translation-manager.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/translation-manager.svg?style=flat-square

[link-author]: https://github.com/laravel-backpack
[link-contributors]: ../../contributors
[link-downloads]: https://packagist.org/packages/backpack/translation-manager
