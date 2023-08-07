<div>
    <div class="relative overflow-x-auto border rounded-[4px] box-shadow">
        <x-admin::table>
            <x-admin::table.thead>
                <x-admin::table.tr>
                    <x-admin::table.th
                        v-for="column in available.columns"
                        v-text="column.label"
                        @click="sortPage(column)"
                    >
                    </x-admin::table.th>
                </x-admin::table.tr>
            </x-admin::table.thead>

            <x-admin::table.tbody>
                <x-admin::table.tr
                    v-for="record in available.records"
                >
                    <x-admin::table.td
                        v-for="column in available.columns"
                        v-text="record[column.index]"
                    >
                    </x-admin::table.td>
                </x-admin::table.tr>
            </x-admin::table.tbody>
        </x-admin::table>
    </div>
</div>
