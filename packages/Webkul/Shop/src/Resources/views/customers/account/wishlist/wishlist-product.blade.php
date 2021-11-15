<div class="account-item-card mt-15 mb-15">
    <div class="media-info">
        @php
            $image = $wishlistItem->product->getTypeInstance()->getBaseImage($wishlistItem);
        @endphp

        <img class="media" src="{{ $image['small_image_url'] }}" alt="" />

        <div class="info">
            <div class="product-name">
                {{ $wishlistItem->product->name }}

                @if (isset($wishlistItem->additional['attributes']))
                    <div class="item-options">
                        @foreach ($wishlistItem->additional['attributes'] as $attribute)
                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                        @endforeach
                    </div>
                @endif
            </div>

            <span class="stars" style="display: inline">
                @for ($i = 1; $i <= $reviewHelper->getAverageRating($wishlistItem->product); $i++)
                    <span class="icon star-icon"></span>
                @endfor
            </span>
        </div>
    </div>

    <div class="operations">
        <form id="wishlist-{{ $wishlistItem->id }}" action="{{ route('customer.wishlist.remove', $wishlistItem->id) }}" method="POST">
            @method('DELETE')

            @csrf
        </form>

        @auth('customer')
            <a
                class="mb-50"
                href="javascript:void(0);"
                onclick="document.getElementById('wishlist-{{ $wishlistItem->id }}').submit();">
                <span class="icon trash-icon"></span>
            </a>
        @endauth

        <a href="{{ route('customer.wishlist.move', $wishlistItem->id) }}" class="btn btn-primary btn-md">
            {{ __('shop::app.customer.account.wishlist.move-to-cart') }}
        </a>
    </div>
</div>

<div class="horizontal-rule mb-10 mt-10"></div>