<!DOCTYPE html>
<html>
    <head>
        <title>@lang('Bagisto Installer')</title>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url()->to('/') }}">

        @stack('meta')

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'], 'installer')

        {{-- <link 
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}" 
            rel="shortcut icon"
            sizes="16x16"
        > --}}

        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />

        <link
            href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
            rel="stylesheet"
        />

        <link 
            type="image/x-icon"
            href="{{ asset('images/logo.svg') }}" 
            rel="shortcut icon"
            sizes="16x16"
        />
        
        @stack('styles')
    </head>

    <body>
            {{-- Flash Message Blade Component --}}
            {{-- <x-admin::flash-group /> --}}
            <div id="app" class="h-full">
                <div class="container">
                    <div class="flex [&amp;>*]:w-[50%] justify-center items-center">
                        <v-server-requirements></v-server-requirements>
                    </div>
                </div>
            </div>

            @pushOnce('scripts')
                <script type="text/x-template" id="v-server-requirements-template">
                    <!-- Left Side Welcome to Installation -->
                    <div class="flex flex-col justify-center">
                        <div class="grid items-end max-w-[362px] m-auto h-[100vh]">
                            <div class="grid gap-[16px]">
                                <img src="../images/bagisto-logo.svg" alt="Bagisto Logo">
                                <div class="grid gap-[6px]">
                                    <p class="text-gray-800 text-[20px] font-bold">
                                        Welcome to Installation
                                    </p>

                                    <p class="text-gray-600 text-[14px]">
                                        We are happy to see you here!
                                    </p>
                                </div>

                                <p class="text-gray-600 text-[14px]">
                                    Bagisto installation typically involves several steps.
                                    Here's a general outline of the installation process for Bagisto:
                                </p>

                                <div class="grid gap-[12px]">
                                    <div class="flex gap-[4px] text-[14px] font-bold text-gray-600">
                                        <span class="icon-processing text-[20px]"></span>

                                        <p>Server Requirments</p>
                                    </div>

                                    <div class="flex gap-[4px] text-[14px] text-gray-600">
                                        <span class="icon-checkbox text-[20px]"></span>
                                        <p>Environment Configuration</p>
                                    </div>
                                    <div class="flex gap-[4px] text-[14px] text-gray-600">
                                        <span class="icon-checkbox text-[20px]"></span>
                                        <p>Ready for Installation</p>
                                    </div>
                                    <div class="flex gap-[4px] text-[14px] text-gray-600">
                                        <span class="icon-tick text-[20px] text-green-500"></span>
                                        <p>Create Administrator</p>
                                    </div>
                                    <div class="flex gap-[4px] text-[14px] text-gray-600">
                                        <span class="icon-checkbox text-[20px]"></span>
                                        <p>Email Configuration</p>
                                    </div>
                                    <div class="flex gap-[4px] text-[14px] text-gray-600">
                                        <span class="icon-checkbox text-[20px]"></span>
                                        <p>Installation Completed</p>
                                    </div>
                                </div>
                            </div>
                            <p class="place-self-end w-full text-left mb-[24px]"><a class="text-blue-500" href="https://bagisto.com/en/">Bagisto</a> a Community Project by <a class="text-blue-500" href="https://webkul.com/">Webkul</a></p>
                        </div>
                    </div>

                    <!-- Right Side Components -->
                    <!-- Server Requirements -->
                    <div class="w-full max-w-[568px] bg-white rounded-[8px] shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)] border-[1px] border-gray-300" v-if="serverRequirements">
                        <div class="flex justify-between items-center gap-[10px] px-[16px] py-[11px] border-b-[1px] border-gray-300">
                            <p class="text-[20px] text-gray-800 font-bold">Server Requirements</p>
                        </div>

                        <div class="flex flex-col gap-[15px] px-[30px] py-[16px] border-b-[1px] border-gray-300 h-[486px] overflow-y-auto">
                            <div class="flex gap-[4px]">
                                <span class="{{ $phpVersion['supported'] ? 'icon-tick text-[20px] text-green-500' : '' }}"></span>

                                <p class="text-[14px] text-gray-600 font-semibold">
                                    PHP <span class="font-normal">(8.1 or higher)</span>
                                </p>
                            </div>

                            @foreach ($requirements['requirements'] as $requirement)
                                @foreach ($requirement as $key => $item)
                                    <div class="flex gap-[4px]">
                                        <span class="{{ $item ? 'icon-tick text-green-500' : 'icon-cross text-red-500' }} text-[20px]"></span>
                                        <p class="text-[14px] text-gray-600 font-semibold">
                                            {{ $key }}
                                        </p>
                                    </div>    
                                @endforeach
                            @endforeach
                        </div>

                        @php $hasRequirement = false; @endphp

                        <div class="flex px-[16px] py-[10px] justify-end items-center">
                            @foreach ($requirements['requirements']['php'] as $value)
                                @if (! $value)
                                    @php $hasRequirement = true; @endphp
                                @endif
                            @endforeach

                            <div 
                                class="{{ $hasRequirement ? 'opacity-50 cursor-not-allowed' : ''}} px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer {{ $hasRequirement ?: 'hover:opacity-90' }}"
                                @click="moveToEnvSetup"
                            >
                                Continue
                            </div>
                        </div>
                    </div>

                    <!-- Environment Configuration .ENV -->
                    <div
                        class="w-full max-w-[568px] bg-white rounded-[8px] shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)] border-[1px] border-gray-300"
                        v-if="envSetup"
                    >
                        <div class="flex justify-between items-center gap-[10px] px-[16px] py-[11px] border-b-[1px] border-gray-300">
                            <p class="text-[20px] text-gray-800 font-bold">
                                Environment Configuration
                            </p>
                        </div>

                        <div class="flex flex-col gap-[12px] px-[30px] py-[16px] border-b-[1px] border-gray-300 h-[484px] overflow-y-auto">
                            <form ref="form">
                                <div class="mb-[10px]">
                                    <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                        Application Name*
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                        value="Bagisto"
                                        placeholder="Bagisto"
                                    />
                                </div>

                                <div class="mb-[10px]">
                                    <label
                                        class="block text-[12px] text-gray-800 font-medium leading-[24px]"
                                        for="username"
                                    >
                                        Default URL*
                                    </label>

                                    <input
                                        type="text"
                                        name="url"
                                        class="w-full text-[14px] text-gray-600 appearance-none border rounded-[6px] py-2 px-3 transition-all hover:border-gray-400"
                                        value="https://localhost"
                                        placeholder="https://localhost"
                                    />
                                </div>
        
                                <div class="w-full mb-[10px]">
                                    <label  
                                        class="block text-[12px] text-gray-800 font-medium leading-[24px]"
                                        for="username"
                                    >
                                        Default Currency*
                                    </label>

                                    <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                        US Dollar<span class="icon-arrow-down text-[24px]"></span>
                                    </div>
                                </div>
        
                                <div class="w-full mb-[10px]">
                                    <label
                                        class="block text-[12px] text-gray-800 font-medium leading-[24px]"
                                        for="username"
                                    >
                                        Default Timezone*
                                    </label>

                                    <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                        Africa/Abidgan<span class="icon-arrow-down text-[24px]"></span>
                                    </div>
                                </div>
        
                                <div class="w-full mb-[10px]">
                                    <label
                                        class="block text-[12px] text-gray-800 font-medium leading-[24px]"
                                        for="username"
                                    >
                                        Default Locale*
                                    </label>

                                    <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                        English<span class="icon-arrow-down text-[24px]"></span>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="flex px-[16px] py-[10px] justify-between items-center">
                            <div
                                class="text-[12px] text-blue-600 font-semibold cursor-pointer"
                                @click="back"
                            >
                                Back
                            </div>

                            <div
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer hover:opacity-90"
                                @click="moveToEnvDatabase"
                            >
                                Continue</div>
                        </div>
                    </div>

                    <!-- Environment Configuration Database -->
                    <div
                        class="w-full max-w-[568px] bg-white rounded-[8px] shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)] border-[1px] border-gray-300"
                        v-if="envDatabase"
                    >
                        <div class="flex justify-between items-center gap-[10px] px-[16px] py-[11px] border-b-[1px] border-gray-300">
                            <p class="text-[20px] text-gray-800 font-bold">
                                Environment Configuration
                            </p>
                        </div>

                        <div class="flex flex-col gap-[12px] px-[30px] py-[16px] border-b-[1px] border-gray-300 h-[484px] overflow-y-auto">
                            <div class="mb-[10px]">
                                <label
                                    class="required block text-[12px] text-gray-800 font-medium leading-[24px]"
                                    for="username"
                                >
                                    Database Connection
                                </label>

                                <input  
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                    type="text"
                                    name="name"
                                    value="Bagisto"
                                    placeholder="Bagisto"
                                >
                            </div>

                            <div class="mb-[10px]">
                                <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                    Default URL*
                                </label>

                                <input
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                    type="text"
                                    name="url"
                                    value="https://localhost"
                                    placeholder="https://localhost"
                                >
                            </div>
    
                            <div class="w-full mb-[10px]">
                                <label class="required block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                    Database Name
                                </label>

                                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                    US Dollar<span class="icon-arrow-down text-[24px]"></span>
                                </div>
                            </div>
    
                            <div class="w-full mb-[10px]">
                                <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                    Database Prefix
                                </label>

                                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                    Africa/Abidgan<span class="icon-arrow-down text-[24px]"></span>
                                </div>
                            </div>
    
                            <div class="w-full mb-[10px]">
                                <label class="required block text-[12px] text-gray-800 font-medium leading-[24px]" for="username">
                                    Database Username
                                </label>

                                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                    English<span class="icon-arrow-down text-[24px]"></span>
                                </div>
                            </div>

                            <div class="w-full mb-[10px]">
                                <label class="required block text-[12px] text-gray-800 font-medium leading-[24px]" for="username">
                                    Database Passeord
                                </label>

                                <input class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-2 px-3 w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400" type="text" placeholder="English" value="English"/>
                            </div>
                        </div>

                        <div class="flex px-[16px] py-[10px] justify-between items-center">
                            <div
                                class="text-[12px] text-blue-600 font-semibold cursor-pointer"
                                @click="back2"
                            >
                                Back
                            </div>

                            <div
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer hover:opacity-90"
                                @click="moveToStartInstallation"
                            >
                                Continue
                            </div>
                        </div>
                    </div>

                    <!-- Ready For Installation -->
                    <div
                        class="w-full max-w-[568px] bg-white rounded-[8px] shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)] border-[1px] border-gray-300"
                        v-if="readyForInstallation"
                    >
                        <div class="flex justify-between items-center gap-[10px] px-[16px] py-[11px] border-b-[1px] border-gray-300">
                            <p class="text-[20px] text-gray-800 font-bold">
                                Installation
                            </p>
                        </div>

                        <div class="flex flex-col gap-[15px] justify-center px-[30px] py-[16px] border-b-[1px] border-gray-300 h-[484px] overflow-y-auto">
                            <div class="flex flex-col gap-[16px]">
                                <p class="text-[18px] text-gray-800 font-semibold">
                                    Bagisto for Installation
                                </p>
    
                                <div class="grid gap-[10px]">
                                    <label class="text-[14px] text-gray-600">
                                        Click the button below to
                                    </label>

                                    <div class="grid gap-[12px]">
                                        <div class="flex gap-[4px] text-[14px] text-gray-600">
                                            <span class="icon-processing text-[20px]"></span>

                                            <p>Create the database table</p>
                                        </div>

                                        <div class="flex gap-[4px] text-[14px] text-gray-600">
                                            <span class="icon-processing text-[20px]"></span>

                                            <p>Populate the database tables</p>
                                        </div>

                                        <div class="flex gap-[4px] text-[14px] text-gray-600">
                                            <span class="icon-processing text-[20px]"></span>

                                            <p>Publishing the vendor files</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex px-[16px] py-[10px] justify-between items-center">
                            <div
                                class="text-[12px] text-blue-600 font-semibold cursor-pointer"
                                @click="back3"
                            >
                                Back
                            </div>

                            <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer hover:opacity-90">
                                Start Installation
                            </div>
                        </div>
                    </div>
                </script>


                <script type="module">
                    app.component('v-server-requirements', {
                        template: '#v-server-requirements-template',
                
                        data() {
                            return {
                                serverRequirements: true,

                                envSetup: false,

                                envDatabase: false,

                                readyForInstallation: false,
                            }
                        },
        
                        methods: {
                            moveToEnvSetup() {
                                this.serverRequirements = false;
                                this.envSetup = true;
                            },

                            moveToEnvDatabase() {
                                this.envDatabase = true;
                                this.envSetup = false;
                            },

                            moveToStartInstallation() {
                                this.readyForInstallation = true,
                                this.envDatabase = false;
                            },

                            back() {
                                this.serverRequirements = true;
                                this.envSetup = false;
                            },

                            back2() {
                                this.envDatabase = false;
                                this.envSetup = true;
                            },

                            back3() {
                                this.readyForInstallation = false;
                                this.envDatabase = true;
                            },
                        },
                    });
                </script>
            @endPushOnce

        @stack('scripts')
    </body>
</html>
