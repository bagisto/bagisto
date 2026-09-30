@php
    $copyrightContent = core()->getConfigData('general.content.footer.copyright_content');
@endphp

@if ($copyrightContent)
    {!! clean_content((string) $copyrightContent) !!}
@else
    {{ $slot }}
@endif
