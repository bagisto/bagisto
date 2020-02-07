{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.downloadable.before', ['product' => $product]) !!}

<accordian :title="'{{ __('admin::app.catalog.products.downloadable') }}'" :active="true">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.downloadable.links.controls.before', ['product' => $product]) !!}

        <div id="downloadable-link-list-section" class="section">
            <div class="secton-title">
                <span>{{ __('admin::app.catalog.products.links') }}</span>
            </div>

            <div class="section-content">
                <downloadable-link-list></downloadable-link-list>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.downloadable.links.controls.after', ['product' => $product]) !!}

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.downloadable.samples.controls.before', ['product' => $product]) !!}

        <div id="downloadable-sample-list-section" class="section">
            <div class="secton-title">
                <span>{{ __('admin::app.catalog.products.samples') }}</span>
            </div>

            <div class="section-content">
                <downloadable-sample-list></downloadable-sample-list>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.downloadable.samples.controls.after', ['product' => $product]) !!}

    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.downloadable.after', ['product' => $product]) !!}

@push('scripts')
    @parent

    <script type="text/x-template" id="downloadable-link-list-template">
        <div class="table" style="overflow-x: unset;">
            <table style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>{{ __('admin::app.catalog.products.name') }}</th>
                        <th>{{ __('admin::app.catalog.products.price') }}</th>
                        <th>{{ __('admin::app.catalog.products.file') }}</th>
                        <th>{{ __('admin::app.catalog.products.sample') }}</th>
                        <th>{{ __('admin::app.catalog.products.downloads') }}</th>
                        <th>{{ __('admin::app.catalog.products.sort-order') }}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>

                <tbody>

                    <downloadable-link-item 
                        v-for='(link, index) in links'
                        :link="link"
                        :key="index"
                        :index="index"
                        @onRemoveLink="removeLink($event)"
                    ></downloadable-link-item>

                </tbody>
            </table>

            <button type="button" class="btn btn-md btn-primary" @click="addLink">
                {{ __('admin::app.catalog.products.add-link-btn-title') }}
            </button>
        </div>
    </script>

    <script type="text/x-template" id="downloadable-link-item-template">
        <tr>
            <td>
                <div class="control-group" :class="[errors.has(linkInputTitleName) ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" v-model="link.title" :name="[linkInputTitleName]" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.name') }}&quot;"/>

                    <span class="control-error" v-if="errors.has(linkInputTitleName)">
                        @{{ errors.first(linkInputTitleName) }}
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(linkInputName + '[price]') ? 'has-error' : '']">
                    <input type="text" v-validate="'min_value:0'" v-model="link.price" :name="[linkInputName + '[price]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;"/>

                    <span class="control-error" v-if="errors.has(linkInputName + '[price]')">@{{ errors.first(linkInputName + '[price]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" style="margin-bottom: 10px;">
                    <select v-model="link.type" :name="[linkInputName + '[type]']" class="control">
                        <option value="file">{{ __('admin::app.catalog.products.upload-file') }}</option>
                        <option value="url">{{ __('admin::app.catalog.products.url') }}</option>
                    </select>
                </div>

                <div class="control-group" :class="[errors.has(linkInputName + '[file]') ? 'has-error' : '']" v-if="link.type == 'file'">
                    <input type="hidden" v-validate="'required'" :name="[linkInputName + '[file]']" v-model="link.file" data-vv-as="&quot;{{ __('admin::app.catalog.products.file') }}&quot;"/>

                    <input type="hidden" :name="[linkInputName + '[file_name]']" v-model="link.file_name"/>

                    <input type="file" class="control" :id="[linkInputName + '[file]']" ref="file" @change="uploadFile('file')" style="display: none"/>

                    <a v-if="link.file_url" :href="link.file_url" style="margin-bottom: 10px; display: block;" target="_blank">
                        @{{ link.file_name | truncate }}
                    </a>

                    <label class="btn btn-black btn-md" :for="[linkInputName + '[file]']" style="width: auto; text-align: center;">
                        {{ __('admin::app.catalog.products.browse-file') }}
                    </label>

                    <span class="control-error" v-if="errors.has(linkInputName + '[file]')">
                        @{{ errors.first(linkInputName + '[file]') }}
                    </span>
                </div>

                <div class="control-group" :class="[errors.has(linkInputName + '[url]') ? 'has-error' : '']" v-if="link.type == 'url'">
                    <input type="text" v-validate="'required'" v-model="link.url" :name="[linkInputName + '[url]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.url') }}&quot;"/>

                    <span class="control-error" v-if="errors.has(linkInputName + '[url]')">@{{ errors.first(linkInputName + '[url]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" style="margin-bottom: 10px;">
                    <select v-model="link.sample_type" :name="[linkInputName + '[sample_type]']" class="control">
                        <option value="file">{{ __('admin::app.catalog.products.upload-file') }}</option>
                        <option value="url">{{ __('admin::app.catalog.products.url') }}</option>
                    </select>
                </div>
                
                <div class="control-group" v-if="link.sample_type == 'file'">
                    <input type="hidden" :name="[linkInputName + '[sample_file]']" v-model="link.sample_file" data-vv-as="&quot;{{ __('admin::app.catalog.products.sampe-file') }}&quot;"/>

                    <input type="hidden" :name="[linkInputName + '[sample_file_name]']" v-model="link.sample_file_name"/>

                    <input type="file" class="control" :id="[linkInputName + '[sample_file]']" ref="sample_file" @change="uploadFile('sample_file')" style="display: none"/>

                    <a v-if="link.sample_file_url" :href="link.sample_file_url" style="margin-bottom: 10px; display: block;" target="_blank">
                        @{{ link.sample_file_name | truncate }}
                    </a>

                    <label class="btn btn-black btn-md" :for="[linkInputName + '[sample_file]']" style="width: auto; text-align: center;">
                        {{ __('admin::app.catalog.products.browse-file') }}
                    </label>
                </div>

                <div class="control-group" v-if="link.sample_type == 'url'">
                    <input type="text" v-model="link.sample_url" :name="[linkInputName + '[sample_url]']" class="control"/>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(linkInputName + '[downloads]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|min_value:0'" v-model="link.downloads" :name="[linkInputName + '[downloads]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.downloads') }}&quot;"/>

                    <span class="control-error" v-if="errors.has(linkInputName + '[downloads]')">@{{ errors.first(linkInputName + '[downloads]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(linkInputName + '[sort_order]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|numeric|min_value:0'" v-model="link.sort_order" :name="[linkInputName + '[sort_order]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.sort-order') }}&quot;"/>
                    
                    <span class="control-error" v-if="errors.has(linkInputName + '[sort_order]')">@{{ errors.first(linkInputName + '[sort_order]') }}</span>
                </div>
            </td>

            <td class="actions">
                <i class="icon remove-icon" @click="removeLink()"></i>
            </td>
        </tr>
    </script>

    <script type="text/x-template" id="downloadable-sample-list-template">
        <div class="table" style="overflow-x: unset;">
            <table style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>{{ __('admin::app.catalog.products.name') }}</th>
                        <th>{{ __('admin::app.catalog.products.file') }}</th>
                        <th>{{ __('admin::app.catalog.products.sort-order') }}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>

                <tbody>

                    <downloadable-sample-item 
                        v-for='(sample, index) in samples'
                        :sample="sample"
                        :key="index"
                        :index="index"
                        @onRemoveSample="removeSample($event)"
                    ></downloadable-sample-item>

                </tbody>
            </table>

            <button type="button" class="btn btn-md btn-primary" @click="addSample">
                {{ __('admin::app.catalog.products.add-sample-btn-title') }}
            </button>
        </div>
    </script>

    <script type="text/x-template" id="downloadable-sample-item-template">
        <tr>
            <td>
                <div class="control-group" :class="[errors.has(sampleInputTitleName) ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" v-model="sample.title" :name="[sampleInputTitleName]" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.name') }}&quot;"/>

                    <span class="control-error" v-if="errors.has(sampleInputTitleName)">
                        @{{ errors.first(sampleInputTitleName) }}
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" style="margin-bottom: 10px;">
                    <select v-model="sample.type" :name="[sampleInputName + '[type]']" class="control">
                        <option value="file">{{ __('admin::app.catalog.products.upload-file') }}</option>
                        <option value="url">{{ __('admin::app.catalog.products.url') }}</option>
                    </select>
                </div>

                <div class="control-group" :class="[errors.has(sampleInputName + '[file]') ? 'has-error' : '']" v-if="sample.type == 'file'">
                    <input type="hidden" v-validate="'required'" :name="[sampleInputName + '[file]']" v-model="sample.file" data-vv-as="&quot;{{ __('admin::app.catalog.products.file') }}&quot;"/>

                    <input type="hidden" :name="[sampleInputName + '[file_name]']" v-model="sample.file_name"/>

                    <input type="file" class="control" :id="[sampleInputName + '[file]']" ref="file" @change="uploadFile('file')" style="display: none"/>

                    <a v-if="sample.file_url" :href="sample.file_url" style="margin-bottom: 10px; display: block;" target="_blank">
                        @{{ sample.file_name | truncate }}
                    </a>

                    <label class="btn btn-black btn-md" :for="[sampleInputName + '[file]']" style="width: auto; text-align: center;">
                        {{ __('admin::app.catalog.products.browse-file') }}
                    </label>

                    <span class="control-error" v-if="errors.has(sampleInputName + '[file]')">
                        @{{ errors.first(sampleInputName + '[file]') }}
                    </span>
                </div>

                <div class="control-group" :class="[errors.has(sampleInputName + '[url]') ? 'has-error' : '']" v-if="sample.type == 'url'">
                    <input type="text" v-validate="'required'" v-model="sample.url" :name="[sampleInputName + '[url]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.url') }}&quot;"/>

                    <span class="control-error" v-if="errors.has(sampleInputName + '[url]')">@{{ errors.first(sampleInputName + '[downloads]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(sampleInputName + '[sort_order]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|numeric|min_value:0'" v-model="sample.sort_order" :name="[sampleInputName + '[sort_order]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.sort-order') }}&quot;"/>
                    
                    <span class="control-error" v-if="errors.has(sampleInputName + '[sort_order]')">@{{ errors.first(sampleInputName + '[sort_order]') }}</span>
                </div>
            </td>

            <td class="actions">
                <i class="icon remove-icon" @click="removeSample()"></i>
            </td>
        </tr>
    </script>

    <script>
        var downloadableLinks = @json($product->downloadable_links);
        var downloadableSamples = @json($product->downloadable_samples);

        Vue.component('downloadable-link-list', {

            template: '#downloadable-link-list-template',

            inject: ['$validator'],

            data: function() {
                return {
                    links: downloadableLinks,

                    old_links: @json(old('downloadable_links'))
                }
            },

            created: function() {
                var index = 0;

                for (var key in this.old_links) {
                    var link = this.old_links[key];

                    if (key.indexOf('link_') !== -1) {
                        link['title'] = link["{{$locale}}"]['title'];

                        downloadableLinks.push(link);
                    } else {
                        for (var code in link) {
                            if (code === "{{$locale}}") {
                                downloadableLinks[index]['title'] = link[code]['title'];
                            } else {
                                downloadableLinks[index][code] = link[code];
                            }
                        }
                    }

                    index++;
                }
            },

            methods: {
                addLink: function() {
                    this.links.push({
                        title: '',
                        type: 'file',
                        file: '',
                        file_name: '',
                        url: '',
                        sample_type: 'file',
                        sample_file: '',
                        sample_file_name: '',
                        sample_url: '',
                        downloads: 0,
                        sort_order: 0
                    });
                },
                
                removeLink: function(link) {
                    let index = this.links.indexOf(link)

                    this.links.splice(index, 1)
                },
            }

        });

        Vue.component('downloadable-link-item', {

            template: '#downloadable-link-item-template',

            props: ['index', 'link'],

            inject: ['$validator'],

            data: function() {
                return {
                    file: '',
                    sample_file: '',
                }
            },

            computed: {
                linkInputTitleName: function () {
                    if (this.link.id)
                        return "downloadable_links[" + this.link.id + "]" + '[{{$locale}}][title]';

                    return "downloadable_links[link_" + this.index + "]" + '[{{$locale}}][title]';
                },

                linkInputName: function () {
                    if (this.link.id)
                        return "downloadable_links[" + this.link.id + "]";

                    return "downloadable_links[link_" + this.index + "]";
                }
            },

            methods: {
                removeLink: function () {
                    this.$emit('onRemoveLink', this.link)
                },

                uploadFile: function(type) {
                    var this_this = this;

                    this[type] = this.$refs[type].files[0];

                    let formData = new FormData();

                    formData.append(type, this[type]);
        
                    this.$http.post("{{ route('admin.catalog.products.upload_link', $product->id) }}", formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                        .then(function(response) {
                            Object.assign(this_this.link, response.data);
                        })
                        .catch(function() {});
                }
            }

        });

        Vue.component('downloadable-sample-list', {

            template: '#downloadable-sample-list-template',

            inject: ['$validator'],

            data: function() {
                return {
                    samples: downloadableSamples
                }
            },

            methods: {
                addSample: function() {
                    this.samples.push({
                        title: '',
                        type: 'file',
                        file: '',
                        file_name: '',
                        url: '',
                        sort_order: 0
                    });
                },
                
                removeSample: function(sample) {
                    let index = this.samples.indexOf(sample)

                    this.samples.splice(index, 1)
                },
            }

        });

        Vue.component('downloadable-sample-item', {

            template: '#downloadable-sample-item-template',

            props: ['index', 'sample'],

            inject: ['$validator'],

            data: function() {
                return {
                    file: ''
                }
            },

            computed: {
                sampleInputTitleName: function () {
                    if (this.sample.id)
                        return "downloadable_samples[" + this.sample.id + "]" + '[{{$locale}}][title]';

                    return "downloadable_samples[sample_" + this.index + "]" + '[{{$locale}}][title]';
                },

                sampleInputName: function () {
                    if (this.sample.id)
                        return "downloadable_samples[" + this.sample.id + "]";

                    return "downloadable_samples[sample_" + this.index + "]";
                }
            },

            methods: {
                removeSample: function () {
                    this.$emit('onRemoveSample', this.sample)
                },

                uploadFile: function(type) {
                    var this_this = this;

                    this.file = this.$refs.file.files[0];

                    let formData = new FormData();

                    formData.append(type, this.file);
        
                    this.$http.post("{{ route('admin.catalog.products.upload_sample', $product->id) }}", formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                        .then(function(response) {
                            Object.assign(this_this.sample, response.data);
                        })
                        .catch(function() {});
                }
            }

        });
    </script>
@endpush