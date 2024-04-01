<?php

namespace Backpack\TranslationManager\Http\Operations;

if (class_exists(\Backpack\EditableColumns\AddonServiceProvider::class)) {
    trait MinorUpdateOperation
    {
        use \Backpack\EditableColumns\Http\Controllers\Operations\MinorUpdateOperation;

        private function editableColumnsEnabled(): bool
        {
            return true;
        }
    }
} else {
    trait MinorUpdateOperation
    {
        private function editableColumnsEnabled(): bool
        {
            return false;
        }
    }
}
