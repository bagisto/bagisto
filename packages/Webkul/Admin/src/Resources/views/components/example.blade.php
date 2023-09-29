{{--Flash message --}}
<x-flash-group></x-flash-group>

<x-panel>
    <x-slot:header>Panel Title</x-slot:header>

    <x-slot:body>Panel Body</x-slot:body>
</x-panel>

{{-- Form Control Group --}}

{{-- Type Text --}}
<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        @lang('name')
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="text"
        name="name"
        :value=""
        rules="required"
        label="name"
        placeholder="name"
    >
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.error
        control-name="name"
    >
    </x-admin::form.control-group.error>
</x-admin::form.control-group>

{{-- Type Select --}}
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        @lang('admin::app.catalog.families.create.column')
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="select"
        name="column"
        rules="required"
        :label="trans('admin::app.catalog.families.create.column')"
    >
        <!-- Default Option -->
        <option value="">
            @lang('admin::app.catalog.families.create.select-group')
        </option>

        <option value="1">
            @lang('admin::app.catalog.families.create.main-column')
        </option>

        <option value="2">
            @lang('admin::app.catalog.families.create.right-column')
        </option>
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.error 
        control-name="column"
    >
    </x-admin::form.control-group.error>
</x-admin::form.control-group>

{{--Type Checkbox --}}
<x-admin::form.control-group>
    <x-admin::form.control-group.control
        type="checkbox"
        name="is_unique"
        id="is_unique"
        for="is_unique"
        value="1"
    >
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.label
        for="is_unique"
    >
        @lang('admin::app.catalog.attributes.edit.is-unique')
    </x-admin::form.control-group.label>
</x-admin::form.control-group>

{{--Type Radio --}}
<x-admin::form.control-group>
    <x-admin::form.control-group.control
        type="radio"
        name="is_unique"
        id="is_unique"
        for="is_unique"
        value="1"
    >
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.label
        for="is_unique"
    >
        @lang('admin::app.catalog.attributes.edit.is-unique')
    </x-admin::form.control-group.label>
</x-admin::form.control-group>

{{-- basic/traditional form  --}}
<x-admin::form action="">
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>
            Email
        </x-admin::form.control-group.label>

        <x-admin::form.control-group.control
            type="email"
            name="email"
            value=""
            rules="required|email"
            label="Email"
            placeholder="email@example.com"
        >
        </x-admin::form.control-group.control>

        <x-admin::form.control-group.error
            control-name="email"
        >
        </x-admin::form.control-group.error>
    </x-admin::form.control-group>
</x-admin::form>

{{-- customized/ajax form --}}
<x-admin::form
    v-slot="{ meta, errors, handleSubmit }"
    as="div"
>
    <form @submit="handleSubmit($event, callMethodInComponent)">
        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                Email
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="email"
                name="email"
                :value="old('email')"
                rules="required"
                label="Email"
                placeholder="email@example.com"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error
                control-name="email"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <button>Submit</button>
    </form>
</x-admin::form>

{{-- Accordion Component --}}
<x-admin::accordion title="Test Accordion">
    <x-slot:header>
        Accordion Header
    </x-slot:header>

    <x-slot:content>
        Accordion Content
    </x-slot:content>
</x-admin::accordion>

{{-- Modal Component --}}
<x-admin::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot:toggle>

    <x-slot:header>
        Modal Header
    </x-slot:header>

    <x-slot:content>
        Modal Content
    </x-slot:content>
</x-admin::modal>

{{-- Drawer Component --}}
<x-admin::drawer>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot:toggle>

    <x-slot:header>
        Drawer Header
    </x-slot:header>

    <x-slot:content>
        Drawer Content
    </x-slot:content>
</x-admin::drawer>

{{-- Dropdown Component--}}
<x-admin::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot:toggle>

    <x-slot:content>
        Content
    </x-slot:content>
</x-admin::dropdown>

{{-- Tinymce Component --}}
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Content
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="textarea"
        name="html_content"
        :value="old('html_content')"
        id="content"
        rules="required"
        label="Content"
        placeholder="Content"
        :tinymce="true"
    >
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.error
        control-name="html_content"
    >
    </x-admin::form.control-group.error>
</x-admin::form.control-group>

{{-- SEO Title & Description Blade Componnet --}}
<x-admin::seo/>

{{-- Star Rating Component --}}
<x-admin::star-rating 
    :is-editable="false"
    :value="$review->rating"
>
</x-admin::star-rating>

{{-- Exportdatagrid Component--}}
<x-admin::datagrid.export 
    src=""
>
</x-admin::datagrid.export>

{{-- Datagrid Component --}}
<x-admin::datagrid 
    :src="route('admin.sales.orders.index')" 
    :isMultiRow="true"
>
</x-admin::datagrid>

{{-- Image Blade Component --}}
<x-admin::media.images
    name="images[files]"
    allow-multiple="true"
    show-placeholders="true"
    :uploaded-images="$product->images"
>
</x-admin::media.images>

{{-- Video Blade Component --}}
<x-admin::media.videos
    name="videos[files]"
    :allow-multiple="true"
    :uploaded-videos="$product->videos"
>
</x-admin::media.videos>

{{-- Tree Component --}}

<x-admin::tree.view
    input-type="checkbox"
    name-field="categories"
    id-field="id"
    value-field="id"
    ::items="categories"
    :value="json_encode($product->categories->pluck('id'))"
    behavior="no"
    :fallback-locale="config('app.fallback_locale')"
>
</x-admin::tree.view>