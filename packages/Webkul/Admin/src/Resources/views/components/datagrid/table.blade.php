<div>
    <div class="relative overflow-x-auto border rounded-[4px] box-shadow">
        <x-admin::table>
            <x-admin::table.thead>
                <x-admin::table.thead.tr>
                    <!-- Columns -->
                    <x-admin::table.th
                        v-for="column in available.columns"
                        v-text="column.label"
                        @click="sortPage(column)"
                    >
                    </x-admin::table.th>

                    <!-- Actions -->
                    <x-admin::table.th v-if="available.actions.length">
                        Actions
                    </x-admin::table.th>
                </x-admin::table.thead.tr>
            </x-admin::table.thead>

            <x-admin::table.tbody>
                <x-admin::table.tbody.tr
                    v-for="record in available.records"
                >
                    <!-- Columns -->
                    <x-admin::table.td
                        v-for="column in available.columns"
                        v-text="record[column.index]"
                    >
                    </x-admin::table.td>

                    <!-- Actions -->
                    <x-admin::table.td
                        v-for="action in record.actions"
                    >
                        <a
                            href="javascript:void(0);"
                            v-text="action.title"
                            @click="performAction(action)"
                        >
                        </a>
                    </x-admin::table.td>
                </x-admin::table.tbody.tr>
            </x-admin::table.tbody>
        </x-admin::table>
    </div>
</div>
