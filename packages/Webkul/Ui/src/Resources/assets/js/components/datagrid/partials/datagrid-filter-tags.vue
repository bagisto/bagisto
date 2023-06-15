<template>
    <div class="filtered-tags">
        <form
            v-if="massActionsToggle"
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
                v-model="dataId"
            />

            <input
                type="hidden"
                name="channel"
                :value="extraFilters.current.channel"
            />

            <input
                type="hidden"
                name="locale"
                :value="extraFilters.current.locale"
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
                        :value="massAction.type"
                    ></option>
                </select>
            </div>

            <div
                class="control-group"
                style="margin-left: 10px"
                v-if="massActionType == 'update'"
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

        <div class="item-count" v-if="itemCount"> {{ itemCount }} {{ translations.of }} {{ total }} {{ translations.selected }}</div>
        
        <template v-if="filters.length > 0">
            <datagrid-filter-tag
                :key="filterKey"
                :filter="filter"
                :translations="translations"
                v-for="(filter, filterKey) in filters"
                @onRemoveFilter="removeFilter(filter)"
            ></datagrid-filter-tag>
            
            <p @click="removeAllFilters" v-if="filters.length > 0">{{ translations.clearAll }}</p>
        </template>
    </div>
</template>

<script>
import DatagridFilterTag from './datagrid-filter-tag.vue';

export default {
    props: [
        'filters', 
        'translations',
        'massActions',
        'massActionTargets',
        'dataId',
        'massActionsToggle',
        'extraFilters',
        'itemCount',
        'total'
    ],

    components: {
        DatagridFilterTag
    },

    data() {
        return {
            massActionConfirmText: this.translations.clickOnAction,
            massActionType: this.getDefaultMassActionType(),
            massActionUpdateValue: null,
            massActionValues: [],
        };
    },
    
    methods: {
        removeFilter(filter) {
            this.$emit('onRemoveFilter', { data: { filter } });
        },

        removeAllFilters() {
            this.$emit('onRemoveAllFilter');
        },

        getDefaultMassActionType: function() {
            return {
                id: null,
                value: null
            };
        },

        changeMassActionTarget: function() {
            if (this.massActionType === 'delete') {
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

            if (this.massActionType === 'update') {
                for (let i in this.massActionTargets) {
                    if (this.massActionTargets[i].type === 'update') {
                        this.massActionValues = this.massActions[
                            i
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
    }
};
</script>
