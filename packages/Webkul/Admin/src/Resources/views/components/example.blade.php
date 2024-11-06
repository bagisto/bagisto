<!-- Accordion Component -->
<x-admin::accordion title="Test Accordion">
    <x-slot:header>
        Accordion Header
    </x-slot>

    <x-slot:content>
        Accordion Content
    </x-slot>
</x-admin::accordion>

<!--
    Chart | Bar Chart Component

    Note: To use charts, you need to require the Chart.js library.
-->
<x-admin::charts.bar
    ::labels="chartLabels"
    ::datasets="chartDatasets"
/>

<!--
    Chart | Line Chart Component

    Note: To use charts, you need to require the Chart.js library.
-->
<x-admin::charts.line
    ::labels="chartLabels"
    ::datasets="chartDatasets"
/>

<!-- Datagrid Component -->
<x-admin::datagrid
    :src="route('admin.sales.orders.index')"
/>

<!-- Datagrid | Export Component-->
<x-admin::datagrid.export
    :src="route('admin.sales.orders.index')"
/>

<!-- Drawer Component -->
<x-admin::drawer>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot>

    <x-slot:header>
        Drawer Header
    </x-slot>

    <x-slot:content>
        Drawer Content
    </x-slot>
</x-admin::drawer>

<!-- Dropdown Component-->
<x-admin::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot>

    <x-slot:content>
        Content
    </x-slot>
</x-admin::dropdown>

<!-- Flash Group Component-->
<x-admin::flash-group />

<!-- Flat Picker | Date Component -->
<x-admin::flat-picker.date ::allow-input="false">
    <input
        value=""
        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
        type="date"
        name="created_at"
        placeholder="Created At"
        @change=""
    />
</x-admin::flat-picker.date>

<!-- Flat Picker | Datetime Component -->
<x-admin::flat-picker.datetime ::allow-input="false">
    <input
        value=""
        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
        type="datetime-local"
        name="created_at"
        placeholder="Created At"
        @change=""
    />
</x-admin::flat-picker.datetime>

<!-- Form Control Group | Text Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        Name
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="text"
        name="name"
        rules="required"
        :value=""
        label="Name"
        placeholder="Name"
    />

    <x-admin::form.control-group.error control-name="name" />
</x-admin::form.control-group>

<!-- Form Control Group | Price Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        Price
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="price"
        name="price"
        value="2.00"
        rules="required"
        label="Price"
        placeholder="Price"
    />

    <x-admin::form.control-group.error control-name="price" />
</x-admin::form.control-group>

<!-- Form Control Group | File Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        File Upload
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="file"
        id="file"
        name="file"
    />

    <x-admin::form.control-group.error control-name="file" />
</x-admin::form.control-group>

<!-- Form Control Group | Color Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Color
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="color"
        name="color"
        placeholder="Color"
    />

    <x-admin::form.control-group.error control-name="color" />
</x-admin::form.control-group>

<!-- Form Control Group | Textarea Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Description
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="textarea"
        name="description"
        value=""
        label="Description"
    />

    <x-admin::form.control-group.error control-name="description" />
</x-admin::form.control-group>

<!-- Form Control Group | Date Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Date Of Birth
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="date"
        name="date_of_birth"
        label="Date Of Birth"
        placeholder="Date Of Birth"
    />

    <x-admin::form.control-group.error control-name="date_of_birth" />
</x-admin::form.control-group>

<!-- Form Control Group | Datetime Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Date Of Birth
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="datetime"
        name="date_of_birth"
        label="Date Of Birth"
        placeholder="Date Of Birth"
    />

    <x-admin::form.control-group.error control-name="date_of_birth" />
</x-admin::form.control-group>

<!-- Form Control Group | Time Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        Time
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="time"
        name="time"
        placeholder="Time"
        rules="required"
        label="Time"
    />

    <x-admin::form.control-group.error control-name="time" />
</x-admin::form.control-group>

<!-- Form Control Group | Select Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Customer Group
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="select"
        name="group"
        rules="required"
        label="Customer Group"
    >
        <!-- Default Option -->
        <option value="0">
            Guest
        </option>

        <option value="1">
            Customer
        </option>

        <option value="2">
            Wholesaler
        </option>
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.error control-name="group" />
</x-admin::form.control-group>

<!-- Form Control Group | Multiselect Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Customer Groups
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="multiselect"
        name="groups"
        rules="required"
        label="Customer Group"
    >
        <!-- Default Option -->
        <option value="0">
            Guest
        </option>

        <option value="1">
            Customer
        </option>

        <option value="2">
            Wholesaler
        </option>
    </x-admin::form.control-group.control>

    <x-admin::form.control-group.error control-name="groups" />
</x-admin::form.control-group>

<!-- Form Control Group | Checkbox Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.control
        type="checkbox"
        id="is_unique"
        name="is_unique"
        value="1"
        for="is_unique"
    />

    <x-admin::form.control-group.label
        for="is_unique"
    >
        Is Unique
    </x-admin::form.control-group.label>
</x-admin::form.control-group>

