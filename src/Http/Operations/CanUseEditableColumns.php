<?php

namespace Backpack\LanguageManager\Http\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\LanguageManager\Models\LanguageLine;
use Backpack\LanguageManager\Models\LanguageLineOriginal;
use Illuminate\Support\Facades\App;

if (class_exists(\Backpack\EditableColumns\AddonServiceProvider::class)) {
    trait CanUseEditableColumns
    {
        use \Backpack\EditableColumns\Http\Controllers\Operations\MinorUpdateOperation;

        private $minorUpdateEntry = null;
        private $minorUpdateRequest = null;

        private function editableColumnsEnabled(): bool
        {
            return config('backpack.language-manager.useEditableColumns');
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

                $entry = LanguageLineOriginal::find($entry->id_database);
                $entry->text = $text;
                $entry->save();
            } else {
                [$group, $key] = explode('.', $request->id);

                LanguageLineOriginal::create([
                    'group' => $group,
                    'key' => $key,
                    'text' => [
                        $locale => $request->value,
                    ],
                ]);
            }

            // fetch the entry from sushi
            $entry = LanguageLine::find($request->id);
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
