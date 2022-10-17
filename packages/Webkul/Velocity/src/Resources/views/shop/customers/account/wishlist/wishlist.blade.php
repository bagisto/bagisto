@inject('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('page-detail-wrapper')

{!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

<wishlist-details items-value='@json($items)'></wishlist-details>

{!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}
@endsection

@push('scripts')
<script type="text/x-template" id="wishlist-details-template">
    <div>
        <div class="account-head">
            <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

            @if (count($items))
                <span class="account-action d-inline-flex">
                    <form id="remove-all-wishlist" class="d-none" action="{{ route('customer.wishlist.removeall') }}" method="POST">
                        @method('DELETE')

                        @csrf
                    </form>

                    @if ($isSharingEnabled)
                        <a
                            class="remove-decoration theme-btn light"
                            href="javascript:void(0);"
                            @click="showShareWishlistModal()">
                            {{ __('shop::app.customer.account.wishlist.share') }}
                        </a>
                    @endif

                    <a
                        class="remove-decoration theme-btn light"
                        href="javascript:void(0);"
                        @click="deleteAllWishlist();">
                        {{ __('shop::app.customer.account.wishlist.deleteall') }}
                    </a>
                </span>
            @endif
        </div>
        <div class="wishlist-container">
            @if ($items->count())
                @foreach ($items as $item)
                    @include ('shop::customers.account.wishlist.wishlist-product', [
                        'product'    => $item,
                        'visibility' => $isSharingEnabled
                    ])
                    
                @endforeach
                
            @else
                <div class="empty">
                    {{ __('customer::app.wishlist.empty') }}
                </div>
            @endif

            @if ($isSharingEnabled)
                <div id="shareWishlistModal" class="d-none">
                    <modal id="shareWishlist" :is-open="openClose" @onCloseModal='changeStatus()'>
                        <h3 slot="header">
                            {{ __('shop::app.customer.account.wishlist.share-wishlist') }}
                        </h3>

                        <i class="rango-close"></i>

                        <div slot="body">
                            <share-component :product-ids=projectIds :item-values=items></share-component>
                        </div>
                    </modal>
                </div>
            @endif
        </div> <br>
        <div>
            {{ $items->links()  }}
        </div>
    </div>
</script>

<script>
    Vue.component('wishlist-details', {
        template: '#wishlist-details-template',

        props: [
            'itemsValue',
        ],

        data() {
            return {
                projectIds: [],

                openClose: false,

                items: this.itemsValue
            }
        },

        mounted() {
            let items = JSON.parse(this.itemsValue);
            items.data.forEach((values) => {
                if (values.shared) {
                    this.projectIds.push(values.product_id);
                }
            })
        },

        methods: {
            getCheckBoxValue: function(event) {
                if (event.checked) {
                    this.projectIds.push(event.value);

                } else {
                    this.projectIds.filter((value, index) => {
                        if (value == event.value) {
                            this.projectIds.splice(index, 1);
                        }
                    });
                }
            },

            showShareWishlistModal: function() {

                document.getElementById('shareWishlistModal').classList.remove('d-none');

                window.app.showModal('shareWishlist');

                this.openClose = true;
            },

            changeStatus: function() {
                this.openClose = false;
            },

            deleteAllWishlist: function() {
                if (confirm("{{ __('shop::app.customer.account.wishlist.confirm-delete-all')}}")) {
                    document.getElementById('remove-all-wishlist').submit();
                }

                return;
            }
        }
    })
</script>

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

        <div class="form-group select-container">
            <div>
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
            <div class='select-all'>
                <input type='checkbox' id='check-all' ref='selectAll'>
                <p>{{ __('shop::app.customer.account.wishlist.select-all') }}</p>
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
    Vue.component('share-component', {

        props: [
            'productIds',
            'itemValues'
        ],

        template: '#share-component-template',

        inject: ['$validator'],

        data: function() {
            return {
                isWishlistShared: parseInt("{{ $isWishlistShared }}"),

                wishlistSharedLink: "{{ $wishlistSharedLink }}".replace(/&amp;/g, "&"),
            }
        },

        methods: {

            shareWishlist: function(val) {

                let self = this;

                let checked = this.$refs.selectAll.checked;

                this.$root.showLoader();

                let items = JSON.parse(this.itemValues);

                let itemsCount = items.data.length;

                this.$http.post("{{ route('customer.wishlist.share') }}", {
                        shared: val,
                        product_ids: !checked ? this.productIds : [],
                        product_count: itemsCount
                    })
                    .then(function(response) {
                        self.$root.hideLoader();

                        self.isWishlistShared = response.data.isWishlistShared;

                        self.wishlistSharedLink = response.data.wishlistSharedLink;
                    })
                    .catch(function(error) {
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
    function showCopyMessage() {
        $('#copy-btn').text("{{ __('shop::app.customer.account.wishlist.copied') }}");
        $('#copy-btn').css({
            backgroundColor: '#146e24'
        });
    }
</script>

@endpush