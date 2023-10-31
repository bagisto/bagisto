{{--Flash message --}}
<x-flash-group></x-flash-group>

<x-panel>
    <x-slot:header>Panel Title</x-slot:header>

    <x-slot:body>Panel Body</x-slot:body>
</x-panel>

{{-- Form Control Group --}}

{{-- Type Text --}}
<x-booking::form.control-group>
    <x-booking::form.control-group.label class="required">
        @lang('name')
    </x-booking::form.control-group.label>

    <x-booking::form.control-group.control
        type="text"
        name="name"
        :value=""
        rules="required"
        label="name"
        placeholder="name"
    >
    </x-booking::form.control-group.control>

    <x-booking::form.control-group.error
        control-name="name"
    >
    </x-booking::form.control-group.error>
</x-booking::form.control-group>

{{-- Type Select --}}
<x-booking::form.control-group>
    <x-booking::form.control-group.label>
        @lang('admin::app.catalog.families.create.column')
    </x-booking::form.control-group.label>

    <x-booking::form.control-group.control
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
    </x-booking::form.control-group.control>

    <x-booking::form.control-group.error 
        control-name="column"
    >
    </x-booking::form.control-group.error>
</x-booking::form.control-group>

{{--Type Checkbox --}}
<x-booking::form.control-group>
    <x-booking::form.control-group.control
        type="checkbox"
        name="is_unique"
        id="is_unique"
        for="is_unique"
        value="1"
    >
    </x-booking::form.control-group.control>

    <x-booking::form.control-group.label
        for="is_unique"
    >
        @lang('admin::app.catalog.attributes.edit.is-unique')
    </x-booking::form.control-group.label>
</x-booking::form.control-group>

{{--Type Radio --}}
<x-booking::form.control-group>
    <x-booking::form.control-group.control
        type="radio"
        name="is_unique"
        id="is_unique"
        for="is_unique"
        value="1"
    >
    </x-booking::form.control-group.control>

    <x-booking::form.control-group.label
        for="is_unique"
    >
        @lang('admin::app.catalog.attributes.edit.is-unique')
    </x-booking::form.control-group.label>
</x-booking::form.control-group>

{{-- basic/traditional form  --}}
<x-booking::form action="">
    <x-booking::form.control-group>
        <x-booking::form.control-group.label>
            Email
        </x-booking::form.control-group.label>

        <x-booking::form.control-group.control
            type="email"
            name="email"
            value=""
            rules="required|email"
            label="Email"
            placeholder="email@example.com"
        >
        </x-booking::form.control-group.control>

        <x-booking::form.control-group.error
            control-name="email"
        >
        </x-booking::form.control-group.error>
    </x-booking::form.control-group>
</x-booking::form>

{{-- customized/ajax form --}}
<x-booking::form
    v-slot="{ meta, errors, handleSubmit }"
    as="div"
>
    <form @submit="handleSubmit($event, callMethodInComponent)">
        <x-booking::form.control-group>
            <x-booking::form.control-group.label>
                Email
            </x-booking::form.control-group.label>

            <x-booking::form.control-group.control
                type="email"
                name="email"
                :value="old('email')"
                rules="required"
                label="Email"
                placeholder="email@example.com"
            >
            </x-booking::form.control-group.control>

            <x-booking::form.control-group.error
                control-name="email"
            >
            </x-booking::form.control-group.error>
        </x-booking::form.control-group>

        <button>Submit</button>
    </form>
</x-booking::form>

{{-- Modal Component --}}
<x-booking::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot:toggle>

    <x-slot:header>
        Modal Header
    </x-slot:header>

    <x-slot:content>
        Modal Content
    </x-slot:content>
</x-booking::modal>

{{-- Drawer Component --}}
<x-booking::drawer>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot:toggle>

    <x-slot:header>
        Drawer Header
    </x-slot:header>

    <x-slot:content>
        Drawer Content
    </x-slot:content>
</x-booking::drawer>

{{-- Tinymce Component --}}
<x-booking::form.control-group>
    <x-booking::form.control-group.label>
        Content
    </x-booking::form.control-group.label>

    <x-booking::form.control-group.control
        type="textarea"
        name="html_content"
        :value="old('html_content')"
        id="content"
        rules="required"
        label="Content"
        placeholder="Content"
        :tinymce="true"
    >
    </x-booking::form.control-group.control>

    <x-booking::form.control-group.error
        control-name="html_content"
    >
    </x-booking::form.control-group.error>
</x-booking::form.control-group>

{{-- Exportdatagrid Component--}}
<x-booking::datagrid.export 
    src=""
>
</x-booking::datagrid.export>

{{-- Datagrid Component --}}
<x-booking::datagrid 
    :src="route('admin.sales.orders.index')" 
    :isMultiRow="true"
>
</x-booking::datagrid>

{{-- Image Blade Component --}}
<x-booking::media.images
    name="images[files]"
    allow-multiple="true"
    show-placeholders="true"
    :uploaded-images="$product->images"
>
</x-booking::media.images>
