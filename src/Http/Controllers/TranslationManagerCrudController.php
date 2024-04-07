<?php

namespace Backpack\TranslationManager\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\TranslationManager\Models\TranslationLine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

/**
 * Class TranslationManagerCrudController
 * @package Backpack\TranslationManager\Http\Controllers
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TranslationManagerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\TranslationManager\Http\Operations\CanUseEditableColumns;

    /**
     * Setup
     */
    public function setup(): void
    {
        CRUD::setModel(TranslationLine::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/translation-manager');
        CRUD::setEntityNameStrings(__('backpack.translation-manager::translation_manager.translation_line'), __('backpack.translation-manager::translation_manager.translation_lines'));

        // access to edit and delete buttons
        CRUD::setAccessCondition(['delete'], fn(TranslationLine $entry) => $entry->database);

        // disable create
        if (! config('backpack.translation-manager.create', false)) {
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
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.text')),
            'value' => fn(TranslationLine $entry): mixed => $entry->getTranslation(App::getLocale()),
            'searchLogic' => function (Builder $query, mixed $column, string $search): void {
                $query->orWhere('search', 'like', '%'.Str::slug($search).'%');
            },
        ]);

        CRUD::addColumn([
            'name' => 'group_key',
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.key')),
            'type' => 'custom_html',
            'value' => fn(TranslationLine $entry): string => '<span class="badge" title="'.$entry->group_key.'">'.Str::limit($entry->group_key, 50).'</span>',
            'orderable' => true,
            'orderLogic' => fn(Builder $query, mixed $column, mixed $columnDirection): Builder => $query
                ->orderBy('group', $columnDirection)
                ->orderBy('key', $columnDirection),
            'searchLogic' => function (Builder $query, mixed $column, string $search): void {
                $query->orWhere('group', 'like', "%$search%")
                    ->orWhere('key', 'like', "%$search%");
            },
        ]);

        if (config('backpack.translation-manager.display_source', false)) {
            CRUD::addColumn([
                'name' => 'database',
                'label' => ucfirst(__('backpack.translation-manager::translation_manager.source')),
                'type' => 'custom_html',
                'value' => function (TranslationLine $entry): string {
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
        CRUD::setDetailsRowView('backpack.translation-manager::admin.details_row');

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
            'type' => 'translation-preview-table',
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.text')),
        ]);
    }

    /**
     * Setup Create Operation
     */
    protected function setupCreateOperation(): void
    {
        $groups = config('backpack.translation-manager.groups', []);

        CRUD::addField([
            'name' => 'group',
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.group')),
            'wrapper' => ['class' => 'form-group col-md-4'],
            'type' => empty($groups) ? 'text' : 'select_from_array',
            'options' => $groups,
        ]);

        CRUD::addField([
            'name' => 'key',
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.key')),
            'type' => 'text',
            'wrapper' => ['class' => 'form-group col-md-8'],
        ]);

        CRUD::addField([
            'name' => 'text',
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.text')),
            'type' => 'translation-edit-field',
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
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.group')),
        ], fn(): array => TranslationLine::select('group')
            ->distinct()
            ->pluck('group', 'group')
            ->toArray(), function (string $options): void {
            CRUD::addClause('whereIn', 'group', json_decode($options));
        });

        // database/file filter
        CRUD::addFilter([
            'name' => 'source',
            'type' => 'select2',
            'label' => ucfirst(__('backpack.translation-manager::translation_manager.source')),
        ], [
            'database' => ucfirst(__('backpack.translation-manager::translation_manager.database')),
            'file' => ucfirst(__('backpack.translation-manager::translation_manager.file')),
        ], function (string $option): void {
            CRUD::addClause('where', 'database', $option === 'database');
        });
    }
}
