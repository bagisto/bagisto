<template>
    <div>
        <div class="dropdown-toggle">
            <div class="grid-dropdown-header">
                <span class="name" v-text="translations.filter"></span>
                <i class="icon arrow-down-icon active"></i>
            </div>
        </div>

        <div class="dropdown-list dropdown-container" style="display: none">
            <ul>
                <li>
                    <div class="control-group">
                        <select
                            class="filter-column-select control"
                            v-model="filterColumn"
                            v-on:change="getColumnOrAlias(filterColumn)"
                        >
                            <option
                                v-text="translations.column"
                                selected
                                disabled
                            ></option>

                            <option
                                :key="columnKey"
                                v-for="(column, columnKey) in columns"
                                :value="column.index"
                                v-text="column.label"
                                v-if="
                                    typeof column.filterable !== 'undefined' &&
                                        column.filterable
                                "
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="stringConditionSelect">
                    <div class="control-group">
                        <select class="control" v-model="stringCondition">
                            <option
                                v-text="translations.condition"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.contains"
                                value="like"
                            ></option>

                            <option
                                v-text="translations.ncontains"
                                value="nlike"
                            ></option>

                            <option
                                v-text="translations.equals"
                                value="eq"
                            ></option>

                            <option
                                v-text="translations.nequals"
                                value="neqs"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="stringCondition != null">
                    <div class="control-group">
                        <input
                            type="text"
                            class="control response-string"
                            :placeholder="translations.valueHere"
                            v-model="stringValue"
                        />
                    </div>
                </li>

                <li v-if="numberConditionSelect">
                    <div class="control-group">
                        <select class="control" v-model="numberCondition">
                            <option
                                v-text="translations.condition"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.equals"
                                value="eq"
                            ></option>

                            <option
                                v-text="translations.nequals"
                                value="neqs"
                            ></option>

                            <option
                                v-text="translations.greater"
                                value="gt"
                            ></option>

                            <option
                                v-text="translations.less"
                                value="lt"
                            ></option>

                            <option
                                v-text="translations.greatere"
                                value="gte"
                            ></option>

                            <option
                                v-text="translations.lesse"
                                value="lte"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="numberCondition != null">
                    <div class="control-group">
                        <input
                            type="text"
                            class="control response-number"
                            v-on:input="filterNumberInput"
                            v-model="numberValue"
                            :placeholder="translations.numericValueHere"
                        />
                    </div>
                </li>

                <li v-if="booleanConditionSelect">
                    <div class="control-group">
                        <select class="control" v-model="booleanCondition">
                            <option
                                v-text="translations.condition"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.equals"
                                value="eq"
                            ></option>

                            <option
                                v-text="translations.nequals"
                                value="neqs"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="booleanCondition != null">
                    <div class="control-group">
                        <select class="control" v-model="booleanValue">
                            <option
                                v-text="translations.value"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.true"
                                value="1"
                            ></option>

                            <option
                                v-text="translations.false"
                                value="0"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="dropdownConditionSelect">
                    <div class="control-group">
                        <select class="control" v-model="dropdownCondition">
                            <option
                                v-text="translations.condition"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.equals"
                                value="eq"
                            ></option>

                            <option
                                v-text="translations.nequals"
                                value="neqs"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="dropdownCondition != null">
                    <div class="control-group">
                        <select class="control" v-model="dropdownValue">
                            <option
                                :key="key"
                                v-text="option"
                                v-value="key"
                                v-for="(option,
                                key) in this.getCurrentFilterOptions()"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="checkboxConditionSelect">
                    <div class="control-group">
                        <select class="control" v-model="checkboxCondition">
                            <option
                                v-text="translations.condition"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.equals"
                                value="eq"
                            ></option>

                            <option
                                v-text="translations.nequals"
                                value="neqs"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="checkboxCondition != null">
                    <div class="control-group">
                        <button
                            style="width: 100%;"
                            type="button"
                            class="dropdown-btn"
                            @click="toggleCheckboxDropdown"
                        >
                            {{ translations.select }}

                            <i class="icon arrow-down-icon"></i>
                        </button>

                        <div
                            ref="checkboxOptions"
                            class="dropdown-list"
                            style="display: none;"
                        >
                            <div class="dropdown-container">
                                <ul>
                                    <li
                                        :key="key"
                                        v-for="(option,
                                        key) in this.getCurrentFilterOptions()"
                                    >
                                        <span class="checkbox">
                                            <input
                                                type="checkbox"
                                                v-model="checkboxValue"
                                                :value="option"
                                            />

                                            <label
                                                class="checkbox-view"
                                            ></label>

                                            {{ option }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li v-if="datetimeConditionSelect">
                    <div class="control-group">
                        <select class="control" v-model="datetimeCondition">
                            <option
                                v-text="translations.condition"
                                selected
                                disabled
                            ></option>

                            <option
                                v-text="translations.equals"
                                value="eq"
                            ></option>

                            <option
                                v-text="translations.nequals"
                                value="neqs"
                            ></option>

                            <option
                                v-text="translations.greater"
                                value="gt"
                            ></option>

                            <option
                                v-text="translations.less"
                                value="lt"
                            ></option>

                            <option
                                v-text="translations.greatere"
                                value="gte"
                            ></option>

                            <option
                                v-text="translations.lesse"
                                value="lte"
                            ></option>
                        </select>
                    </div>
                </li>

                <li v-if="datetimeCondition != null">
                    <div class="control-group">
                        <input
                            class="control"
                            v-model="datetimeValue"
                            type="date"
                        />
                    </div>
                </li>

                <button
                    v-text="translations.apply"
                    class="btn btn-sm btn-primary apply-filter"
                    v-on:click="getResponse"
                ></button>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['columns', 'translations'],

    data() {
        /**
         * To Do (@devansh-webkul):
         *
         * - Try to minimize these data props also.
         * - Refactor all repeative properties.
         */
        return {
            type: null,
            columnOrAlias: '',

            stringConditionSelect: false,
            numberConditionSelect: false,
            booleanConditionSelect: false,
            dropdownConditionSelect: false,
            checkboxConditionSelect: false,
            datetimeConditionSelect: false,

            stringCondition: null,
            numberCondition: null,
            booleanCondition: null,
            dropdownCondition: null,
            checkboxCondition: null,
            datetimeCondition: null,

            stringValue: null,
            numberValue: 0,
            booleanValue: null,
            dropdownValue: null,
            checkboxValue: [],
            datetimeValue: '2000-01-01'
        };
    },

    methods: {
        filterNumberInput: function(e) {
            this.numberValue = e.target.value.replace(/[^0-9\,\.]+/g, '');
        },

        getCurrentFilterColumn() {
            return this.columns.find(
                column => column.index === this.filterColumn
            );
        },

        isCurrentFilterColumnHasOptions() {
            let currentFilterColumn = this.getCurrentFilterColumn();

            return (
                currentFilterColumn && currentFilterColumn.options !== undefined
            );
        },

        getCurrentFilterOptions() {
            if (this.isCurrentFilterColumnHasOptions()) {
                return this.getCurrentFilterColumn().options ?? [];
            }

            throw 'Options are not defined.';
        },

        getColumnOrAlias(columnOrAlias) {
            this.columnOrAlias = columnOrAlias;

            for (let column in this.columns) {
                if (this.columns[column].index === this.columnOrAlias) {
                    this.type = this.columns[column].type;

                    switch (this.type) {
                        case 'string': {
                            this.stringConditionSelect = true;
                            this.numberConditionSelect = false;
                            this.booleanConditionSelect = false;
                            this.dropdownConditionSelect = false;
                            this.checkboxConditionSelect = false;
                            this.datetimeConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'number': {
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = true;
                            this.booleanConditionSelect = false;
                            this.dropdownConditionSelect = false;
                            this.checkboxConditionSelect = false;
                            this.datetimeConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'boolean': {
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = false;
                            this.booleanConditionSelect = true;
                            this.dropdownConditionSelect = false;
                            this.checkboxConditionSelect = false;
                            this.datetimeConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'dropdown': {
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = false;
                            this.booleanConditionSelect = false;
                            this.dropdownConditionSelect = true;
                            this.checkboxConditionSelect = false;
                            this.datetimeConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'checkbox': {
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = false;
                            this.booleanConditionSelect = false;
                            this.dropdownConditionSelect = false;
                            this.checkboxConditionSelect = true;
                            this.datetimeConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'datetime': {
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = false;
                            this.booleanConditionSelect = false;
                            this.dropdownConditionSelect = false;
                            this.checkboxConditionSelect = false;
                            this.datetimeConditionSelect = true;

                            this.nullify();
                            break;
                        }

                        case 'price': {
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = true;
                            this.booleanConditionSelect = false;
                            this.dropdownConditionSelect = false;
                            this.checkboxConditionSelect = false;
                            this.datetimeConditionSelect = false;

                            this.nullify();
                            break;
                        }
                    }
                }
            }
        },

        getResponse: function() {
            let label = '';

            for (let colIndex in this.columns) {
                if (this.columns[colIndex].index == this.columnOrAlias) {
                    label = this.columns[colIndex].label;
                    break;
                }
            }

            if (this.type === 'string' && this.stringValue !== null) {
                this.$emit('onFilter', {
                    data: {
                        column: this.columnOrAlias,
                        condition: this.stringCondition,
                        response: encodeURIComponent(this.stringValue),
                        label
                    }
                });
            } else if (this.type === 'number') {
                let indexConditions = true;

                if (
                    this.filterIndex === this.columnOrAlias &&
                    (this.numberValue === 0 || this.numberValue < 0)
                ) {
                    indexConditions = false;

                    alert(this.translations.zeroIndex);
                }

                if (indexConditions) {
                    this.$emit('onFilter', {
                        data: {
                            column: this.columnOrAlias,
                            condition: this.numberCondition,
                            response: this.numberValue,
                            label
                        }
                    });
                }
            } else if (this.type === 'boolean') {
                this.$emit('onFilter', {
                    data: {
                        column: this.columnOrAlias,
                        condition: this.booleanCondition,
                        response: this.booleanValue,
                        label
                    }
                });
            } else if (this.type === 'dropdown') {
                this.$emit('onFilter', {
                    data: {
                        column: this.columnOrAlias,
                        condition: this.dropdownCondition,
                        response: this.dropdownValue,
                        label
                    }
                });
            } else if (this.type === 'checkbox') {
                this.$emit('onFilter', {
                    data: {
                        column: this.columnOrAlias,
                        condition: this.checkboxCondition,
                        response: this.checkboxValue,
                        label
                    }
                });
            } else if (this.type === 'datetime') {
                this.$emit('onFilter', {
                    data: {
                        column: this.columnOrAlias,
                        condition: this.datetimeCondition,
                        response: this.datetimeValue,
                        label
                    }
                });
            } else if (this.type === 'price') {
                this.$emit('onFilter', {
                    data: {
                        column: this.columnOrAlias,
                        condition: this.numberCondition,
                        response: this.numberValue,
                        label
                    }
                });
            }
        },

        nullify() {
            this.stringCondition = null;
            this.datetimeCondition = null;
            this.booleanCondition = null;
            this.dropdownCondition = null;
            this.numberCondition = null;
        },

        toggleCheckboxDropdown() {
            const display = this.$refs.checkboxOptions.style.display;

            if (display === 'none') {
                this.$refs.checkboxOptions.style.display = 'block';
                return;
            }

            this.$refs.checkboxOptions.style.display = 'none';
        }
    }
};
</script>
