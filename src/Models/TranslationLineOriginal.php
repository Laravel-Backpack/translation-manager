<?php

namespace Backpack\TranslationManager\Models;

use Spatie\TranslationLoader\LanguageLine;

/**
 * @property int $id
 * @property string $group
 * @property string $key
 * @property array $text
 * @property string $created_at
 * @property string $updated_at
 */
class TranslationLineOriginal extends LanguageLine
{
    protected $table = 'language_lines';
}
