@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.families.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.families.update', $attributeFamily->id) }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.families.edit-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.families.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.catalog.families.general') }}'" :active="true">
                        <div slot="body">
                        
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.catalog.families.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') ?: $attributeFamily->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>
                        
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') ?: $attributeFamily->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.families.groups') }}'" :active="true">
                        <div slot="body">

                            <button type="button" class="btn btn-md btn-primary" @click="showModal('addGroup')">
                                {{ __('admin::app.catalog.families.add-group-title') }}
                            </button>

                            <group-list></group-list>
                        </div>
                    </accordian>
                </div>
            </div>

        </form>
    </div>

    <modal id="addGroup" :is-open="modalIds.addGroup">
        <h3 slot="header">{{ __('admin::app.catalog.families.add-group-title') }}</h3>

        <div slot="body">
            <group-form></group-form>
        </div>
    </modal>
@stop

@section('javascript')

    <script type="text/x-template" id="group-form-template">
        <form method="POST" action="{{ route('admin.catalog.families.store') }}" data-vv-scope="add-group-form" @submit.prevent="addGroup('add-group-form')">

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <div class="control-group" :class="[errors.has('add-group-form.groupName') ? 'has-error' : '']">
                        <label for="groupName" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                        <input type="text" v-validate="'required'" v-model="group.groupName" class="control" id="groupName" name="groupName"/>
                        <span class="control-error" v-if="errors.has('add-group-form.groupName')">@{{ errors.first('add-group-form.groupName') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('add-group-form.position') ? 'has-error' : '']">
                        <label for="position">{{ __('admin::app.catalog.families.position') }}</label>
                        <input type="text" v-validate="'required|numeric'" v-model="group.position" class="control" id="position" name="position"/>
                        <span class="control-error" v-if="errors.has('add-group-form.position')">@{{ errors.first('add-group-form.position') }}</span>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.families.add-group-title') }}
                    </button>

                </div>
            </div>

        </form>
    </script>

    <script type="text/x-template" id="group-list-template">
        <div style="margin-top: 20px">
            <group-item v-for='(group, index) in groups' :group="group" :attributes="attributes" :key="index" :index="index" @onRemoveGroup="removeGroup($event)" @onAttributeAdd="addAttributes(index, $event)" @onAttributeRemove="removeAttribute(index, $event)"></group-item>
        </div>
    </script>

    <script type="text/x-template" id="group-item-template">
        <accordian :title="group.groupName" :active="true">
            <div slot="header">
                <i class="icon expand-icon left"></i>
                <h1>@{{ group.name ? group.name : group.groupName }}</h1>
                <i class="icon trash-icon" @click="removeGroup()"></i>
            </div>

            <div slot="body">
                <input type="hidden" :name="groupInputName" :value="group.name ? group.name : group.groupName"/>
                <input type="hidden":name="groupInputPosition" :value="group.position"/>

                <div class="table" v-if="group.attributes.length" style="margin-bottom: 20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('admin::app.catalog.families.attribute-code') }}</th>
                                <th>{{ __('admin::app.catalog.families.name') }}</th>
                                <th>{{ __('admin::app.catalog.families.type') }}</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for='(attribute, index) in group.attributes'>
                                <td>
                                    <input type="hidden" :name="groupAttributeInput" :value="attribute.id"/>
                                    @{{ attribute.code }}
                                </td>
                                <td>@{{ attribute.admin_name }}</td>
                                <td>@{{ attribute.type }}</td>
                                <td class="actions">
                                    <i class="icon trash-icon" @click="removeAttribute(attribute)"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-md btn-primary dropdown-toggle">
                    {{ __('admin::app.catalog.families.add-attribute-title') }}
                </button>

                <div class="dropdown-list" style="width: 240px">
                    <div class="search-box">
                        <input type="text" class="control" placeholder="{{ __('admin::app.catalog.families.search') }}">
                    </div>

                    <div class="dropdown-container">
                        <ul>
                            <li v-for='(attribute, index) in attributes' :data-id="attribute.id">
                                <span class="checkbox">
                                    <input type="checkbox" :id="attribute.id" :value="attribute.id"/>
                                    <label class="checkbox-view" :for="attribute.id"></label>
                                    @{{ attribute.admin_name }}
                                </span>
                            </li>
                        </ul>

                        <button type="button" class="btn btn-lg btn-primary" @click="addAttributes($event)">
                            {{ __('admin::app.catalog.families.add-attribute-title') }}
                        </button>
                    </div>
                </div>
            </div>
        </accordian>
    </script>
    
    <script>
        // $(document).ready(function () {
            var groups = @json($attributeFamily->attribute_groups);
            var attributes = @json($attributes);

            Vue.component('group-form', {

                data: () => ({
                    group: {
                        'groupName': '',
                        'position': '',
                        'attributes': []
                    }
                }),

                template: '#group-form-template',

                methods: {
                    addGroup (formScope) {
                        this.$validator.validateAll(formScope).then((result) => {
                            if (result) {
                                var this_this = this;

                                var filteredGroups = groups.filter(function(group) {
                                    return this_this.group.groupName.trim() === (group.name ? group.name.trim() : group.groupName.trim())
                                })

                                if(filteredGroups.length) {
                                    const field = this.$validator.fields.find({ name: 'groupName', scope: 'add-group-form' });

                                    if (field) {
                                        this.$validator.errors.add({
                                            id: field.id,
                                            field: 'groupName',
                                            msg: "{{ __('admin::app.catalog.families.group-exist-error') }}",
                                            scope: 'add-group-form',
                                        });
                                    }
                                } else {
                                    groups.push(this.group);

                                    groups = this.sortGroups();
                                    
                                    this.group = {'groupName': '', 'position': '', 'attributes': []};

                                    this.$parent.closeModal();
                                }
                            }
                        });
                    },

                    sortGroups () {
                        return groups.sort(function(a, b) {
                            return a.position - b.position;
                        });
                    }
                }
            });

            Vue.component('group-list', {

                template: '#group-list-template',

                data: () => ({
                    groups: groups,
                    attributes: attributes
                }),

                created () {
                    this.groups.forEach(function(group) {
                        group.attributes.forEach(function(attribute) {
                            var attribute = this.attributes.filter(attributeTemp => attributeTemp.id == attribute.id)

                            if(attribute.length) {
                                let index = this.attributes.indexOf(attribute[0])

                                this.attributes.splice(index, 1)
                            }

                        });
                    });
                },

                methods: {
                    removeGroup (group) {
                        group.attributes.forEach(function(attribute) {
                            this.attributes.push(attribute);
                        })

                        this.attributes = this.sortAttributes();

                        let index = groups.indexOf(group)

                        groups.splice(index, 1)
                    },

                    addAttributes (groupIndex, attributeIds) {
                        attributeIds.forEach(function(attributeId) {
                            var attribute = this.attributes.filter(attribute => attribute.id == attributeId)
                            
                            this.groups[groupIndex].attributes.push(attribute[0]);

                            let index = this.attributes.indexOf(attribute[0])

                            this.attributes.splice(index, 1)
                        })
                    },

                    removeAttribute (groupIndex, attribute) {
                        let index = this.groups[groupIndex].attributes.indexOf(attribute)

                        this.groups[groupIndex].attributes.splice(index, 1)

                        this.attributes.push(attribute);

                        this.attributes = this.sortAttributes();
                    },

                    sortAttributes () {
                        return this.attributes.sort(function(a, b) {
                            return a.id - b.id;
                        });
                    }
                }
            })

            Vue.component('group-item', {
                props: ['index', 'group', 'attributes'],

                template: "#group-item-template",

                computed: {
                    groupInputName () {
                        if(this.group.id)
                            return "attribute_groups[" + this.group.id + "][name]";

                        return "attribute_groups[group_" + this.index + "][name]";
                    },

                    groupInputPosition () {
                        if(this.group.id)
                            return "attribute_groups[" + this.group.id + "][position]";

                        return "attribute_groups[group_" + this.index + "][position]";
                    },

                    groupAttributeInput () {
                        if(this.group.id)
                            return "attribute_groups[" + this.group.id + "][attributes][]";

                        return "attribute_groups[group_" + this.index + "][attributes][]";
                    }
                },

                methods: {
                    removeGroup () {
                        this.$emit('onRemoveGroup', this.group)
                    },

                    addAttributes (e) {
                        var attributeIds = [];

                        $(e.target).prev().find('li input').each(function() {
                            var attributeId = $(this).val();
                            
                            if($(this).is(':checked')) {
                                attributeIds.push(attributeId);

                                $(this).prop('checked', false);
                            }
                        });

                        $('body').trigger('click')

                        this.$emit('onAttributeAdd', attributeIds)
                    },

                    removeAttribute (attribute) {
                        this.$emit('onAttributeRemove', attribute)
                    }
                }
            });
        // });
    </script>
@stop 