@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.families.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.families.update', $attributeFamily->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.catalog.families.index') }}'"></i>

                        {{ __('admin::app.catalog.families.edit-title') }}
                    </h1>
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

                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.before', ['attributeFamily' => $attributeFamily]) !!}

                    <accordian title="{{ __('admin::app.catalog.families.general') }}" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.controls.before', ['attributeFamily' => $attributeFamily]) !!}

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <input type="text" v-validate="'required'" name="code" class="control" id="code" value="{{ old('code') ?: $attributeFamily->code }}" disabled="disabled" data-vv-as="&quot;{{ __('admin::app.catalog.families.code') }}&quot;" v-code/>
                                <input type="hidden" name="code" value="{{ $attributeFamily->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') ?: $attributeFamily->name }}" data-vv-as="&quot;{{ __('admin::app.catalog.families.name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.controls.after', ['attributeFamily' => $attributeFamily]) !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.after', ['attributeFamily' => $attributeFamily]) !!}


                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.before', ['attributeFamily' => $attributeFamily]) !!}

                    <accordian title="{{ __('admin::app.catalog.families.groups') }}" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.controls.before', ['attributeFamily' => $attributeFamily]) !!}

                            <group-list></group-list>

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.controls.before', ['attributeFamily' => $attributeFamily]) !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.after', ['attributeFamily' => $attributeFamily]) !!}
                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="group-list-template">
        <div style="margin-top: 20px">
            <button type="button" style="margin-bottom : 20px" class="btn btn-md btn-primary" @click="$root.showModal('addGroupForm')">
                {{ __('admin::app.catalog.families.add-group-title') }}
            </button>

            <modal id="addGroupForm" :is-open="$root.modalIds.addGroupForm">
                <h3 slot="header">{{ __('admin::app.catalog.families.add-group-title') }}</h3>

                <div slot="body">
                    <form method="POST" data-vv-scope="add-group-form" @submit.prevent="addGroup('add-group-form')">

                        <div class="page-content">
                            <div class="form-container">
                                <div class="control-group" :class="[errors.has('add-group-form.name') ? 'has-error' : '']">
                                    <label for="name" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                                    <input type="text" v-validate="'required'" v-model="group.name" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.catalog.families.name') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('add-group-form.name')">@{{ errors.first('add-group-form.name') }}</span>
                                </div>

                                <div class="control-group" :class="[errors.has('add-group-form.position') ? 'has-error' : '']">
                                    <label for="position" class="required">{{ __('admin::app.catalog.families.position') }}</label>
                                    <input type="text" v-validate="'required|numeric'" v-model="group.position" class="control" id="position" name="position" data-vv-as="&quot;{{ __('admin::app.catalog.families.position') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('add-group-form.position')">@{{ errors.first('add-group-form.position') }}</span>
                                </div>

                                <button type="submit" class="btn btn-lg btn-primary">
                                    {{ __('admin::app.catalog.families.add-group-title') }}
                                </button>

                            </div>
                        </div>

                    </form>
                </div>
            </modal>

            <modal id="editGroupForm" :is-open="$root.modalIds.editGroupForm">
                <h3 slot="header">{{ __('admin::app.catalog.families.edit-group-title') }}</h3>

                <div slot="body">
                    <form method="POST" data-vv-scope="edit-group-form" @submit.prevent="updateGroup('edit-group-form')">

                        <div class="page-content">
                            <div class="form-container">
                                <div class="control-group" :class="[errors.has('edit-group-form.name') ? 'has-error' : '']">
                                    <label for="name" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                                    <input type="text" v-validate="'required'" v-model="editGroup.name" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.catalog.families.name') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('edit-group-form.name')">@{{ errors.first('edit-group-form.name') }}</span>
                                </div>

                                <div class="control-group" :class="[errors.has('edit-group-form.position') ? 'has-error' : '']">
                                    <label for="position" class="required">{{ __('admin::app.catalog.families.position') }}</label>
                                    <input type="text" v-validate="'required|numeric'" v-model="editGroup.position" class="control" id="position" name="position" data-vv-as="&quot;{{ __('admin::app.catalog.families.position') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('edit-group-form.position')">@{{ errors.first('edit-group-form.position') }}</span>
                                </div>

                                <button type="submit" class="btn btn-lg btn-primary">
                                    {{ __('admin::app.catalog.families.update-group-title') }}
                                </button>

                            </div>
                        </div>

                    </form>
                </div>
            </modal>

            <group-item
                v-for='(group, index) in groups'
                :group="group"
                :custom_attributes="custom_attributes"
                :key="index"
                :index="index"
                @onRemoveGroup="removeGroup($event)"
                @onEditGroup="openEditGroupModal($event)"
                @onAttributeAdd="addAttributes(index, $event)"
                @onAttributeRemove="removeAttribute(index, $event)"
            ></group-item>
        </div>
    </script>

    <script type="text/x-template" id="group-item-template">
        <accordian :title="group.name" :active="true">
            <div slot="header">
                <i class="icon expand-icon left"></i>

                <h1>@{{ group.name }}</h1>

                <i class="icon trash-icon" @click="removeGroup()" v-if="group.is_user_defined"></i>

                <span class="icon pencil-lg-icon" @click="editGroup()"></span>
            </div>

            <div slot="body">
                <input type="hidden" :name="[groupInputName + '[name]']" :value="group.name"/>
                <input type="hidden":name="[groupInputName + '[position]']" :value="group.position"/>

                <div class="table" v-if="group.custom_attributes.length" style="margin-bottom: 20px;">
                    <div class="table-responsive">
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
                                <tr v-for='(attribute, index) in group.custom_attributes'>
                                    <td>
                                        <input type="hidden" :name="[groupInputName + '[custom_attributes][][id]']" :value="attribute.id"/>
                                        @{{ attribute.code }}
                                    </td>
                                    <td>@{{ attribute.admin_name }}</td>
                                    <td>@{{ attribute.type }}</td>
                                    <td class="actions">
                                        <i class="icon trash-icon" @click="removeAttribute(attribute)" v-if="attribute.is_user_defined || attribute.removable"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                            <li v-for='(attribute, index) in custom_attributes' :data-id="attribute.id">
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
        var groups = @json($attributeFamily->attribute_groups);
        var custom_attributes = @json($customAttributes);

        Vue.component('group-list', {

            template: '#group-list-template',

            data: function() {
                return {
                    group: {
                        'name': '',
                        'position': '',
                        'custom_attributes': []
                    },

                    editGroup: {
                        'name': '',
                        'position': '',
                        'is_user_defined': 1,
                        'custom_attributes': []
                    },

                    groups: groups,

                    custom_attributes: custom_attributes
                }
            },

            created: function () {
                this.groups.forEach(function(group) {
                    group.custom_attributes.forEach(function(attribute) {
                        var attribute = this.custom_attributes.filter(function (attributeTemp) {
                            return attributeTemp.id == attribute.id;
                        });

                        if (attribute.length) {
                            let index = this.custom_attributes.indexOf(attribute[0])

                            this.custom_attributes.splice(index, 1)
                        }

                    });
                });
            },

            methods: {
                addGroup: function (formScope) {
                    var this_this = this;

                    this.$validator.validateAll(formScope).then(function (result) {
                        if (result) {

                            var filteredGroups = groups.filter(function(group) {
                                return this_this.group.name.trim() === group.name.trim()
                            })

                            if (filteredGroups.length) {
                                const field = this.$validator.fields.find({ name: 'name', scope: 'add-group-form' });

                                if (field) {
                                    this.$validator.errors.add({
                                        id: field.id,
                                        field: 'name',
                                        msg: "{{ __('admin::app.catalog.families.group-exist-error') }}",
                                        scope: 'add-group-form',
                                    });
                                }
                            } else {
                                groups.push(this_this.group);

                                groups = this_this.sortGroups();

                                this.group = {'name': '', 'position': '', 'is_user_defined': 1, 'custom_attributes': []};

                                self.$set(self.$root.modalIds, 'addGroupForm', false);
                            }
                        }
                    });
                },

                updateGroup: function(formScope) {
                    var self = this;

                    this.$validator.validateAll(formScope).then(function (result) {
                        if (result) {

                            var filteredGroups = groups.filter(function(group) {
                                return self.editGroup.name.trim() === group.name.trim()
                            })

                            if (filteredGroups.length > 1) {
                                const field = self.$validator.fields.find({ name: 'name', scope: 'edit-group-form' });

                                if (field) {
                                    self.$validator.errors.add({
                                        id: field.id,
                                        field: 'name',
                                        msg: "{{ __('admin::app.catalog.families.group-exist-error') }}",
                                        scope: 'edit-group-form',
                                    });
                                }
                            } else {
                                let index = groups.indexOf(self.editGroup)

                                groups[index] = self.editGroup;

                                groups = self.sortGroups();

                                this.editGroup = {'name': '', 'position': '', 'is_user_defined': 1, 'custom_attributes': []};

                                self.$set(self.$root.modalIds, 'editGroupForm', false);
                            }
                        }
                    });
                },

                sortGroups: function () {
                    return groups.sort(function(a, b) {
                        return a.position - b.position;
                    });
                },

                openEditGroupModal: function (group) {
                    this.editGroup = group;

                    this.$root.showModal('editGroupForm')
                },

                removeGroup: function (group) {
                    group.custom_attributes.forEach(function(attribute) {
                        this.custom_attributes.push(attribute);
                    })

                    this.custom_attributes = this.sortAttributes();

                    let index = groups.indexOf(group)

                    groups.splice(index, 1)
                },

                addAttributes: function (groupIndex, attributeIds) {
                    attributeIds.forEach(function(attributeId) {
                        var attribute = this.custom_attributes.filter(function (attribute) {
                            return attribute.id == attributeId;
                        });

                        attribute[0].removable = true;

                        this.groups[groupIndex].custom_attributes.push(attribute[0]);

                        let index = this.custom_attributes.indexOf(attribute[0])

                        this.custom_attributes.splice(index, 1)
                    })
                },

                removeAttribute: function (groupIndex, attribute) {
                    let index = this.groups[groupIndex].custom_attributes.indexOf(attribute)

                    this.groups[groupIndex].custom_attributes.splice(index, 1)

                    this.custom_attributes.push(attribute);

                    this.custom_attributes = this.sortAttributes();
                },

                sortAttributes: function () {
                    return this.custom_attributes.sort(function(a, b) {
                        return a.id - b.id;
                    });
                }
            }
        })

        Vue.component('group-item', {
            props: ['index', 'group', 'custom_attributes'],

            template: "#group-item-template",

            computed: {
                groupInputName: function () {
                    if (this.group.id)
                        return "attribute_groups[" + this.group.id + "]";

                    return "attribute_groups[group_" + this.index + "]";
                }
            },

            methods: {
                editGroup: function() {
                    this.$emit('onEditGroup', this.group)
                },

                removeGroup: function () {
                    this.$emit('onRemoveGroup', this.group)
                },

                addAttributes: function (e) {
                    var attributeIds = [];

                    $(e.target).prev().find('li input').each(function() {
                        var attributeId = $(this).val();

                        if ($(this).is(':checked')) {
                            attributeIds.push(attributeId);

                            $(this).prop('checked', false);
                        }
                    });

                    $('body').trigger('click')

                    this.$emit('onAttributeAdd', attributeIds)
                },

                removeAttribute: function (attribute) {
                    var confirmDelete = confirm('Are you sure to do this?')

                    if (confirmDelete) {
                        this.$emit('onAttributeRemove', attribute)
                    }
                }
            }
        });
    </script>
@endpush
