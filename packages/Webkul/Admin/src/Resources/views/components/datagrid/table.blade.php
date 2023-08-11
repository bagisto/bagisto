<div class="box-shadow relative w-full overflow-x-auto rounded-[4px] border">
    <x-admin::table>
        <x-admin::table.thead>
            <x-admin::table.thead.tr>
                <!-- Mass Actions -->
                <x-admin::table.th>
                    <label for="mass_action_select_all_records">
                        <input
                            type="checkbox"
                            name="mass_action_select_all_records"
                            id="mass_action_select_all_records"
                            class="peer hidden"
                            :checked="['all', 'partial'].includes(this.applied.massActions.meta.mode)"
                            @change="selectAllRecords"
                        >

                        <span
                            class="icon-uncheckbox cursor-pointer rounded-[6px] text-[24px]"
                            :class="[
                                this.applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-navyBlue' : (
                                    this.applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-navyBlue' : ''
                                ),
                            ]"
                        >
                        </span>
                    </label>
                </x-admin::table.th>

                <!-- Columns -->
                <x-admin::table.th
                    v-for="column in available.columns"
                    v-text="column.label"
                    class="cursor-pointer"
                    @click="sortPage(column)"
                >
                </x-admin::table.th>

                <!-- Actions -->
                <x-admin::table.th v-if="available.actions.length">
                    @lang('admin::app.components.datagrid.table.actions')
                </x-admin::table.th>
            </x-admin::table.thead.tr>
        </x-admin::table.thead>

        <x-admin::table.tbody>
            <x-admin::table.tbody.tr v-for="record in available.records">
                <!-- Mass Actions -->
                <x-admin::table.td>
                    <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                        <input
                            type="checkbox"
                            class="peer hidden"
                            :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                            :value="record[available.meta.primary_column]"
                            :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                            v-model="applied.massActions.indices"
                            @change="setCurrentSelectionMode"
                        >

                        <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-[6px] text-[24px] peer-checked:text-navyBlue">
                        </span>
                    </label>
                </x-admin::table.td>

                <!-- Columns -->
                <x-admin::table.td
                    v-for="column in available.columns"
                    v-text="record[column.index]"
                >
                </x-admin::table.td>

                <!-- Actions -->
                <x-admin::table.td class="flex gap-2">
                    <span
                        class="cursor-pointer rounded-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                        :class="action.icon"
                        v-text="!action.icon ? action.title : ''"
                        v-for="action in record.actions"
                        @click="performAction(action)"
                    >
                    </span>
                </x-admin::table.td>
            </x-admin::table.tbody.tr>
        </x-admin::table.tbody>
    </x-admin::table>
</div>
