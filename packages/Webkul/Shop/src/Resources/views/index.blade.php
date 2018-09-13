@inject('rateHelper' , 'Webkul\Shipping\Helper\Rate')


<div>

    @foreach($rateHelper->collectRates() as $key=>$count)

    <div class="shipping-method">

        <input type="radio" name="price"> ${{ core()->currency($count) }}  <span> {{ $key }} </span>


    </div>

    @endforeach


</div>

<style>
    span {
        margin-left: 10px;
    }
</style>