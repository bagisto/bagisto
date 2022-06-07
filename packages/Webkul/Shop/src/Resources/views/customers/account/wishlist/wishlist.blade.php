@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('account-content')
    @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

    <div class="account-layout">
        <div class="account-head mb-15">
            <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

            @if (count($items))
                <div class="account-action">
                    <form id="remove-all-wishlist" action="{{ route('customer.wishlist.removeall') }}" method="POST">
                        @method('DELETE')

                        @csrf
                    </form>

                    @if ($isSharingEnabled)
                        <a 
                            href="javascript:void(0);"
                            onclick="window.showShareWishlistModal();" class="m-20">
                            {{ __('shop::app.customer.account.wishlist.share') }}
                        </a>
                    @endif

                    <a
                        href="javascript:void(0);"
                        onclick="window.deleteAllWishlist()">
                        {{ __('shop::app.customer.account.wishlist.deleteall') }}
                    </a>
                </div>
            @endif

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

        <div class="account-items-list">
            @if ($items->count())
                @foreach ($items as $item)
                    @include('shop::customers.account.wishlist.wishlist-product', [
                        'item' => $item,
                        'visibility' => $isSharingEnabled
                    ])
                @endforeach

                <div class="bottom-toolbar">
                    {{ $items->links()  }}
                </div>
            @else
                <div class="empty">
                    {{ __('customer::app.wishlist.empty') }}
                </div>
            @endif
        </div>

        @if ($isSharingEnabled)
            <div id="shareWishlistModal" class="d-none">
                <modal id="shareWishlist" :is-open="modalIds.shareWishlist">
                    <h3 slot="header">
                        {{ __('shop::app.customer.account.wishlist.share-wishlist') }}
                    </h3>

                    <div slot="body">
                        <share-component></share-component>
                    </div>
                </modal>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}
    </div>
@endsection

@push('scripts')
    @if ($isSharingEnabled)
        <script type="text/x-template" id="share-component-template"> 
            <form method="POST">
                @csrf

                <div class="control-group">
                    <label for="shared" class="required">{{ __('shop::app.customer.account.wishlist.wishlist-sharing') }}</label>

                    <select name="shared" class="control" @change="shareWishlist($event.target.value)">
                        <option value="0" :selected="! isWishlistShared">{{ __('shop::app.customer.account.wishlist.disable') }}</option>
                        <option value="1" :selected="isWishlistShared">{{ __('shop::app.customer.account.wishlist.enable') }}</option>
                    </select>
                </div>

                <div class="control-group">
                    <label class="required">{{ __('shop::app.customer.account.wishlist.visibility') }}</label>

                    <div style="margin-top: 10px; margin-bottom: 5px;">
                        <span class="badge badge-sm badge-success" v-if="isWishlistShared">
                            {{ __('shop::app.customer.account.wishlist.public') }}
                        </span>

                        <span class="badge badge-sm badge-danger" v-else>
                            {{ __('shop::app.customer.account.wishlist.private') }}
                        </span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="required">{{ __('shop::app.customer.account.wishlist.shared-link') }}</label>

                    <div style="margin-top: 10px; margin-bottom: 5px;">
                        <div class="input-group"  v-if="isWishlistShared">
                            <input
                                type="text"
                                class="control"
                                v-model="wishlistSharedLink"
                                v-on:focus="$event.target.select()" 
                                ref="sharedLink"
                            />

                            <div class="input-group-append">
                                <button
                                    class="btn btn-primary btn-md"
                                    type="button"
                                    id="copy-btn"
                                title="{{ __('shop::app.customer.account.wishlist.copy-link') }}"
                                    @click="copyToClipboard"
                                >
                                    {{ __('shop::app.customer.account.wishlist.copy') }}
                                </button>
                            </div>
                        </div>
                            
                        <p class="alert alert-danger" v-else>
                            {{ __('shop::app.customer.account.wishlist.enable-wishlist-info') }}
                        </p>
                    </div>
                </div>
            </form>
        </script>
        
        <script>
            /**
            * Show share wishlist modal.
            */
            function showShareWishlistModal() {
                document.getElementById('shareWishlistModal').classList.remove('d-none');

                window.app.showModal('shareWishlist');
            }

            Vue.component('share-component', {
                template: '#share-component-template',

                inject: ['$validator'],

                data: function () {
                    return {
                        isWishlistShared: parseInt("{{ $isWishlistShared }}"),

                        wishlistSharedLink: "{{ $wishlistSharedLink }}",
                    }
                },

                methods: {
                    shareWishlist: function(val) {
                        var self = this;

                        this.$root.showLoader();

                        this.$http.post("{{ route('customer.wishlist.share') }}", {
                            shared: val
                        })
                        .then(function(response) {
                            self.$root.hideLoader();

                            self.isWishlistShared = response.data.isWishlistShared;

                            self.wishlistSharedLink = response.data.wishlistSharedLink;
                        })
                        .catch(function (error) {
                            self.$root.hideLoader();

                            window.location.reload();
                        })
                    },

                    copyToClipboard: function() {
                        this.$refs.sharedLink.focus();

                        document.execCommand('copy');
                        showCopyMessage();
                    }
                }
            });
        </script>
    @endif

    <script>
        /**
         * Delete all wishlist.
         */
        function deleteAllWishlist() {
            if (confirm('{{ __('shop::app.customer.account.wishlist.confirm-delete-all') }}')) document.getElementById('remove-all-wishlist').submit();

            return;
        }

        function showCopyMessage()
        {
            $('#copy-btn').text("{{ __('shop::app.customer.account.wishlist.copied') }}");
            $('#copy-btn').css({backgroundColor: '#146e24'});
        }
    </script>
    </script>
@endpush