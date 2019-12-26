<div class="footer-copy-right">
    <span class="fs16">
        @if (core()->getConfigData('general.content.footer.footer_content'))
            {{ core()->getConfigData('general.content.footer.footer_content') }}
        @else
            {{ trans('admin::app.footer.copy-right') }}
        @endif
    </span>
</div>
