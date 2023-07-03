<v-media {{ $attributes }} ></v-media>

@pushOnce('scripts')
    <script type="text/x-template" id="v-media-template">
        <div class="flex flex-col mb-4 p-4 rounded-lg cursor-pointer">
            <div
                :class="{'border border-dashed border-gray-300 rounded-[18px]': isDragOver }"
            >
                <label 
                    for="dropzone-file"
                    class="flex flex-col w-[286px] h-[286px] items-center justify-center rounded-[12px] cursor-pointer bg-[#F5F5F5] hover:bg-gray-100"
                    @dragover="onDragOver"
                    @dragleave="onDragLeave"
                    @drop="onDrop"
                >
                    <label 
                        for="file-input"
                        class="m-0 block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer bg"
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

            <div class="flex mt-3 items-center pl-[30px]">
                <ul class="grid grid-cols-3 gap-4">
                    <li 
                        v-for="(file, index) in uploadedFiles"
                        :key="index"
                        class="relative"
                    >
                        <template v-if="isImage(file)">
                            <div
                                @mouseenter="file.showDeleteButton = true"
                                @mouseleave="file.showDeleteButton = false"
                            >
                                <img
                                    :src="file.url"
                                    :alt="file.name"
                                    class="rounded-[12px] min-w-[50px] max-h-[50px] cursor-pointer"
                                >

                                <button
                                    v-if="file.showDeleteButton"    
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition duration-300"
                                    title="Remove"
                                    @click="removeFile(index)"
                                >
                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="w-4 h-4"
                                    >media
                                    <path
                                        fill-rule="evenodd"
                                        d="M2 5a3 3 0 013-3h10a3 3 0 013 3v10a3 3 0 01-3 3H5a3 3 0 01-3-3V5zm3-1a1 1 0 00-1 1v2h12V5a1 1 0 00-1-1H5zm1 3a1 1 0 011-1h2a1 1 0 110 2H6a1 1 0 01-1-1zm4 4a1 1 0 100 2h2a1 1 0 100-2H11zm-4 0a1 1 0 100 2h2a1 1 0 100-2H7zm4 4a1 1 0 110 2h2a1 1 0 110-2h-2z"
                                        clip-rule="evenodd"
                                    />
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <template v-else>
                            <div
                                @mouseenter="file.showDeleteButton = true"
                                @mouseleave="file.showDeleteButton = false"
                            >
                                <video
                                    :src="file.url"
                                    :alt="file.name"
                                    class="rounded-[12px] min-w-[50px] max-h-[50px] cursor-pointer"
                                >
                                </video>

                                <button
                                    v-if="file.showDeleteButton"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition duration-300"
                                    title="Remove"
                                    @click="removeFile(index)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="w-4 h-4"
                                    >
                                        
                                    <path
                                        fill-rule="evenodd"
                                        d="M2 5a3 3 0 013-3h10a3 3 0 013 3v10a3 3 0 01-3 3H5a3 3 0 01-3-3V5zm3-1a1 1 0 00-1 1v2h12V5a1 1 0 00-1-1H5zm1 3a1 1 0 011-1h2a1 1 0 110 2H6a1 1 0 01-1-1zm4 4a1 1 0 100 2h2a1 1 0 100-2H11zm-4 0a1 1 0 100 2h2a1 1 0 100-2H7zm4 4a1 1 0 110 2h2a1 1 0 110-2h-2z"
                                        clip-rule="evenodd"
                                    />
                                    </svg>
                                </button>
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
