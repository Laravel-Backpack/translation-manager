<?php

namespace Backpack\TranslationManager\Models;

/**
 * @property int $id
 * @property string $group
 * @property string $key
 * @property array $text
 * @property string $created_at
 * @property string $updated_at
 */
class TranslationLineOriginal extends TranslationLine
{
    protected $table = 'language_lines';
}
