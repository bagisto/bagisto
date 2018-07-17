@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.families.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.families.store') }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.families.add-title') }}</h1>
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

                    <accordian :title="'{{ __('admin::app.catalog.families.general') }}'" :active="true">
                        <div slot="body">
                        
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code">{{ __('admin::app.catalog.families.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>
                        
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('admin::app.catalog.families.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}"/>
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
                        <label for="groupName">{{ __('admin::app.catalog.families.name') }}</label>
                        <input type="text" v-validate="'required'" v-model="group.groupName" class="control" id="groupName" name="groupName"/>
                        <span class="control-error" v-if="errors.has('add-group-form.groupName')">@{{ errors.first('add-group-form.groupName') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('add-group-form.position') ? 'has-error' : '']">
                        <label for="position">{{ __('admin::app.catalog.families.position') }}</label>
                        <input type="text" v-validate="'required'" v-model="group.position" class="control" id="position" name="position"/>
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
        <div>
            <group-item v-for='(group, index) in groups' :group="group" :attributes="attributes" :key="index" @onRemoveGroup="removeGroup($event)" @onAttributeAdd="addAttributes(index, $event)" @onAttributeRemove="removeAttribute(index, $event)"></group-item>
        </div>
    </script>

    <script type="text/x-template" id="group-item-template">
        <accordian :title="group.groupName" :active="true">
            <div slot="header">
                <i class="icon expand-icon left"></i>
                <h1>@{{ group.groupName }}</h1>
                <i class="icon trash-icon" @click="removeGroup()"></i>
            </div>

            <div slot="body">
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
                                <td>@{{ attribute.code }}</td>
                                <td>@{{ attribute.name }}</td>
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
            var groups = [];
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
                                groups.push(this.group);

                                groups = this.sortGroups();
                                
                                this.group = {'groupName': '', 'position': '', 'attributes': []};

                                this.$parent.closeModal();
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

                methods: {
                    removeGroup (group) {
                        let index = groups.indexOf(group)

                        groups.splice(index, 1)
                    },

                    addAttributes (groupIndex, attributeIds) {
                        var this_this = this;
                        attributeIds.forEach(function(attributeId) {
                            var attribute = this_this.attributes.filter(attribute => attribute.id == attributeId)

                            this_this.groups[groupIndex].attributes.push(attribute[0]);

                            let index = this_this.attributes.indexOf(attribute)

                            this_this.attributes.splice(index, 1)
                        })
                    },

                    removeAttribute (groupIndex, attribute) {
                        
                    }
                }
            })

            Vue.component('group-item', {
                props: ['group', 'attributes'],

                template: "#group-item-template",

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
                        this.$emit('onAttributeRemove', attributeIds)
                    }
                }
            });
        // });
    </script>
@stop 