<?php

namespace Backpack\LanguageManager\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\LanguageManager\Models\LanguageLineOriginal;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\TranslationLoader\TranslationLoaderManager;
use Sushi\Sushi;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @property int $id
 * @property int $id_database
 * @property array $text
 * @property string $search
 * @property boolean $database
 * @property string $group
 * @property string $key
 * @property string $created_at
 */
class LanguageLine extends LanguageLineOriginal
{
    use CrudTrait;
    use Sushi;

    /**
     * Since the id is a string, we need to disable auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Sushi key type.
     */
    public $keyType = 'string';

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'text' => 'array',
        'database' => 'boolean',
    ];

    /**
     * Group key accessor.
     */
    public function getGroupKeyAttribute(): string
    {
        return "$this->group.$this->key";
    }

    /**
     * Load the data for the table.
     */
    public function getRows(): array
    {
        // database entries
        $entries = LanguageLineOriginal::all()
            ->mapWithKeys(fn(LanguageLineOriginal $item) => ["$item->group.$item->key" => [
                'id' => "$item->group.$item->key",
                'id_database' => $item->id,
                'database' => true,
                'group' => $item->group,
                'key' => $item->key,
                'text' => array_filter($item->text ?? []),
                'created_at' => $item->created_at,
            ]])
            ->toArray();

        $filePaths = config('backpack.language-manager.file_paths', []);

        if (config('backpack.language-manager.load_all_registered_translation_paths', true)) {
            $reflectionClass = new ReflectionClass(TranslationLoaderManager::class);
            $hints = $reflectionClass->getProperty('hints')->getValue(app()['translation.loader']);
            $filePaths = array_merge($filePaths, array_values($hints));
        }

        // file entries
        collect($filePaths)
            ->flatMap(fn(string $path) => File::allFiles($path))
            ->filter(fn(SplFileInfo $file) => $file->getExtension() === 'php')
            ->each(function (SplFileInfo $file) use (&$entries) {
                $group = Str::beforeLast($file->getFilename(), '.php');
                $locale = Str::of($file->getPath())->afterLast('/')->afterLast('\\')->value();

                collect(include $file)
                    ->dot()
                    ->filter(fn($text): bool => is_string($text))
                    ->each(function (string $text, string $key) use ($group, $file, $locale, &$entries) {
                        $entries["$group.$key"] ??= [
                            'id' => "$group.$key",
                            'id_database' => null,
                            'database' => false,
                            'group' => $group,
                            'key' => $key,
                            'text' => [],
                            'created_at' => $file->getMTime(),
                        ];
                        $entries["$group.$key"]['text'][$locale] ??= $text;
                    });
            });

        // enconde all the text arrays to json
        foreach ($entries as &$entry) {
            $entry['text'] = json_encode($entry['text']);
            $entry['search'] = Str::slug($entry['text']);
        }

        return array_values($entries);
    }

    /**
     * Boot the model.
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function (LanguageLine $entry): void {
            if (! $entry->database) {
                $entry = LanguageLineOriginal::create([
                    'group' => $entry->group,
                    'key' => $entry->key,
                    'text' => $entry->text,
                ]);
            }
        });

        static::deleted(function (LanguageLine $entry): void {
            LanguageLineOriginal::findOrFail($entry->id_database)->delete();
        });
    }
}
