<template>
    <div>
        <div class="dropdown-toggle">
            <div class="grid-dropdown-header">
                <span class="name" v-text="translations.filter"></span>
                <i class="icon arrow-down-icon active"></i>
            </div>
        </div>

        <div
            class="dropdown-list dropdown-container"
            style="display: none;"
            v-on:keyup.enter="getResponse"
        >
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
                                value="null"
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

                <li v-if="types.string.isSelected">
                    <div class="control-group">
                        <select
                            class="control"
                            v-model="types.string.condition"
                        >
                            <option
                                v-text="translations.condition"
                                selected
                                value="null"
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

                <li v-if="types.string.condition != null">
                    <div class="control-group">
                        <input
                            type="text"
                            class="control response-string"
                            :placeholder="translations.valueHere"
                            v-model="types.string.value"
                        />
                    </div>
                </li>

                <li v-if="types.number.isSelected">
                    <div class="control-group">
                        <select
                            class="control"
                            v-model="types.number.condition"
                        >
                            <option
                                v-text="translations.condition"
                                selected
                                value="null"
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

                <li v-if="types.number.condition != null">
                    <div class="control-group">
                        <input
                            type="text"
                            class="control response-number"
                            v-on:input="filterNumberInput"
                            v-model="types.number.value"
                            :placeholder="translations.numericValueHere"
                        />
                    </div>
                </li>

                <li v-if="types.boolean.isSelected">
                    <div class="control-group">
                        <select
                            class="control"
                            v-model="types.boolean.condition"
                        >
                            <option
                                v-text="translations.condition"
                                selected
                                value="null"
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

                <li v-if="types.boolean.condition != null">
                    <div class="control-group">
                        <select class="control" v-model="types.boolean.value">
                            <option
                                v-text="translations.value"
                                selected
                                value="null"
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

                <li v-if="types.dropdown.isSelected">
                    <div class="control-group">
                        <select
                            class="control"
                            v-model="types.dropdown.condition"
                        >
                            <option
                                v-text="translations.condition"
                                selected
                                value="null"
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

                <li v-if="types.dropdown.condition != null">
                    <div class="control-group">
                        <select class="control" v-model="types.dropdown.value">
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

                <li v-if="types.checkbox.isSelected">
                    <div class="control-group">
                        <select
                            class="control"
                            v-model="types.checkbox.condition"
                        >
                            <option
                                v-text="translations.condition"
                                selected
                                value="null"
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

                <li v-if="types.checkbox.condition != null">
                    <div class="control-group">
                        <button
                            style="width: 100%;"
                            type="button"
                            class="dropdown-btn"
                            @click="toggleCheckboxDropdown"
                        >

                            <span>
                                {{
                                    types.checkbox.value.length > 0
                                        ? types.checkbox.value.join(', ')
                                        : translations.select
                                }}
                            </span>

                            <span>
                                <i class="icon arrow-down-icon"></i>
                            </span>

                        </button>

                        <div
                            ref="checkboxOptions"
                            class="dropdown-list checkbox-dropdown-list"
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
                                                v-model="types.checkbox.value"
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

                <li v-if="types.datetime.isSelected">
                    <div class="control-group">
                        <select
                            class="control"
                            v-model="types.datetime.condition"
                        >
                            <option
                                v-text="translations.condition"
                                selected
                                value="null"
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

                <li v-if="types.datetime.condition != null">
                    <div class="control-group">
                        <input
                            class="control"
                            v-model="types.datetime.value"
                            type="date"
                        />
                    </div>
                </li>

                <button
                    v-text="translations.apply"
                    class="btn btn-sm btn-primary apply-filter"
                    @click="getResponse"
                ></button>
            </ul>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.checkbox-dropdown-list {
    font-size: 11px;
    width: inherit;
}
</style>

