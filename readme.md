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

![](https://private-user-images.githubusercontent.com/1032474/318216127-f65a24ea-473d-4fec-8ffc-b8137bcb1b9f.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MTIwNjM1MTEsIm5iZiI6MTcxMjA2MzIxMSwicGF0aCI6Ii8xMDMyNDc0LzMxODIxNjEyNy1mNjVhMjRlYS00NzNkLTRmZWMtOGZmYy1iODEzN2JjYjFiOWYucG5nP1gtQW16LUFsZ29yaXRobT1BV1M0LUhNQUMtU0hBMjU2JlgtQW16LUNyZWRlbnRpYWw9QUtJQVZDT0RZTFNBNTNQUUs0WkElMkYyMDI0MDQwMiUyRnVzLWVhc3QtMSUyRnMzJTJGYXdzNF9yZXF1ZXN0JlgtQW16LURhdGU9MjAyNDA0MDJUMTMwNjUxWiZYLUFtei1FeHBpcmVzPTMwMCZYLUFtei1TaWduYXR1cmU9YjU5YWE4MzAzZjFkODIwYjFkOWMwZGUzZDY0YzM0MjEzNjU0OTU2YTE2NGQ4NWQ5OTVlNjU4OWZmMzk3MWI0YyZYLUFtei1TaWduZWRIZWFkZXJzPWhvc3QmYWN0b3JfaWQ9MCZrZXlfaWQ9MCZyZXBvX2lkPTAifQ.BgcKZ2Ug4Co-meK972MGkQRqt9PRPPy1wDe57NT0X5A)


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

![](https://private-user-images.githubusercontent.com/1032474/318216128-a60b3204-e3f7-48f2-bb83-e300b01da481.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MTIwNjM1MTEsIm5iZiI6MTcxMjA2MzIxMSwicGF0aCI6Ii8xMDMyNDc0LzMxODIxNjEyOC1hNjBiMzIwNC1lM2Y3LTQ4ZjItYmI4My1lMzAwYjAxZGE0ODEucG5nP1gtQW16LUFsZ29yaXRobT1BV1M0LUhNQUMtU0hBMjU2JlgtQW16LUNyZWRlbnRpYWw9QUtJQVZDT0RZTFNBNTNQUUs0WkElMkYyMDI0MDQwMiUyRnVzLWVhc3QtMSUyRnMzJTJGYXdzNF9yZXF1ZXN0JlgtQW16LURhdGU9MjAyNDA0MDJUMTMwNjUxWiZYLUFtei1FeHBpcmVzPTMwMCZYLUFtei1TaWduYXR1cmU9MTNiODE4OGVhMzk2ZjAyMGU0MDMxNjA4ZWJiYmE2MjliNjJlZTFjMWY2MWY0MjRkYzJkZmJhYTVjY2Q4ZDMxMyZYLUFtei1TaWduZWRIZWFkZXJzPWhvc3QmYWN0b3JfaWQ9MCZrZXlfaWQ9MCZyZXBvX2lkPTAifQ.nrbE5bbZIKpQsthbc8LMrzGR3Z24K8zPrx5g2dHwsw0)

The list view displays a comprehensive list of all translations within your application. By default, all translations including vendor translations are displayed in the list view. If you don't want to see vendor translations, you can set the config option `load_all_registered_translation_paths` to `false` in `config/backpack/translation-manager.php`.

Additionally, if you have [Backpack Pro](https://backpackforlaravel.com/products/pro-for-unlimited-projects) installed, your admin can also see and use the filters, to quickly narrow down translations.

### Edit View

![](https://private-user-images.githubusercontent.com/1032474/318216125-13fa216a-24e0-4a82-b949-d24124c8ee2a.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MTIwNjM1MTEsIm5iZiI6MTcxMjA2MzIxMSwicGF0aCI6Ii8xMDMyNDc0LzMxODIxNjEyNS0xM2ZhMjE2YS0yNGUwLTRhODItYjk0OS1kMjQxMjRjOGVlMmEucG5nP1gtQW16LUFsZ29yaXRobT1BV1M0LUhNQUMtU0hBMjU2JlgtQW16LUNyZWRlbnRpYWw9QUtJQVZDT0RZTFNBNTNQUUs0WkElMkYyMDI0MDQwMiUyRnVzLWVhc3QtMSUyRnMzJTJGYXdzNF9yZXF1ZXN0JlgtQW16LURhdGU9MjAyNDA0MDJUMTMwNjUxWiZYLUFtei1FeHBpcmVzPTMwMCZYLUFtei1TaWduYXR1cmU9MDFkMDIwYmRhOThiM2RjNGZjZTc5NGVkMWQ5Nzc3YjVhZDhhZmI4YjIyMTg5MzZjM2Q0ZDlhNGViZTQ4NzgzNiZYLUFtei1TaWduZWRIZWFkZXJzPWhvc3QmYWN0b3JfaWQ9MCZrZXlfaWQ9MCZyZXBvX2lkPTAifQ.I5svlxban472nVMxapcqy11XmnzFZMs2jGKnVZO4T3g)

Any translation can be edited by clicking the Edit button. It will open a page where the admin can input the new value, for all languages. 

Once edited, the changes are saved to the database for persistence. All translations on the database have priority over the ones in the language files. This means that you can safely let your admin edit translations, in production. Your auto-deploys will continue working, because no files get edited, only DB entries.

### Editable Columns

![](https://private-user-images.githubusercontent.com/1032474/318216122-88996f7c-6807-4c54-a3f8-10ab18afaa24.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MTIwNjM1MTEsIm5iZiI6MTcxMjA2MzIxMSwicGF0aCI6Ii8xMDMyNDc0LzMxODIxNjEyMi04ODk5NmY3Yy02ODA3LTRjNTQtYTNmOC0xMGFiMThhZmFhMjQucG5nP1gtQW16LUFsZ29yaXRobT1BV1M0LUhNQUMtU0hBMjU2JlgtQW16LUNyZWRlbnRpYWw9QUtJQVZDT0RZTFNBNTNQUUs0WkElMkYyMDI0MDQwMiUyRnVzLWVhc3QtMSUyRnMzJTJGYXdzNF9yZXF1ZXN0JlgtQW16LURhdGU9MjAyNDA0MDJUMTMwNjUxWiZYLUFtei1FeHBpcmVzPTMwMCZYLUFtei1TaWduYXR1cmU9N2ExN2NjNjhkNGNiMzNhNmRjZGE0MjlmNmM0Yzc2ZjBmMGNkYmE2NmNiNjhlMTBmMzZjYjZiZTg1ODQ0Mzg2MSZYLUFtei1TaWduZWRIZWFkZXJzPWhvc3QmYWN0b3JfaWQ9MCZrZXlfaWQ9MCZyZXBvX2lkPTAifQ.AGTc17YEEY9ReMRS7tanKOI5YUTnGDGxjtO1MsD2EfE)

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
