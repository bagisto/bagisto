<div class="footer">
    <div class="footer-content">

        @include('shop::layouts.footer.newsletter-subscription')
        @include('shop::layouts.footer.footer-links')

        @if ($categories)
            {{-- @include('shop::layouts.footer.top-brands') --}}
        @endif

        @include('shop::layouts.footer.copy-right')
    </div>
</div>


