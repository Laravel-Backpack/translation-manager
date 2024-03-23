<div class="d-grid align-items-md-center" style="grid-template-columns: 2.75rem 1fr; grid-template-rows: 2fr;">
    @foreach($entry['text'] as $locale => $translation)
        <div class="p-2" title="{{ $locale }}"><x-icon name="flag-language-{{ $locale }}" /></div>
        <div>{{ $translation }}</div>
    @endforeach
</div>