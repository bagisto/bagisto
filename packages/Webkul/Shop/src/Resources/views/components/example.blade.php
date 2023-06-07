<x-accordion title="Test Accordion">
    <x-slot:header>Accordion Header</x-slot:header>

    <x-slot:body>Accordion Body</x-slot:body>
</x-accordion>

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

<x-tabs>
    <x-tabs.item
        name="Tab 1"
        is-selected="true"
    >
        Tab 1 Content
    </x-tabs.item>

    <x-tabs.item name="Tab 2">Tab 2 Content</x-tabs.item>
</x-tabs>

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

<?php $products = [1,2,3,3,4]  ?>

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

<x-form
    method="post"
    action=""
>
    <x-form.control-group>
        <x-form.control-group.label>
            Email
        </x-form.control-group.label>

        <x-form.control-group.control type="text" name="email" />

        <x-form.control-group.error>
            This is error
        </x-form.control-group.error>
    </x-form.control-group>
</x-form>

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

<x-shop::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot:toggle>

    <x-slot:content>
        Content
    </x-slot:content>
</x-shop::dropdown>