<!-- Form Control Group | Radio Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.control
        type="radio"
        id="is_unique"
        name="is_unique"
        value="1"
        for="is_unique"
    />

    <x-admin::form.control-group.label
        for="is_unique"
    >
        Is Unique
    </x-admin::form.control-group.label>
</x-admin::form.control-group>

<!-- Form Control Group | Switch Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Status
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="switch"
        class="cursor-pointer"
        name="status"
        value="1"
        label="Status"
    />

    <x-admin::form.control-group.error control-name="status" />
</x-admin::form.control-group>

<!-- Form Control Group | Image Type Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Slider Image
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="image"
        name="slider_image"
        rules="required"
        :is-multiple="false"
    />

    <x-admin::form.control-group.error control-name="slider_image" />
</x-admin::form.control-group>

<!-- Form | Basic/Traditional Component  -->
<x-admin::form action="">
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>
            Email
        </x-admin::form.control-group.label>

        <x-admin::form.control-group.control
            type="email"
            name="email"
            rules="required|email"
            value=""
            label="Email"
            placeholder="email@example.com"
        />

        <x-admin::form.control-group.error control-name="email" />
    </x-admin::form.control-group>
</x-admin::form>

<!-- Form | Customized/Ajax Component -->
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
                rules="required"
                :value="old('email')"
                label="Email"
                placeholder="email@example.com"
            />

            <x-admin::form.control-group.error control-name="email" />
        </x-admin::form.control-group>

        <button>Submit</button>
    </form>
</x-admin::form>

<!-- Media | Image Component -->
<x-admin::media.images
    name="images[files]"
    allow-multiple="true"
    show-placeholders="true"
    :uploaded-images="$product->images"
/>

<!-- Media | Video Component -->
<x-admin::media.videos
    name="videos[files]"
    :allow-multiple="true"
    :uploaded-videos="$product->videos"
/>

<!-- Modal Component -->
<x-admin::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot>

    <x-slot:header>
        Modal Header
    </x-slot>

    <x-slot:content>
        Modal Content
    </x-slot>
</x-admin::modal>

<!-- SEO Componnet -->
<x-admin::seo />

<!-- Star Rating Component -->
<x-admin::star-rating
    :is-editable="false"
    :value="$review->rating"
/>

<!-- Table Component -->
<x-admin::table>
    <x-admin::table.thead>
        <x-admin::table.thead.tr>
            <x-admin::table.th>
                Heading 1
            </x-admin::table.th>

            <x-admin::table.th>
                Heading 2
            </x-admin::table.th>

            <x-admin::table.th>
                Heading 3
            </x-admin::table.th>

            <x-admin::table.th>
                Heading 4
            </x-admin::table.th>
        </x-admin::table.thead.tr>
    </x-admin::table.thead>

    <x-admin::table.tbody>
        <x-admin::table.tbody.tr>
            <x-admin::table.td>
                Column 1
            </x-admin::table.td>

            <x-admin::table.td>
                Column 2
            </x-admin::table.td>

            <x-admin::table.td>
                Column 3
            </x-admin::table.td>

            <x-admin::table.td>
                Column 4
            </x-admin::table.td>
        </x-admin::table.thead.tr>
    </x-admin::table.tbody>
</x-admin::table>

<!-- Tabs Component -->
<x-admin::tabs>
    <x-admin::tabs.item title="Tab 1">
        Tab 1 Content
    </x-admin::tabs.item>

    <x-admin::tabs.item title="Tab 2">
        Tab 2 Content
    </x-admin::tabs.item>
</x-admin::tabs>

<!-- Tinymce Component -->
<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        Content
    </x-admin::form.control-group.label>

    <x-admin::form.control-group.control
        type="textarea"
        id="content"
        name="html_content"
        rules="required"
        :value="old('html_content')"
        label="Content"
        placeholder="Content"
        :tinymce="true"
    />

    <x-admin::form.control-group.error control-name="html_content" />
</x-admin::form.control-group>

<!-- Tree | Checkbox Individual Component -->
<x-admin::tree.view
    input-type="checkbox"
    selection-type="hierarchical"
    name-field="parent_id"
    value-field="key"
    id-field="key"
    :items="json_encode($availableItems)"
    :value="json_encode($savedValues)"
    :fallback-locale="config('app.fallback_locale')"
/>

<!-- Tree | Checkbox Hierarchical Component -->
<x-admin::tree.view
    input-type="checkbox"
    selection-type="hierarchical"
    name-field="parent_id"
    value-field="key"
    id-field="key"
    :items="json_encode($availableItems)"
    :value="json_encode($savedValues)"
    :fallback-locale="config('app.fallback_locale')"
/>

<!-- Tree | Radio Component -->
<x-admin::tree.view
    input-type="radio"
    name-field="parent_id"
    value-field="id"
    id-field="id"
    :items="json_encode($availableItems)"
    :value="$savedValue"
    :fallback-locale="config('app.fallback_locale')"
/>

<!-- Status  Label -->
<div class="label-canceled">Canceled</div>

<div class="label-info">Information</div>

<div class="label-completed">Completed</div>

<div class="label-closed">Closed</div>

<div class="label-processing">Processing</div>

<div class="label-pending">Pending</div>
