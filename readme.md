# Language Manager

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

Language Manager provides a user interface for Backpack to manage translations. It allows you to:

- View a list of all translations present in your application's language files (including vendor translations).
- Edit translations directly within the list.
- Search and filter translations for easy management.

This package leverages the functionalities of `spatie/laravel-translation-loader` to Backpack for Laravel, providing a user interface to manage translations.

## Preview

![](https://user-images.githubusercontent.com/1032474/205863022-827f3248-a9f3-4d05-896f-5fa7a40227be.gif)


## Demo

Try it right now, edit some translations in [our online demo](https://demo.backpackforlaravel.com/admin/language-manager).  

## Installation

In your Laravel + Backpack project, install this package:

1) Install the package using Composer:

```bash
composer require backpack/language-manager
```

2) Add menu items to `sidebar_content.blade.php`:

```bash
php artisan backpack:add-menu-content "<x-backpack::menu-item title=\"Language Managers\" icon=\"la la-stream\" :link=\"backpack_url('language-manager')\" />"
```

3) Optionally, publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="config"
```

4) But also, if your package didn't already have [`spatie/laravel-translation-loader`](https://github.com/spatie/laravel-translation-loader) installed and set up, please [follow the installation steps in their docs](https://github.com/spatie/laravel-translation-loader#installation). We'll also copy-paste them here, for your convenience:


    4.1) In `config/app.php` you should replace Laravel's translation service provider

    ```diff
    -Illuminate\Translation\TranslationServiceProvider::class,
    +Spatie\TranslationLoader\TranslationServiceProvider::class,
    ```

    4.2) You must publish and run the migrations to create the `language_lines` table:

    ```bash
    php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="migrations"
    php artisan migrate
    ```

    4.3) Optionally you could publish the config file using this command.

    ```bash
    php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="config"
    ```

5) We highly recommend you to use this package allong with the [Language Switcher](https://github.com/Laravel-Backpack/language-switcher) package, so you can easily switch between languages in your panel.


## Usage

### Translation List View:

The list view displays a comprehensive list of all translations within your application.
You can search and filter translations using provided functionalities. Filters are available with the [Backpack Pro](https://backpackforlaravel.com/products/pro-for-unlimited-projects) package.  
All translations including vendor translations are displayed in the list view, if you don't want to see vendor translations, you can filter them out setting the `load_all_registered_translation_paths` config option to `false`.

### Editing Translations:

You can directly edit translations within the list view itself if you have the [Editable Columns](https://backpackforlaravel.com/products/editable-columns) package.
Once edited, the changes are saved to the database for persistence. All translations on the database have priority over the ones in the language files.

## Security

If you discover any security related issues, please email cristian.tabacitu@backpackforlaravel.com instead of using the issue tracker.

## Credits

- [Antonio Almeida](https://github.com/promatik)
- [Cristian Tabacitu](https://github.com/tabacitu)
- [All Contributors][link-contributors]

## License

This project was released under MIT License, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/backpack/language-manager.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/language-manager.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/backpack/language-manager
[link-downloads]: https://packagist.org/packages/backpack/language-manager
[link-contributors]: ../../contributors
