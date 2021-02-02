<div class="sidebar">
    @foreach ($menu->items as $menuItem)
        <div class="menu-block">
            <div class="menu-block-title">
                {{ trans($menuItem['name']) }}

                <i class="icon icon-arrow-down right" id="down-icon"></i>
            </div>

            <div class="menu-block-content">
                <ul class="menubar">
                    @php
                        $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;

                        $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                    @endphp

                    @if (! $showCompare)
                        @php
                            unset($menuItem['children']['compare']);
                        @endphp
                    @endif

                    @if (! $showWishlist)
                        @php
                            unset($menuItem['children']['wishlist']);
                        @endphp
                    @endif

                    @foreach ($menuItem['children'] as $subMenuItem)
                        <li class="menu-item {{ $menu->getActive($subMenuItem) }}">
                            <a href="{{ $subMenuItem['url'] }}">
                                {{ trans($subMenuItem['name']) }}
                            </a>

                            <i class="icon angle-right-icon"></i>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(".icon.icon-arrow-down.right").on('click', function(e){
            var currentElement = $(e.currentTarget);
            if (currentElement.hasClass('icon-arrow-down')) {
                $(this).parents('.menu-block').find('.menubar').show();
                currentElement.removeClass('icon-arrow-down');
                currentElement.addClass('icon-arrow-up');
            } else {
                $(this).parents('.menu-block').find('.menubar').hide();
                currentElement.removeClass('icon-arrow-up');
                currentElement.addClass('icon-arrow-down');
            }
        });
    });
</script>
@endpush