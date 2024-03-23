<?php

namespace Backpack\LanguageManager\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\LanguageManager\Models\LanguageLine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Spatie\TranslationLoader\LanguageLine as LanguageLineOriginal;

/**
 * Class LanguageManagerCrudController
 * @package Backpack\LanguageManager\Http\Controllers
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LanguageManagerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\LanguageManager\Http\Operations\MinorUpdateOperation;

    /**
     * Setup
     */
    public function setup(): void
    {
        CRUD::setModel(LanguageLine::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/language-manager');
        CRUD::setEntityNameStrings(__('backpack.language-manager::language_manager.language_line'), __('backpack.language-manager::language_manager.language_lines'));

        // access to edit and delete buttons
        CRUD::setAccessCondition(['delete'], fn(LanguageLine $entry) => $entry->database);

        // disable create
        if (! config('backpack.language-manager.create', false)) {
            CRUD::denyAccess('create');
        }
    }

    /**
     * Setup List Operation
     */
    protected function setupListOperation(): void
    {
        CRUD::addColumn([
            'name' => 'text',
            'type' => $this->editableColumnsEnabled() ? 'editable_text' : 'text',
            'label' => ucfirst(__('backpack.language-manager::language_manager.text')),
            'value' => fn(LanguageLine $entry): mixed => $entry->getTranslation(App::getLocale()),
            'searchLogic' => function (Builder $query, mixed $column, string $search): void {
                $query->orWhere('search', 'like', '%'.Str::slug($search).'%');
            },
        ]);

        CRUD::addColumn([
            'name' => 'group_key',
            'label' => ucfirst(__('backpack.language-manager::language_manager.key')),
            'type' => 'custom_html',
            'value' => function (LanguageLine $entry): string {
                return '<span class="badge" title="'.$entry->group_key.'">'.Str::limit($entry->group_key, 50).'</span>';
            },
            'orderable' => true,
            'orderLogic' => function (Builder $query, mixed $column, mixed $columnDirection): Builder {
                return $query
                    ->orderBy('group', $columnDirection)
                    ->orderBy('key', $columnDirection);
            },
            'searchLogic' => function (Builder $query, mixed $column, string $search): void {
                $query->orWhere('group', 'like', "%$search%")
                    ->orWhere('key', 'like', "%$search%");
            },
        ]);

        if (config('backpack.language-manager.display_source', false)) {
            CRUD::addColumn([
                'name' => 'database',
                'label' => ucfirst(__('backpack.language-manager::language_manager.source')),
                'type' => 'custom_html',
                'value' => function (LanguageLine $entry): string {
                    $value = $entry->database ? 'database' : 'file';
                    return '<i class="las la-'.$value.'" title="'.$value.'"></i>';
                },
            ]);
        }

        // replace delete with revert button
        CRUD::removeButton('delete');
        CRUD::addButtonFromView('line', 'revert', 'revert', 'end');

        // enable details row
        CRUD::enableDetailsRow();
        CRUD::setDetailsRowView('backpack.language-manager::admin.details_row');

        // set default order
        CRUD::orderBy('group', 'asc')->orderBy('key', 'asc');

        // filters
        $this->setupFilters();
    }

    /**
     * Setup Show Operation
     */
    protected function setupShowOperation(): void
    {
        //setup show operation extending setup List Operation but replacing the text column with a list of all translations
        $this->setupListOperation();

        CRUD::removeColumn('text');
        CRUD::addColumn([
            'name' => 'text',
            'type' => 'language-preview-table',
            'label' => ucfirst(__('backpack.language-manager::language_manager.text')),
        ]);
    }

    /**
     * Setup Create Operation
     */
    protected function setupCreateOperation(): void
    {
        $groups = config('backpack.language-manager.groups', []);

        CRUD::addField([
            'name' => 'group',
            'label' => ucfirst(__('backpack.language-manager::language_manager.group')),
            'wrapper' => ['class' => 'form-group col-md-4'],
            'type' => empty($groups) ? 'text' : 'select_from_array',
            'options' => $groups,
        ]);

        CRUD::addField([
            'name' => 'key',
            'label' => ucfirst(__('backpack.language-manager::language_manager.key')),
            'type' => 'text',
            'wrapper' => ['class' => 'form-group col-md-8'],
        ]);

        CRUD::addField([
            'name' => 'text',
            'label' => ucfirst(__('backpack.language-manager::language_manager.text')),
            'type' => 'language-edit-field',
        ]);

        CRUD::removeSaveAction('save_and_edit');
    }

    /**
     * Setup Update Operation
     */
    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    /**
     * Setup Filters
     */
    public function setupFilters(): void
    {
        if (! backpack_pro()) {
            return;
        }

        // group filter
        CRUD::addFilter([
            'name' => 'group',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('backpack.language-manager::language_manager.group')),
        ], function (): array {
            return LanguageLine::select('group')
                ->distinct()
                ->pluck('group', 'group')
                ->toArray();
        }, function (string $options): void {
            CRUD::addClause('whereIn', 'group', json_decode($options));
        });

        // database/file filter
        CRUD::addFilter([
            'name' => 'source',
            'type' => 'select2',
            'label' => ucfirst(__('backpack.language-manager::language_manager.source')),
        ], [
            'database' => ucfirst(__('backpack.language-manager::language_manager.database')),
            'file' => ucfirst(__('backpack.language-manager::language_manager.file')),
        ], function (string $option): void {
            CRUD::addClause('where', 'database', $option === 'database');
        });
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
        }

        // create
        else {
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
