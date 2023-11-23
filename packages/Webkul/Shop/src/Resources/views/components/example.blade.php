<!-- default product listing -->
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index')"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

<!-- category product listing -->
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['category_id' => 1])"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

<!-- featured product listing -->
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['featured' => 1])"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

<!-- new product listing -->
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['new' => 1])"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

<!-- basic/traditional form  -->
<x-shop::form action="">
     
    <!-- Type E-mail -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.label>
            Email
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="email"
            name="email"
            value=""
            rules="required|email"
            label="Email"
            placeholder="email@example.com"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error
            control-name="email"
        >
        </x-shop::form.control-group.error>
    </x-shop::form.control-group>

    <!-- Type Date -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.label>
            Date of Birth
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="date"
            name="date_of_birth" 
            value=""
            id="dob"
            label="Date of Birth"
            placeholder="Date of Birth"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error
            control-name="date_of_birth"
        >
        </x-shop::form.control-group.error>
    </x-shop::form.control-group>

    <!-- Type Date Time -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.label>
            Start Timing
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="datetime"
            name="starts_from"
            value=""
            id="starts_from"
            label="Start Timing"
            placeholder="Start Timing"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error
            control-name="starts_from"
        >
        </x-shop::form.control-group.error>
    </x-shop::form.control-group>

    <!-- Type Text -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.label class="required">
            @lang('name')
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="text"
            name="name"
            :value=""
            rules="required"
            label="name"
            placeholder="name"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error
            control-name="name"
        >
        </x-shop::form.control-group.error>
    </x-shop::form.control-group>

    <!-- Type Select -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.label>
            @lang('shop::app.catalog.families.create.column')
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="select"
            name="column"
            rules="required"
            :label="trans('shop::app.catalog.families.create.column')"
        >
            <!-- Default Option -->
            <option value="">
                @lang('shop::app.catalog.families.create.select-group')
            </option>

            <option value="1">
                @lang('shop::app.catalog.families.create.main-column')
            </option>

            <option value="2">
                @lang('shop::app.catalog.families.create.right-column')
            </option>
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error 
            control-name="column"
        >
        </x-shop::form.control-group.error>
    </x-shop::form.control-group>

    <!--Type Checkbox -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.control
            type="checkbox"
            name="is_unique"
            id="is_unique"
            for="is_unique"
            value="1"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.label
            for="is_unique"
        >
            @lang('shop::app.catalog.attributes.edit.is-unique')
        </x-shop::form.control-group.label>
    </x-shop::form.control-group>

    <!--Type Radio -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.control
            type="radio"
            name="is_unique"
            id="is_unique"
            for="is_unique"
            value="1"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.label
            for="is_unique"
        >
            @lang('shop::app.catalog.attributes.edit.is-unique')
        </x-shop::form.control-group.label>
    </x-shop::form.control-group>

    <!-- Type Tinymce -->
    <x-shop::form.control-group>
        <x-shop::form.control-group.label>
            Description
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="textarea"
            name="description"
            class="description"
            :value=""
            rules="required"
            label="Description"
            :tinymce="true"
        >
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error
            control-name="description"
        >
        </x-shop::form.control-group.error>
    </x-shop::form.control-group>
</x-shop::form>

<!-- customized/ajax form -->
<x-shop::form
    v-slot="{ meta, errors, handleSubmit }"
    as="div"
>
    <form @submit="handleSubmit($event, callMethodInComponent)">
        <x-shop::form.control-group>
            <x-shop::form.control-group.label>
                Email
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="email"
                name="email"
                :value="old('email')"
                rules="required"
                label="Email"
                placeholder="email@example.com"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="email"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <button>Submit</button>
    </form>
</x-shop::form>

<!-- Shimmer -->
<x-shop::shimmer.checkout.onepage.payment-method/>

<!-- tabs -->
<x-shop::tabs>
    <x-shop::tabs.item
        title="Tab 1"
    >
        Tab 1 Content
    </x-shop::tabs.item>

    <x-shop::tabs.item
        title="Tab 2"
    >
        Tab 2 Content
    </x-shop::tabs.item>
</x-shop::tabs>

<!-- accordion -->
<x-shop::accordion>
    <x-slot:header>
        Accordion Header
    </x-slot:header>

    <x-slot:content>
        Accordion Content
    </x-slot:content>
</x-shop::accordion>

<!-- modal -->
<x-shop::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot:toggle>

    <x-slot:header>
        Modal Header
    </x-slot:header>

    <x-slot:content>
        Modal Content
    </x-slot:content>
</x-shop::modal>

<!-- drawer -->
<x-shop::drawer>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot:toggle>

    <x-slot:header>
        Drawer Header
    </x-slot:header>

    <x-slot:content>
        Drawer Content
    </x-slot:content>
</x-shop::drawer>

<!-- dropdown -->
<x-shop::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot:toggle>

    <x-slot:content>
        Content
    </x-slot:content>
</x-shop::dropdown>

<!--Range Slider -->
<x-shop::range-slider
    ::key="refreshKey"
    default-type="price"
    ::default-allowed-max-range="allowedMaxPrice"
    ::default-min-range="minRange"
    ::default-max-range="maxRange"
    @change-range="setPriceRange($event)"
>
</x-shop::range-slider>

<!-- Image/Media -->
<x-shop::media.images.lazy
    class="min-w-[250px] relative after:content-[' '] after:block after:pb-[calc(100%+9px)] bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300"
    ::src="product.base_image.medium_image_url"
    ::key="product.id"
    ::index="product.id"
    width="291"
    height="300"
    ::alt="product.name"
>
</x-shop::media.images.lazy>

<!-- Page Title -->
<x-slot:title>
    @lang('Title')
</x-slot:title>

<!-- Page Layout -->
<x-shop::layouts>
   Page Content 
</x-shop::layouts>
