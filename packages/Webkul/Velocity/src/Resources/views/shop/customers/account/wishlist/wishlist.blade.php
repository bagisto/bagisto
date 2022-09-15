@inject('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@push('css')
    <style>
        .wishlist-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 20px;
        }
            
            .product-card {
                width: 100%;
                padding-right: 0;
                margin-top: 0px !important;
            }

            .product-image {
                width: 100% !important;
                max-width: 100% !important;
            }

            .product-information {
                width: fit-content !important;
            }
            #share-wishlist {
                display: flex;
                margin-top: 30px;
                align-items: center;
            }       
            input,p {
                margin: 0px;
            }
    </style>
@endpush

@section('page-detail-wrapper')
    <div class="account-head">
        <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

        @if (count($items))
            <span class="account-action d-inline-flex">
                <form id="remove-all-wishlist" class="d-none" action="{{ route('customer.wishlist.removeall') }}" method="POST">
                    @method('DELETE')

                    @csrf
                </form>

                <div class='select-all-section'>
                    <p>{{ __('shop::app.customer.account.wishlist.select-all') }}</p>
                    <input type="checkbox" id="check-all" onclick="checkAllCheckbox()">
                </div>

                @if ($isSharingEnabled)
                    <a
                        class="remove-decoration theme-btn light"
                        href="javascript:void(0);"
                        onclick="window.showShareWishlistModal();">
                        {{ __('shop::app.customer.account.wishlist.share') }}
                    </a>

                    &nbsp;
                @endif

                <a
                    class="remove-decoration theme-btn light"
                    href="javascript:void(0);"
                    onclick="window.deleteAllWishlist();">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </a>
            </span>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

    <div class="wishlist-container">
        @if ($items->count())
            @foreach ($items as $item)
                @include ('shop::customers.account.wishlist.wishlist-product', [
                    'product'    => $item,
                    'visibility' => $isSharingEnabled
                ])
                
            @endforeach

            <div>
                {{ $items->links()  }}
            </div>
        @else
            <div class="empty">
                {{ __('customer::app.wishlist.empty') }}
            </div>
        @endif
    </div>

    @if($isSharingEnabled)
        <div id="shareWishlistModal" class="d-none">
            <modal id="shareWishlist" :is-open="modalIds.shareWishlist">
                <h3 slot="header">
                    {{ __('shop::app.customer.account.wishlist.share-wishlist') }}
                </h3>

                <i class="rango-close"></i>

                <div slot="body">
                    <share-component :product-ids=arr></share-component>
                </div>
            </modal>
        </div>
    @endif

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}
@endsection

@push('scripts')
    @if($isSharingEnabled)
        <script type="text/x-template" id="share-component-template">
            <form method="POST">
                @csrf

                <div class="form-group">
                    <label class="label-style mandatory">
                        {{ __('shop::app.customer.account.wishlist.wishlist-sharing') }}
                    </label>

                    <select name="shared" class="form-control" @change="shareWishlist($event.target.value)">
                        <option value="0" :selected="! isWishlistShared">{{ __('shop::app.customer.account.wishlist.disable') }}</option>
                        <option value="1" :selected="isWishlistShared">{{ __('shop::app.customer.account.wishlist.enable') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="label-style mandatory">
                        {{ __('shop::app.customer.account.wishlist.visibility') }}
                    </label>

                    <div>
                        <span class="badge badge-success" v-if="isWishlistShared">
                            {{ __('shop::app.customer.account.wishlist.public') }}
                        </span>

                        <span class="badge badge-danger" v-else>
                            {{ __('shop::app.customer.account.wishlist.private') }}
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-style mandatory">
                        {{ __('shop::app.customer.account.wishlist.shared-link') }}
                    </label>

                    <div class="input-group" v-if="isWishlistShared">
                        <input
                            type="text"
                            class="form-control"
                            v-model="wishlistSharedLink"
                            v-on:focus="$event.target.select()"
                            ref="sharedLink"
                        />

                        <div class="input-group-append">
                            <button
                                class="btn btn-outline-secondary theme-btn"
                                style="padding: 6px 20px"
                                id="copy-btn"
                                title="{{ __('shop::app.customer.account.wishlist.copy-link') }}"
                                type="button"
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

                props : [
                    'productIds'
                ],

                template: '#share-component-template',

                inject: ['$validator'],

                data: function () {
                    return {
                        isWishlistShared: parseInt("{{ $isWishlistShared }}"),

                        wishlistSharedLink: "{{ $wishlistSharedLink }}".replace(/&amp;/g, "&"),
                    }
                },

                methods: {
                    shareWishlist: function(val) {
                        var self = this;

                        this.$root.showLoader();

                        this.$http.post("{{ route('customer.wishlist.share') }}", {
                            shared: val,
                            product_id:this.productIds
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
        
        const arr = []; 

        function getAllCheckboxValue() {
            $(".wishlist").each(function() {
                getCheckBoxValue(this);
                if ($(this).attr('checked')) {
                    $("#check-all").attr('checked',true);
                }
            });
        }
        
        getAllCheckboxValue();

        function checkAllCheckbox() {
            $(".wishlist").each(function() {
                if ($(this).attr('checked')) {
                    $(this).attr('checked',false);
                } else {
                    $(this).attr('checked',true);
                }
            });
            getAllCheckboxValue();
        }

        function getCheckBoxValue(event) {
            if (event.checked) {
                arr.push(event.value);

            } else {
                arr.filter((value,index)=>{
                    if (value == event.value) {
                        arr.splice(index,1);
                   }
                });
            }
        }

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
@endpush