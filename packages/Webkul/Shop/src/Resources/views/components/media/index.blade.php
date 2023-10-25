<v-media {{ $attributes }} >
    <x-shop::media.images.lazy
        class="w-[284px] h-[284px] mt-[30px] rounded-[12px]"
    ></x-shop::media.images.lazy>
</v-media>

@pushOnce('scripts')
    <script type="text/x-template" id="v-media-template">
        <div class="flex flex-col mb-4 rounded-lg cursor-pointer">
            <div :class="{'border border-dashed border-gray-300 dark:border-gray-800 rounded-[18px]': isDragOver }">
                <div
                    class="flex flex-col items-center justify-center w-[284px] h-[284px] bg-[#F5F5F5] rounded-[12px] cursor-pointer hover:bg-gray-100 dark:hover:gray-950"
                    v-if="uploadedFiles.isPicked"
                >
                    <div 
                        class="group flex justify-center relative w-[284px] h-[284px]"
                        @mouseenter="uploadedFiles.showDeleteButton = true"
                        @mouseleave="uploadedFiles.showDeleteButton = false"
                    >
                        <img
                            class="rounded-[12px] object-cover"
                            :src="uploadedFiles.url"
                            :class="{'opacity-25' : uploadedFiles.showDeleteButton}"
                            alt="Uploaded Image"
                        >

                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span 
                                class="icon-bin text-[24px] text-black cursor-pointer"
                                @click="removeFile"
                            >
                            </span>
                        </div>
                    </div>
                </div>

                <label 
                    for="file-input"
                    class="flex flex-col items-center justify-center w-[284px] h-[284px] bg-[#F5F5F5] rounded-[12px] hover:bg-gray-100 cursor-pointer"
                    v-show="! uploadedFiles.isPicked"
                    @dragover="onDragOver"
                    @dragleave="onDragLeave"
                    @drop="onDrop"
                >
                    <label 
                        for="file-input"
                        class="primary-button block w-max m-0 mx-auto py-[11px] px-[43px] rounded-[18px] text-base text-center"
                    >
                        @lang('shop::app.components.media.add-attachments')
                    </label>

                    <input type="hidden" :name="name" v-if="! uploadedFiles.isPicked"/>

                    <v-field
                        type="file"
                        :name="name"
                        id="file-input"
                        class="hidden"
                        :accept="acceptedTypes"
                        :rules="appliedRules"
                        :multiple="isMultiple"
                        @change="onFileChange"
                    >
                    </v-field>
                </label>
            </div>

            <div 
                class="flex items-center"
                v-if="isMultiple"
            >
                <ul class="flex gap-[10px] flex-wrap justify-left mt-2">
                    <li 
                        v-for="(file, index) in uploadedFiles"
                        :key="index"
                    >
                        <template v-if="isImage(file)">
                            <div 
                                class="relative group flex justify-center h-12 w-12"
                                @mouseenter="file.showDeleteButton = true"
                                @mouseleave="file.showDeleteButton = false"
                            >
                                <img
                                    :src="file.url"
                                    :alt="file.name"
                                    class="rounded-[12px] min-w-[48px] max-h-[48px]"
                                    :class="{'opacity-25' : file.showDeleteButton}"
                                >

                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span 
                                        class="icon-bin text-[24px] text-black cursor-pointer"
                                        @click="removeFile(index)"
                                    >
                                    </span>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <div
                                class="relative group flex justify-center h-12 w-12"
                                @mouseenter="file.showDeleteButton = true"
                                @mouseleave="file.showDeleteButton = false"
                            >
                                <video
                                    :src="file.url"
                                    :alt="file.name"
                                    class="min-w-[50px] max-h-[50px] rounded-[12px]"
                                    :class="{'opacity-25' : file.showDeleteButton}"
                                >
                                </video>

                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span 
                                        class="icon-bin text-[24px] text-black cursor-pointer"
                                        @click="removeFile(index)"
                                    >
                                    </span>
                                </div>
                            </div>
                        </template>
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">
        app.component("v-media", {
            template: '#v-media-template',

            props: {
                name: {
                    type: String, 
                    default: 'attachments',
                }, 

                isMultiple: {
                    type: Boolean,
                    default: false,
                }, 

                rules: {
                    type: String,
                },

                acceptedTypes: {
                    type: String, 
                    default: 'image/*, video/*,'
                }, 

                label: {
                    type: String, 
                    default: 'Add attachments'
                }, 

                src: {
                    type: String,
                    default: ''
                }
            },

            data() {
                return {
                    uploadedFiles: [],

                    isDragOver: false,

                    appliedRules: '',
                };
            },

            created() {
                this.appliedRules = this.rules;

                if (this.src != '') {
                    this.appliedRules = '';

                    this.uploadedFiles = {
                        isPicked: true,
                        url: this.src,
                    }                        
                }
            },

            methods: {
                onFileChange(event) {
                    let files = event.target.files;

                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];

                        let reader = new FileReader();

                        reader.onload = () => {
                            if (! this.isMultiple) {
                                this.uploadedFiles = {
                                    isPicked: true,
                                    name: file.name,
                                    url: reader.result,
                                }

                                return;
                            }

                            this.uploadedFiles.push({
                                name: file.name,
                                url: reader.result,
                            });
                        };

                        reader.readAsDataURL(file);
                    }
                },

                handleDroppedFiles(files) {
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];

                        let reader = new FileReader();
                        
                        reader.onload = () => {
                            if (! this.isMultiple) {
                                this.uploadedFiles = {
                                    isPicked: true,
                                    name: file.name,
                                    url: reader.result,
                                }

                                return;
                            }

                            this.uploadedFiles.push({
                                name: file.name,
                                url: reader.result,
                            });
                        };

                        reader.readAsDataURL(file);
                    }
                },

                isImage(file) {
                    if (! file.name) {
                        return;
                    }

                    return file.name.match(/\.(jpg|jpeg|png|gif)$/i);
                },

                onDragOver(event) {
                    event.preventDefault();

                    this.isDragOver = true;
                },

                onDragLeave(event) {
                    event.preventDefault();

                    this.isDragOver = false;
                },
                
                onDrop(event) {
                    event.preventDefault();

                    this.isDragOver = false;

                    let files = event.dataTransfer.files;

                    this.handleDroppedFiles(files);
                },

                removeFile(index) {
                    if (! this.isMultiple) {
                        this.uploadedFiles = [];

                        this.appliedRules = this.rules;
                        
                        return;
                    }

                    this.uploadedFiles.splice(index, 1);
                },
            },        
        });
    </script>
@endpushOnce
