<template>
    <table class="table">
        <thead v-if="massActionsToggle">
            <tr
                class="mass-action"
                v-if="massActionsToggle"
                style="height: 65px"
            >
                <th colspan="100%">
                    <div
                        class="mass-action-wrapper"
                        style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;"
                    >
                        <span
                            class="massaction-remove"
                            v-on:click="removeMassActions"
                            style="margin-right: 10px; margin-top: 5px"
                        >
                            <span class="icon checkbox-dash-icon"></span>
                        </span>

                        <form
                            method="POST"
                            id="mass-action-form"
                            style="display: inline-flex"
                            action=""
                            :onsubmit="
                                `return confirm('${massActionConfirmText}')`
                            "
                        >
                            <input
                                type="hidden"
                                name="_token"
                                :value="getCsrf()"
                            />

                            <input
                                type="hidden"
                                id="indexes"
                                name="indexes"
                                v-model="dataIds"
                            />

                            <div class="control-group">
                                <select
                                    class="control"
                                    v-model="massActionType"
                                    @change="changeMassActionTarget"
                                    name="mass-action-type"
                                    required
                                >
                                    <option
                                        v-for="(massAction,
                                        index) in massActions"
                                        v-text="massAction.label"
                                        :key="index"
                                        :value="{
                                            id: index,
                                            value: massAction.type
                                        }"
                                    ></option>
                                </select>
                            </div>

                            <div
                                class="control-group"
                                style="margin-left: 10px"
                                v-if="massActionType.value == 'update'"
                            >
                                <select
                                    class="control"
                                    v-model="massActionUpdateValue"
                                    name="update-options"
                                    required
                                >
                                    <option
                                        :key="id"
                                        v-for="(massActionValue,
                                        id) in massActionValues"
                                        :value="massActionValue"
                                        v-text="id"
                                    ></option>
                                </select>
                            </div>

                            <button
                                v-text="translations.submit"
                                type="submit"
                                class="btn btn-sm btn-primary"
                                style="margin-left: 10px; white-space: nowrap;"
                            ></button>
                        </form>
                    </div>
                </th>
            </tr>
        </thead>

        <thead v-if="massActionsToggle == false">
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
        'massActionTargets',
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
            massActionUpdateValue: null,
            massActionValues: [],
            massActionConfirmText: this.translations.clickOnAction
        };
    },

    methods: {
        select() {
            this.allSelected = false;

            if (this.dataIds.length === 0) {
                this.massActionsToggle = false;
                this.massActionType = this.getDefaultMassActionType();
            } else {
                this.massActionsToggle = true;
            }
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
            }
        },

        getDefaultMassActionType: function() {
            return {
                id: null,
                value: null
            };
        },

        changeMassActionTarget: function() {
            if (this.massActionType.value === 'delete') {
                for (let i in this.massActionTargets) {
                    if (this.massActionTargets[i].type === 'delete') {
                        this.massActionTarget = this.massActionTargets[
                            i
                        ].action;
                        this.massActionConfirmText = this.massActionTargets[i]
                            .confirm_text
                            ? this.massActionTargets[i].confirm_text
                            : this.massActionConfirmText;

                        break;
                    }
                }
            }

            if (this.massActionType.value === 'update') {
                for (let i in this.massActionTargets) {
                    if (this.massActionTargets[i].type === 'update') {
                        this.massActionValues = this.massActions[
                            this.massActionType.id
                        ].options;
                        this.massActionTarget = this.massActionTargets[
                            i
                        ].action;
                        this.massActionConfirmText = this.massActionTargets[i]
                            .confirm_text
                            ? this.massActionTargets[i].confirm_text
                            : this.massActionConfirmText;

                        break;
                    }
                }
            }

            document.getElementById(
                'mass-action-form'
            ).action = this.massActionTarget;
        },

        removeMassActions() {
            this.dataIds = [];

            this.massActionsToggle = false;

            this.allSelected = false;

            this.massActionType = this.getDefaultMassActionType();
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
