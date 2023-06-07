<x-table>
    <x-table.thead>
        <x-table.tr>
            <x-table.th>Column 1</x-table.th>
            <x-table.th>Column 2</x-table.th>
            <x-table.th class="action">Column 3</x-table.th>
        </x-table.tr>
    </x-table.thead>

    <x-table.tbody>
        <x-table.tr>
            <x-table.td>Row 1 Value 1</x-table.td>
            <x-table.td>Row 1 Value 3</x-table.td>
            <x-table.td class="w-[50px]">Row 1 Value 3</x-table.td>
        </x-table.tr>

        <x-table.tr>
            <x-table.td>Row 2 Value 1</x-table.td>
            <x-table.td>Row 2 Value 3</x-table.td>
            <x-table.td class="action">Row 2 Value 3</x-table.td>
        </x-table.tr>
    </x-table.tbody>
</x-table>

<x-form.control>
    <x-slot:label class="required">
        Input Control
    </x-slot:label>

    <x-slot:control
        type="input"
        name="input"
        class="just-checking"
        value="Input Value"
        v-validate="'required'"
    ></x-slot:control>
</x-form.control>

<x-form.control>
    <x-slot:control
        type="select"
        name="select"
        v-validate="'required'"
    >
        <option value=""></option>
        <option value="1">Option 1</option>
    </x-slot:control>

    <x-slot:error class="custom-error-class"></x-slot:error>
</x-form.control>

<x-form.control>
    <x-slot:label
        class="required"
    >
        Multi Select Control
    </x-slot:label>

    <x-slot:control
        type="select"
        name="multiselect"
        v-validate="'required'"
        multiple
    >
        <option value="1" selected>Option 1</option>
        <option value="2">Option 2</option>
        <option value="3">Option 3</option>
    </x-slot:control>
</x-form.control>

<x-form.control>
    <x-slot:label class="required">Checkbox Control</x-slot:label>

    <x-slot:control
        type="checkbox"
        name="checkbox"
        value="1"
        checked
    >
        Checkbox Label
    </x-slot:control>
</x-form.control>

<x-form.control>
    <x-slot:label class="required">Radio Control</x-slot:label>

    <x-slot:control
        type="radio"
        name="radio[]"
        value="1"
    >
        Radio Label
    </x-slot:control>
</x-form.control>

<x-flash-group></x-flash-group>

<x-panel>
    <x-slot:header>Panel Title</x-slot:header>

    <x-slot:body>Panel Body</x-slot:body>
</x-panel>

{{-- default product listing --}}
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.products.index')"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

{{-- category product listing --}}
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.products.index', ['category_id' => 1])"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

{{-- featured product listing --}}
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.products.index', ['featured' => 1])"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

{{-- new product listing --}}
<x-shop::products.carousel
    title="Men's Collections"
    :src="route('shop.products.index', ['new' => 1])"
    :navigation-link="route('shop.home.index')"
>
</x-shop::products.carousel>

{{-- form  --}}
<x-shop::form
    :action="route('shop.customer.session.create')"
    class="rounded mt-[60px] max-sm:mt-[30px]"
>
    <x-shop::form.control-group class="mb-4">
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
</x-shop::form>

{{-- tabs --}}
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

{{-- accordion --}}
<x-shop::accordion>
    <x-slot:header>
        Accordion Header
    </x-slot:header>

    <x-slot:content>
        Accordion Content
    </x-slot:content>
</x-shop::accordion>

{{-- modal --}}
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

{{-- drawer --}}
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

{{-- dropdown --}}
<x-shop::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot:toggle>

    <x-slot:content>
        Content
    </x-slot:content>
</x-shop::dropdown>
