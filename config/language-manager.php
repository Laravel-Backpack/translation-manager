<?php

return [
    // This will automatically load all translation paths registered by packages
    // If you want to set the file paths manually, set this to false
    'load_all_registered_translation_paths' => true,

    // Paths from where the language files are loaded
    // You can restrict the paths to a specific set
    // It's only applied if load_all_registered_translation_paths is set to false
    'file_paths' => [
        lang_path(),
    ],

    // Allow create new language lines
    'create' => false,

    // This will limit the available groups when creating a new language line
    // If you want to allow all groups, just leave it empty
    // It's only applied if create is set to true
    'groups' => [
        //
    ],

    // Display source column
    // Adds a column to the language lines table to show the source, either database or file
    'display_source' => false,

    // If you have Editable Columns addon, Backpack will use it in the translations table.
    'useEditableColumns' => true,
];