<script>
export default {
    props: ['columns', 'translations'],

    data() {
        return {
            type: null,

            columnOrAlias: '',

            filterColumn: null,

            types: {
                string: {
                    isSelected: false,
                    condition: null,
                    value: null
                },

                number: {
                    isSelected: false,
                    condition: null,
                    value: null
                },

                boolean: {
                    isSelected: false,
                    condition: null,
                    value: null
                },

                dropdown: {
                    isSelected: false,
                    condition: null,
                    value: null
                },

                checkbox: {
                    isSelected: false,
                    condition: null,
                    value: []
                },

                datetime: {
                    isSelected: false,
                    condition: null,
                    value: '2000-01-01'
                }
            }
        };
    },

    methods: {
        toggleCheckboxDropdown() {
            const display = this.$refs.checkboxOptions.style.display;

            if (display === 'none') {
                this.$refs.checkboxOptions.style.display = 'block';
                return;
            }

            this.$refs.checkboxOptions.style.display = 'none';
        },

        filterNumberInput: function(e) {
            this.types.number.value = e.target.value.replace(
                /[^0-9\,\.]+/g,
                ''
            );
        },

        isCurrentFilterColumnHasOptions() {
            let currentFilterColumn = this.getCurrentFilterColumn();

            return (
                currentFilterColumn && currentFilterColumn.options !== undefined
            );
        },

        getCurrentFilterColumn() {
            return this.columns.find(
                column => column.index === this.filterColumn
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
                            this.switchSelectCondition('string');
                            break;
                        }

                        case 'number': {
                            this.switchSelectCondition('number');
                            break;
                        }

                        case 'boolean': {
                            this.switchSelectCondition('boolean');
                            break;
                        }

                        case 'dropdown': {
                            this.switchSelectCondition('dropdown');
                            break;
                        }

                        case 'checkbox': {
                            this.switchSelectCondition('checkbox');
                            break;
                        }

                        case 'datetime': {
                            this.switchSelectCondition('datetime');
                            break;
                        }

                        case 'price': {
                            this.switchSelectCondition('number');
                            break;
                        }
                    }
                }
            }
        },

        getResponse: function() {
            if (this.type == null) {
                alert(this.translations.emptyField);
            }

            let label = '';

            for (let colIndex in this.columns) {
                if (this.columns[colIndex].index == this.columnOrAlias) {
                    label = this.columns[colIndex].label;
                    break;
                }
            }

            switch (this.type) {
                case 'string': {
                    if (this.types.string.value !== null) {
                        this.emitOnFilterEvent('string', label, true);
                    } else {
                        alert(this.translations.emptyValue);
                    }
                    break;
                }

                case 'number': {
                    let indexConditions = true;

                    if (! this.types.number.value) {
                        this.switchSelectCondition('number');
                    }

                    if (
                        this.filterIndex === this.columnOrAlias &&
                        (this.types.number.value === 0 ||
                            this.types.number.value < 0)
                    ) {
                        indexConditions = false;

                        alert(this.translations.zeroIndex);
                    }

                    if (indexConditions) {
                        this.emitOnFilterEvent('number', label);
                    }
                    break;
                }

                case 'boolean': {
                    this.emitOnFilterEvent('boolean', label);
                    break;
                }

                case 'dropdown': {
                    this.emitOnFilterEvent('dropdown', label);
                    break;
                }

                case 'checkbox': {
                    this.emitOnFilterEvent('checkbox', label);
                    break;
                }

                case 'datetime': {
                    this.emitOnFilterEvent('datetime', label);
                    break;
                }

                case 'price': {
                    this.emitOnFilterEvent('number', label);
                    break;
                }
            }
        },

        switchSelectCondition(type) {
            this.resetAllTypesSelection();

            this.types[type].isSelected = true;

            this.resetAllTypesCondition();
        },

        emitOnFilterEvent(type, label, isEncoded = false) {
            this.$emit('onFilter', {
                data: {
                    column: this.columnOrAlias,
                    condition: this.types[type].condition,
                    response: isEncoded
                        ? encodeURIComponent(this.types[type].value)
                        : this.types[type].value,
                    label,
                    type
                }
            });
        },

        resetAllTypesSelection() {
            for (let key in this.types) {
                if (this.types.hasOwnProperty(key)) {
                    this.types[key].isSelected = false;
                }
            }
        },

        resetAllTypesCondition() {
            for (let key in this.types) {
                if (this.types.hasOwnProperty(key)) {
                    this.types[key].condition = null;
                }
            }
        }
    }
};
</script>
