@php
    $locales = config('backpack.crud.locales');
@endphp

<div translation-edit-field data-init-function="bpFieldInitTranslationEditField" class="form-group col-sm-12 mb-3">
    <label>{{ $label }}</label>

    <div class="align-items-center justify-content-between d-flex mb-2 w-100">
        <div switcher class="input-group input-group-sm me-2" style="max-width: 10rem;">
            {{-- Flags --}}
            <label class="input-group-text" for="">
                <div flags>
                    @foreach($locales as $locale => $name)
                        <x-icon class="d-none" locale="{{ $locale }}" name="flag-language-{{ $locale }}" />
                    @endforeach
                </div>
            </label>

            {{-- Translation Switcher --}}
            <select 
                class="form-select">
                @foreach($locales as $locale => $name)
                    <option value="{{ $locale }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Toggler --}}
        <div class="d-inline-flex align-items-center">
            <label class="form-switch switch switch-sm switch-label switch-pill switch-primary mb-0">
                <input type="checkbox" class="switch-input form-check-input" id="switch_translation_edit_field">
            </label>
            <label class="font-weight-normal mb-0 ml-2" for="switch_translation_edit_field">{{ ucfirst(__('backpack.translation-manager::translation_manager.show-all-translations')) }}</label>
        </div>
    </div>

    {{-- Translation inputs --}}
    <div inputs>
        @foreach($locales as $locale => $name)
            <div class="d-none" locale="{{ $locale }}">
                <div class="align-items-start d-flex">
                    <x-icon name="flag-language-{{ $locale }}" class="d-none me-2" />
                    <textarea 
                        class="form-control mb-1"
                        name="{{ $field['name'] }}[{{ $locale }}]"
                        >{{ $field['value'][$locale] ?? ''}}</textarea>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('crud_fields_styles')
    @bassetBlock('backpack/translation-manager/fields/translation-edit.css')
    <style>
        [translation-edit-field] [flags] {
            width: 1.5rem;
            height: 1.25rem;
        }
        [translation-edit-field] [inputs] svg {
            width: 1.8rem;
        }
        [translation-edit-field].show-all-languages div[locale],
        [translation-edit-field].show-all-languages div[locale] svg {
            display: block !important;
        }
        [translation-edit-field].show-all-languages [switcher] {
            opacity: 0;
        }
    </style>
    @endBassetBlock
@endpush

@push('crud_fields_scripts')
    @bassetBlock('backpack/translation-manager/fields/translation-edit.js')
    <script>
        function bpFieldInitTranslationEditField(elem) {
            let element = elem[0];
            let select = element.querySelector('[switcher] select');
            let flags = element.querySelector('[flags]');
            let inputs = element.querySelector('[inputs]');
            let checkbox = element.querySelector('input[type="checkbox"]');

            function changeFlag() {
                let locale = select.value;
                flags.querySelectorAll('svg').forEach(flag => flag.classList.toggle('d-none', flag.getAttribute('locale') !== locale));
                inputs.querySelectorAll('div[locale]').forEach(elem => elem.classList.toggle('d-none', elem.getAttribute('locale') !== locale));
            }

            function checkboxChange() {
                localStorage.setItem('show-all-languages', checkbox.checked);
                element.classList.toggle('show-all-languages', checkbox.checked);
                select.disabled = checkbox.checked;
            }

            checkbox.addEventListener('change', checkboxChange);
            select.addEventListener('change', changeFlag);
            changeFlag();

            checkbox.checked = localStorage.getItem('show-all-languages') === 'true';
            checkboxChange();
        }
    </script>
    @endBassetBlock
@endpush
