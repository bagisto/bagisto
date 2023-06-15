<template>
    <table class="table">
        <thead>
            <tr style="height: 65px">
                <th
                    v-if="enableMassActions"
                    class="grid_head"
                    id="mastercheckbox"
                    style="width: 50px"
                >
                    <span class="checkbox">
                        <input
                            type="checkbox"
                            v-model="allSelected"
                            v-on:change="selectAll"
                            :disabled="!records.data.length"
                        />

                        <label class="checkbox-view" for="checkbox"></label>
                    </span>
                </th>

                <th
                    :key="columnKey"
                    v-for="(column, columnKey) in columns"
                    v-if ="column.visibility != false"
                    v-text="column.label"
                    class="grid_head"
                    :class="{ sortable: column.sortable }"
                    :style="
                        typeof column.width !== 'undefined' && column.width
                            ? `width: ${column.width}`
                            : ''
                    "
                    v-on:click="
                        typeof column.sortable !== 'undefined' &&
                        column.sortable
                            ? sortCollection(column.index)
                            : {}
                    "
                ></th>

                <th v-if="enableActions" v-text="translations.actions"></th>
            </tr>
        </thead>

        <tbody>
            <template v-if="records.data.length">
                <tr
                    :key="recordKey"
                    v-for="(record, recordKey) in records.data"
                >
                    <td v-if="enableMassActions">
                        <span class="checkbox">
                            <input
                                type="checkbox"
                                v-model="dataIds"
                                @change="select"
                                :value="record[index]"
                            />

                            <label class="checkbox-view" for="checkbox"></label>
                        </span>
                    </td>

                    <td
                        :key="columnKey"
                        v-for="(column, columnKey) in columns"
                        v-if ="column.visibility != false"
                        v-html="record[column.index]"
                        :data-value="column.label"
                    ></td>

                    <td
                        class="actions"
                        style="white-space: nowrap; width: 100px"
                        v-if="enableActions"
                        :data-value="translations.actions"
                    >
                        <div class="action">
                            <a
                                :key="actionIndex"
                                v-for="(action, actionIndex) in actions"
                                v-if="record[`${action.key}_to_display`]"
                                :id="
                                    record[
                                        typeof action.index !== 'undefined' &&
                                        action.index
                                            ? action.index
                                            : index
                                    ]
                                "
                                :href="
                                    action.method == 'GET'
                                        ? record[`${action.key}_url`]
                                        : 'javascript:void(0);'
                                "
                                v-on:click="
                                    action.method != 'GET'
                                        ? doAction($event)
                                        : {}
                                "
                                :data-method="action.method"
                                :data-action="record[`${action.key}_url`]"
                                :data-token="getCsrf()"
                                :target="
                                    typeof action.target !== 'undefined' &&
                                    action.target
                                        ? action.target
                                        : ''
                                "
                                :title="
                                    typeof action.title !== 'undefined' &&
                                    action.title
                                        ? action.title
                                        : ''
                                "
                            >
                                <span :class="action.icon"></span>
                            </a>
                        </div>
                    </td>
                </tr>
            </template>

            <template v-else>
                <tr>
                    <td colspan="10">
                        <p
                            style="text-align: center"
                            v-text="translations.norecords"
                        ></p>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script>
export default {
    props: [
        'actions',
        'columns',
        'enableActions',
        'enableMassActions',
        'index',
        'massActions',
        'records',
        'translations'
    ],

    data() {
        return {
            allSelected: false,
            dataIds: [],
            gridCurrentData: this.records,
            massActionTarget: null,
            massActionsToggle: false,
            massActionType: this.getDefaultMassActionType(),
            itemSelected: 0
        };
    },

    methods: {
        select(dataIds) {
            this.allSelected = false;

            if (this.dataIds.length === 0) {
                this.massActionsToggle = false;
                this.massActionType = this.getDefaultMassActionType();
            } else {
                this.massActionsToggle = true;
            }

            this.itemSelected = this.dataIds.length;

            this.$emit('onSelect', [dataIds, this.massActionsToggle, this.itemSelected]);
        },

        selectAll() {
            this.dataIds = [];

            this.massActionsToggle = true;

            if (this.allSelected) {
                if (this.gridCurrentData.hasOwnProperty('data')) {
                    for (let currentData in this.gridCurrentData.data) {
                        let i = 0;
                        for (let currentId in this.gridCurrentData.data[
                            currentData
                        ]) {
                            if (i == 0) {
                                this.dataIds.push(
                                    this.gridCurrentData.data[currentData][
                                        this.index
                                    ]
                                );
                            }

                            i++;
                        }
                    }
                } else {
                    for (let currentData in this.gridCurrentData) {
                        let i = 0;
                        for (let currentId in this.gridCurrentData[
                            currentData
                        ]) {
                            if (i === 0)
                                this.dataIds.push(
                                    this.gridCurrentData[currentData][currentId]
                                );

                            i++;
                        }
                    }
                }
            } else {
                this.removeMassActions();
            }

            this.itemSelected = this.dataIds.length;

            this.$emit('onSelectAll', [this.dataIds, this.massActionsToggle, this.itemSelected]);
        },

        getDefaultMassActionType: function() {
            return {
                id: null,
                value: null
            };
        },

        removeMassActions() {
            this.dataIds = [];

            this.massActionsToggle = false;

            this.allSelected = false;

            this.massActionType = this.getDefaultMassActionType();

            this.$emit('onSelectAll', this.massActionsToggle);
        },

        sortCollection(alias) {
            let label = '';

            for (let colIndex in this.columns) {
                if (this.columns[colIndex].index === alias) {
                    label = this.columns[colIndex].label;
                    break;
                }
            }

            this.$emit('onSorting', {
                data: {
                    column: 'sort',
                    condition: alias,
                    response: 'asc',
                    label
                }
            });
        },

        doAction(e, message, type) {
            let self = this;

            let element = e.currentTarget;

            if (message) {
                element = e.target.parentElement;
            }

            message = message || this.translations.massActionDelete;

            if (confirm(message)) {
                axios
                    .post(element.getAttribute('data-action'), {
                        _token: element.getAttribute('data-token'),
                        _method: element.getAttribute('data-method')
                    })
                    .then(function(response) {
                        const { data } = response;

                        /**
                         * If refirect is true, then pass redirect url in the response.
                         *
                         * Else, it will reload table only.
                         */
                        if (data.redirect !== undefined && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            self.$emit('onActionSuccess');

                            window.flashMessages.push({
                                type: 'alert-success',
                                message: data.message
                            });

                            self.$root.addFlashMessages();
                        }
                    })
                    .catch(function(error) {
                        const { response } = error;

                        window.flashMessages.push({
                            type: 'alert-error',
                            message:
                                response.data.message ?? 'Something went wrong!'
                        });

                        self.$root.addFlashMessages();
                    });

                e.preventDefault();
            } else {
                e.preventDefault();
            }
        }
    }
};
</script>
