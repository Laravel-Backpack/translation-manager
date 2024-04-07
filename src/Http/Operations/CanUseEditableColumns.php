<?php

namespace Backpack\TranslationManager\Http\Operations;

use Backpack\TranslationManager\Models\TranslationLine;
use Backpack\TranslationManager\Models\TranslationLineOriginal;
use Illuminate\Support\Facades\App;

if (class_exists(\Backpack\EditableColumns\AddonServiceProvider::class)) {
    trait CanUseEditableColumns
    {
        use \Backpack\EditableColumns\Http\Controllers\Operations\MinorUpdateOperation;

        private $minorUpdateEntry;
        private $minorUpdateRequest;

        private function editableColumnsEnabled(): bool
        {
            return config('backpack.translation-manager.use_editable_columns');
        }

        /**
         * Override the parent method to customize the saving
         */
        public function saveMinorUpdateEntry()
        {
            $entry = $this->minorUpdateEntry;
            $request = $this->minorUpdateRequest;
            $locale = App::getLocale();

            // update
            if ($entry->id_database) {
                $text = $entry->text;
                $text[$locale] = $request->value;

                $entry = TranslationLineOriginal::find($entry->id_database);
                $entry->text = $text;
                $entry->save();
            } else {
                [$group, $key] = explode('.', (string) $request->id);

                TranslationLineOriginal::create([
                    'group' => $group,
                    'key' => $key,
                    'text' => [
                        $locale => $request->value,
                    ],
                ]);
            }

            // fetch the entry from sushi
            $entry = TranslationLine::find($request->id);
            $entry->database = true;

            return $entry;
        }
    }
} else {
    trait CanUseEditableColumns
    {
        private function editableColumnsEnabled(): bool
        {
            return false;
        }
    }
}
