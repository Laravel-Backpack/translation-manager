@if ($crud->hasAccess('delete', $entry))
    <span onclick="revertEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-sm btn-link" data-button-type="revert">
        <span>
            <i class="la la-undo"></i>
            {{ ucfirst(trans('backpack.translation-manager::translation_manager.revert')) }}
        </span>
    </span>
@endif

@push('after_scripts') @if (request()->ajax()) @endpush @endif
@bassetBlock('backpack/translation-manager/buttons/revert-button-'.app()->getLocale().'.js')
<script>
    if (typeof revertEntry !== 'function') {
        function revertEntry(button) {
            let trans = {
                warning: '{!! addslashes(ucfirst(trans("backpack::base.warning"))) !!}',
                cancel: '{!! addslashes(ucfirst(trans("backpack::crud.cancel"))) !!}',
                revert: '{!! addslashes(ucfirst(trans("backpack.translation-manager::translation_manager.revert"))) !!}',
                revert_confirm: '{!! addslashes(trans("backpack.translation-manager::translation_manager.revert_confirm")) !!}',
                revert_confirmation_title: '{!! addslashes(trans("backpack.translation-manager::translation_manager.revert_confirmation_title")) !!}',
                revert_confirmation_message: '{!! addslashes(trans("backpack.translation-manager::translation_manager.revert_confirmation_message")) !!}',
                revert_confirmation_not_title: '{!! addslashes(trans("backpack.translation-manager::translation_manager.revert_confirmation_not_title")) !!}',
                revert_confirmation_not_message: '{!! addslashes(trans("backpack.translation-manager::translation_manager.revert_confirmation_not_message")) !!}',
            };

            swal({
                title: trans.warning,
                text: trans.revert_confirm,
                icon: 'warning',
                buttons: [trans.cancel, trans.revert],
                dangerMode: true,
            }).then((value) => {
                if (! value) return;
                
                $.ajax({
                    url: button.dataset.route,
                    type: 'DELETE',
                    success: result => {
                        if (result === "1") {
                            // Redraw the table
                            if (crud?.table) {
                                // Move to previous page in case of deleting the only item in table
                                if(crud.table.rows().count() === 1) {
                                    crud.table.page('previous');
                                }
                                crud.table.draw(false);
                            }

                            // Show a success notification bubble
                            new Noty({
                                type: 'success',
                                text: `<strong>${trans.revert_confirmation_title}</strong><br>${trans.revert_confirmation_message}`,
                            }).show();

                            // Hide the modal, if any
                            $('.modal').modal('hide');
                        } else {
                            // if the result is an array, it means 
                            // we have notification bubbles to show
                            if (result instanceof Object) {
                                // trigger one or more bubble notifications 
                                Object.entries(result).forEach(([type, entry]) => {
                                    entry.forEach(text => new Noty({ type, text}).show());
                                });
                            } else {
                                swal({
                                    title: trans.revert_confirmation_not_title,
                                    text: trans.revert_confirmation_not_message,
                                    icon: 'error',
                                    timer: 4000,
                                    buttons: false,
                                });
                            }
                        }
                    },
                    error: result => {
                        // Show an alert with the result
                        swal({
                            title: trans.revert_confirmation_not_title,
                            text: trans.revert_confirmation_not_message,
                            icon: 'error',
                            timer: 4000,
                            buttons: false,
                        });
                    }
                });
            });
        }
    }
</script>
@endBassetBlock
@if (!request()->ajax()) @endpush @endif
