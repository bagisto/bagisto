@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('account-content')
    @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

    <div class="account-layout">
        <div class="account-head mb-15">
            <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

            @if (count($wishlistItems))
                <div class="account-action">
                    <form id="remove-all-wishlist" action="{{ route('customer.wishlist.removeall') }}" method="POST">
                        @method('DELETE')

                        @csrf
                    </form>

                    <a
                        href="javascript:void(0);"
                        onclick="window.showShareWishlistModal();">
                        {{ __('shop::app.customer.account.wishlist.share') }}
                    </a>

                    <a
                        href="javascript:void(0);"
                        onclick="document.getElementById('remove-all-wishlist').submit();">
                        {{ __('shop::app.customer.account.wishlist.deleteall') }}
                    </a>
                </div>
            @endif

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $wishlistItems]) !!}

        <div class="account-items-list">
            @if ($wishlistItems->count())
                @foreach ($wishlistItems as $wishlistItem)
                    @include('shop::customers.account.wishlist.wishlist-product', [
                        'wishlistItem' => $wishlistItem
                    ])
                @endforeach

                <div class="bottom-toolbar">
                    {{ $wishlistItems->links()  }}
                </div>
            @else
                <div class="empty">
                    {{ __('customer::app.wishlist.empty') }}
                </div>
            @endif
        </div>

        <div id="shareWishlistModal" class="d-none">
            <modal id="shareWishlist" :is-open="modalIds.shareWishlist">
                <h3 slot="header">
                    {{ __('shop::app.customer.account.wishlist.share-wishlist') }}
                </h3>

                <i class="rango-close"></i>

                <div slot="body">
                    <form method="POST" action="{{ route('customer.wishlist.share') }}">
                        @csrf

                        <div class="control-group">
                            <label for="shared" class="required">{{ __('shop::app.customer.account.wishlist.wishlist-sharing') }}</label>

                            <select name="shared" class="control">
                                <option value="0" {{ $isWishlistShared ? '' : 'selected="selected"' }}>{{ __('shop::app.customer.account.wishlist.enable') }}</option>
                                <option value="1" {{ $isWishlistShared ? 'selected="selected"' : '' }}>{{ __('shop::app.customer.account.wishlist.disable') }}</option>
                            </select>
                        </div>

                        <div class="control-group">
                            <label class="required">{{ __('shop::app.customer.account.wishlist.visibility') }}</label>

                            <div class="mt-5">
                                @if ($isWishlistShared)
                                    <span class="badge badge-sm badge-success">{{ __('shop::app.customer.account.wishlist.public') }}</span>
                                @else
                                    <span class="badge badge-sm badge-danger">{{ __('shop::app.customer.account.wishlist.private') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="required">{{ __('shop::app.customer.account.wishlist.shared-link') }}</label>

                            <div>
                                @if ($isWishlistShared)
                                    <a href="{{ $wishlistSharedLink ?? 'javascript:void(0);' }}" target="_blank">{{ $wishlistSharedLink }}</a>
                                @else
                                    <p>{{ __('shop::app.customer.account.wishlist.enable-wishlist-info') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="page-action">
                            <button type="submit"  class="btn btn-lg btn-primary mt-10 pull-right">
                                {{ __('shop::app.customer.account.wishlist.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </modal>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $wishlistItems]) !!}
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Show share wishlist modal.
         */
        function showShareWishlistModal() {
            document.getElementById('shareWishlistModal').classList.remove('d-none');

            window.app.showModal('shareWishlist');
        }
    </script>
@endpush