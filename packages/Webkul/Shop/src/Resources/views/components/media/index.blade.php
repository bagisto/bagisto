<v-media {{ $attributes }} ></v-media>

@pushOnce('scripts')
    <script type="text/x-template" id="v-media-template">
        <div class="flex flex-col mb-4 p-4 rounded-lg cursor-pointer">
            <div
                :class="{'border border-dashed border-gray-300 rounded-[18px]': isDragOver }"
            >
                <label 
                    for="dropzone-file"
                    class="flex flex-col w-[284px] h-[284px] items-center justify-center rounded-[12px] cursor-pointer bg-[#F5F5F5] hover:bg-gray-100"
                    @dragover="onDragOver"
                    @dragleave="onDragLeave"
                    @drop="onDrop"
                >
                    <label 
                        for="file-input"
                        class="bs-primary-button m-0 block mx-auto text-base w-max py-[11px] px-[43px] rounded-[18px] text-center"
                    >
                        @lang('Add attachments')
                    </label>

                    <v-field
                        type="file"
                        :name="name"
                        id="file-input"
                        class="hidden"
                        accept="image/*, video/*"
                        :rules="rules"
                        :multiple="isMultiple"
                        @change="onFileChange"
                    >
                    </v-field>
                </label>
            </div>

            <div class="flex items-center">
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
                                    class="rounded-[12px] min-w-[50px] max-h-[50px]"
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

            props: ['name', 'isMultiple', 'rules'],

            data() {
                return {
                    uploadedFiles: [],

                    isDragOver: false,
                };
            },

            methods: {
                onFileChange(event) {
                    let files = event.target.files;

                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];

                        let reader = new FileReader();

                        reader.onload = () => {
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
                            this.uploadedFiles.push({
                                name: file.name,
                                url: reader.result,
                            });
                        };

                        reader.readAsDataURL(file);
                    }
                },

                isImage(file) {
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
                    this.uploadedFiles.splice(index, 1);
                },
            },        
        });
    </script>
@endpushOnce
