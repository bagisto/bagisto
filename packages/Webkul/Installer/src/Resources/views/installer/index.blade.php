<!DOCTYPE html>
<html
    lang="{{ app()->getLocale() }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'fa', 'he']) ? 'rtl' : 'ltr' }}"
>
    <head>
        <title>
            @lang('installer::app.installer.index.title')
        </title>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="base-url" content="{{ url()->to('/') }}">

        @stack('meta')

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'], 'installer')

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
            href="{{ bagisto_asset('images/installer/favicon.ico', 'installer') }}"
            rel="shortcut icon"
            sizes="16x16"
        />

        @stack('styles')
    </head>

    @php
        $locales = [
            'ar'    => 'arabic',
            'bn'    => 'bengali',
            'de'    => 'german',
            'en'    => 'english',
            'es'    => 'spanish',
            'fa'    => 'persian',
            'fr'    => 'french',
            'he'    => 'hebrew',
            'hi_IN' => 'hindi',
            'it'    => 'italian',
            'ja'    => 'japanese',
            'nl'    => 'dutch',
            'pl'    => 'polish',
            'pt_BR' => 'portuguese',
            'ru'    => 'russian',
            'sin'   => 'sinhala',
            'tr'    => 'turkish',
            'uk'    => 'ukrainian',
            'zh_CN' => 'chinese',
        ];

        $currencies = [
            'AED' => 'united-arab-emirates-dirham',
            'ARS' => 'argentine-peso',
            'AUD' => 'australian-dollar',
            'BDT' => 'bangladeshi-taka',
            'BHD' => 'bahraini-dinar',
            'BRL' => 'brazilian-real',
            'CAD' => 'canadian-dollar',
            'CHF' => 'swiss-franc',
            'CLP' => 'chilean-peso',
            'CNY' => 'chinese-yuan',
            'COP' => 'colombian-peso',
            'CZK' => 'czech-koruna',
            'DKK' => 'danish-krone',
            'DZD' => 'algerian-dinar',
            'EGP' => 'egyptian-pound',
            'EUR' => 'euro',
            'FJD' => 'fijian-dollar',
            'GBP' => 'british-pound-sterling',
            'HKD' => 'hong-kong-dollar',
            'HUF' => 'hungarian-forint',
            'IDR' => 'indonesian-rupiah',
            'ILS' => 'israeli-new-shekel',
            'INR' => 'indian-rupee',
            'JOD' => 'jordanian-dinar',
            'JPY' => 'japanese-yen',
            'KRW' => 'south-korean-won',
            'KWD' => 'kuwaiti-dinar',
            'KZT' => 'kazakhstani-tenge',
            'LBP' => 'lebanese-pound',
            'LKR' => 'sri-lankan-rupee',
            'LYD' => 'libyan-dinar',
            'MAD' => 'moroccan-dirham',
            'MUR' => 'mauritian-rupee',
            'MXN' => 'mexican-peso',
            'MYR' => 'malaysian-ringgit',
            'NGN' => 'nigerian-naira',
            'NOK' => 'norwegian-krone',
            'NPR' => 'nepalese-rupee',
            'NZD' => 'new-zealand-dollar',
            'OMR' => 'omani-rial',
            'PAB' => 'panamanian-balboa',
            'PEN' => 'peruvian-nuevo-sol',
            'PHP' => 'philippine-peso',
            'PKR' => 'pakistani-rupee',
            'PLN' => 'polish-zloty',
            'PYG' => 'paraguayan-guarani',
            'QAR' => 'qatari-rial',
            'RON' => 'romanian-leu',
            'RUB' => 'russian-ruble',
            'SAR' => 'saudi-riyal',
            'SEK' => 'swedish-krona',
            'SGD' => 'singapore-dollar',
            'THB' => 'thai-baht',
            'TND' => 'tunisian-dinar',
            'TRY' => 'turkish-lira',
            'TWD' => 'new-taiwan-dollar',
            'UAH' => 'ukrainian-hryvnia',
            'USD' => 'united-states-dollar',
            'UZS' => 'uzbekistani-som',
            'VEF' => 'venezuelan-bolívar',
            'VND' => 'vietnamese-dong',
            'XAF' => 'cfa-franc-beac',
            'XOF' => 'cfa-franc-bceao',
            'ZAR' => 'south-african-rand',
            'ZMW' => 'zambian-kwacha'
        ];
    @endphp

    <body>
        <div
            id="app"
            class="container-fluide fixed w-full"
        >
            <div class="flex [&amp;>*]:w-[50%] gap-12 justify-center items-center">
                <!-- Vue Component -->
                <v-server-requirements></v-server-requirements>
            </div>
        </div>

        @pushOnce('scripts')
            <script
                type="text/x-template"
                id="v-server-requirements-template"
            >
                <!-- Left Side Welcome to Installation -->
                <div class="flex flex-col justify-center">
                    <div class="m-auto grid h-[100vh] max-w-[362px] items-end">
                        <div class="grid gap-4">
                            <img
                                src="{{ bagisto_asset('images/installer/bagisto-logo.svg', 'installer') }}"
                                alt="@lang('installer::app.installer.index.bagisto-logo')"
                            >

                            <div class="grid gap-1.5">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.installation-title')
                                </p>

                                <p class="text-sm text-gray-600">
                                    @lang('installer::app.installer.index.installation-info')
                                </p>
                            </div>

                            <div class="[&>*]:flex [&>*]:items-center [&>*]:gap-1 grid gap-3 text-sm text-gray-600">
                                <!-- Start -->
                                <div :class="[stepStates.start == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.start !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.start === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>@lang('installer::app.installer.index.start.main')</p>
                                </div>

                                <!-- Server Environment -->
                                <div :class="[stepStates.systemRequirements == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.systemRequirements !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.systemRequirements === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>@lang('installer::app.installer.index.server-requirements.title')</p>
                                </div>

                                <!-- ENV Database Configuration -->
                                <div :class="[stepStates.envDatabase == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.envDatabase !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.envDatabase === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>
                                        @lang('installer::app.installer.index.environment-configuration.title')
                                    </p>
                                </div>

                                <!-- Ready For Installation -->
                                <div :class="[stepStates.readyForInstallation == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.readyForInstallation !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.readyForInstallation === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>@lang('installer::app.installer.index.ready-for-installation.title')</p>
                                </div>

                                <!-- Create Sample Product -->
                                <div :class="[stepStates.createSampleProducts == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.createSampleProducts !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.createSampleProducts === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>@lang('installer::app.installer.index.sample-products.title')</p>
                                </div>

                                <!-- Create Admin Configuration -->
                                <div :class="[stepStates.createAdmin == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.createAdmin !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.createAdmin === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>@lang('installer::app.installer.index.create-administrator.title')</p>
                                </div>

                                <!-- Installation Completed -->
                                <div :class="[stepStates.installationCompleted == 'active' ? 'font-bold' : '']">
                                    <template v-if="stepStates.installationCompleted !== 'complete'">
                                        <span
                                            class="text-xl"
                                            :class="stepStates.installationCompleted === 'pending' ? 'icon-checkbox-normal' : 'icon-right'"
                                        >
                                        </span>
                                    </template>

                                    <template v-else>
                                        <span class="icon-tick text-green-500"></span>
                                    </template>

                                    <p>@lang('installer::app.installer.index.installation-completed.title')</p>
                                </div>
                            </div>
                        </div>

                        <p class="mb-6 w-full place-self-end text-left">
                            <a
                                class="bg-white text-blue-600 underline"
                                href="https://bagisto.com/en/"
                            >
                                @lang('installer::app.installer.index.bagisto')
                            </a>

                            <span>@lang('installer::app.installer.index.bagisto-info')</span>

                            <a
                                class="bg-white text-blue-600 underline"
                                href="https://webkul.com/"
                            >
                                @lang('installer::app.installer.index.webkul')
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Right Side Components -->
                <!-- Start -->
                <div
                    class="w-full max-w-[568px] rounded-lg border-[1px] border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'start'"
                >
                    <x-installer::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="start"
                    >
                        <form
                            @submit.prevent="handleSubmit($event, setLocale)"
                            enctype="multipart/form-data"
                            ref="multiLocaleForm"
                        >
                            <div class="border-b border-gray-300 px-4 py-3">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.start.welcome-title')
                                </p>
                            </div>

                            <div class="flex h-[388px] flex-col items-center gap-3 overflow-y-auto px-7 py-4">
                                <div class="container overflow-hidden">
                                    <div class="flex h-[100px] flex-col justify-end gap-3">
                                        <p class="text-center text-sm text-gray-600">
                                            @lang('installer::app.installer.index.installation-description')
                                        </p>
                                    </div>

                                    <div class="flex h-72 flex-col justify-center gap-3 overflow-y-auto px-7 py-4">
                                        <!-- Application Name -->
                                        <x-installer::form.control-group class="mb-2.5">
                                            <x-installer::form.control-group.label>
                                                @lang('Installation Wizard language')
                                            </x-installer::form.control-group.label>

                                            <x-installer::form.control-group.control
                                                type="select"
                                                name="locale"
                                                rules="required"
                                                :value="app()->getLocale()"
                                                :label="trans('installer::app.installer.index.start.locale')"
                                                @change="$refs.multiLocaleForm.submit();"
                                            >
                                                <option
                                                    value=""
                                                    disabled
                                                >
                                                    @lang('installer::app.installer.index.start.select-locale')
                                                </option>

                                                @foreach ($locales as $value => $label)
                                                    <option value="{{ $value }}">
                                                        {{ ucfirst($label) }}
                                                    </option>
                                                @endforeach
                                            </x-installer::form.control-group.control>

                                            <x-installer::form.control-group.error control-name="locale" />
                                        </x-installer::form.control-group>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end px-4 py-3">
                                <button
                                    type="button"
                                    class="primary-button"
                                    tabindex="0"
                                    @click="nextForm"
                                >
                                    @lang('installer::app.installer.index.continue')
                                </button>
                            </div>
                        </form>
                    </x-installer::form>
                </div>

                <!-- System Requirements -->
                <div
                    class="w-full max-w-[568px] rounded-lg border border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'systemRequirements'"
                >
                    <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                        <p class="text-xl font-bold text-gray-800">
                            @lang('installer::app.installer.index.server-requirements.title')
                        </p>
                    </div>

                    <div class="flex h-[486px] flex-col gap-4 overflow-y-auto border-b border-gray-300 px-7 py-4">
                        <div class="flex items-center gap-1">
                            <span class="{{ $phpVersion['supported'] ? 'icon-tick text-xl text-green-500' : '' }}"></span>

                            <p class="text-sm font-semibold text-gray-600">
                                @lang('installer::app.installer.index.server-requirements.php') <span class="font-normal">(@lang('installer::app.installer.index.server-requirements.php-version'))</span>
                            </p>
                        </div>

                        @foreach ($requirements['requirements'] as $requirement)
                            @foreach ($requirement as $key => $item)
                                <div class="flex items-center gap-1">
                                    <span class="{{ $item ? 'icon-tick text-green-500' : 'icon-cross text-red-500' }} text-xl"></span>

                                    <p class="text-sm font-semibold text-gray-600">
                                        @lang('installer::app.installer.index.server-requirements.' . $key)
                                    </p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    @php
                        $hasRequirement = false;

                        foreach ($requirements['requirements']['php'] as $value) {
                            if (!$value) {
                                $hasRequirement = true;
                                break;
                            }
                        }
                    @endphp

                    <div class="flex items-center justify-between px-4 py-2.5">
                        <div
                            class="cursor-pointer text-base font-semibold text-blue-600"
                            role="button"
                            aria-label="@lang('installer::app.installer.index.back')"
                            tabindex="0"
                            @click="back"
                        >
                            @lang('installer::app.installer.index.back')
                        </div>

                        <div
                            class="{{ $hasRequirement ? 'opacity-50 cursor-not-allowed' : ''}} px-3 py-1.5 bg-blue-600 border border-blue-700 rounded-md text-gray-50 font-semibold cursor-pointer {{ $hasRequirement ?: 'hover:opacity-90' }}"
                            @click="nextForm"
                            tabindex="0"
                        >
                            @lang('installer::app.installer.index.continue')
                        </div>
                    </div>
                </div>

                <!-- Environment Configuration Database -->
                <div
                    class="w-full max-w-[568px] rounded-lg border-[1px] border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'envDatabase'"
                >
                    <x-installer::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="envDatabase"
                    >
                        <form
                            @submit.prevent="handleSubmit($event, FormSubmit)"
                            enctype="multipart/form-data"
                        >
                            <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.environment-configuration.title')
                                </p>
                            </div>

                            <div class="flex h-[484px] flex-col gap-3 overflow-y-auto border-b border-gray-300 px-7 py-4">
                                <!-- Database Connection-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.database-connection')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="select"
                                        name="db_connection"
                                        ::value="envData.db_connection ?? 'mysql'"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-connection')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-connection')"
                                    >
                                        <option
                                            value="mysql"
                                            selected
                                        >
                                            @lang('installer::app.installer.index.environment-configuration.mysql')
                                        </option>
                                    </x-installer::form.control-group.control>

                                    <x-installer::form.control-group.error control-name="db_connection" />
                                </x-installer::form.control-group>

                                <!-- Database Hostname-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.database-hostname')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="db_hostname"
                                        ::value="envData.db_hostname ?? '127.0.0.1'"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-hostname')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-hostname')"
                                    />

                                    <x-installer::form.control-group.error control-name="db_hostname" />
                                </x-installer::form.control-group>

                                <!-- Database Port-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.database-port')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="db_port"
                                        ::value="envData.db_port ?? '3306'"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-port')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-port')"
                                    />

                                    <x-installer::form.control-group.error control-name="db_port" />
                                </x-installer::form.control-group>

                                <!-- Database name-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.database-name')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="db_name"
                                        ::value="envData.db_name"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-name')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-name')"
                                    />

                                    <x-installer::form.control-group.error control-name="db_name" />
                                </x-installer::form.control-group>

                                <!-- Database Prefix-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label>
                                        @lang('installer::app.installer.index.environment-configuration.database-prefix')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="db_prefix"
                                        ::value="envData.db_prefix"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-prefix')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-prefix')"
                                    />

                                    <x-installer::form.control-group.error control-name="db_prefix" />
                                </x-installer::form.control-group>

                                <!-- Database Username-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.database-username')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="db_username"
                                        ::value="envData.db_username"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-username')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-username')"
                                    />

                                    <x-installer::form.control-group.error control-name="db_username" />
                                </x-installer::form.control-group>

                                <!-- Database Password-->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label>
                                        @lang('installer::app.installer.index.environment-configuration.database-password')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="password"
                                        name="db_password"
                                        ::value="envData.db_password"
                                        :label="trans('installer::app.installer.index.environment-configuration.database-password')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.database-password')"
                                    />

                                    <x-installer::form.control-group.error control-name="db_password" />
                                </x-installer::form.control-group>
                            </div>

                            <div class="flex items-center justify-between px-4 py-2.5">
                                <div
                                    class="cursor-pointer text-base font-semibold text-blue-600"
                                    role="button"
                                    :aria-label="@lang('installer::app.installer.index.back')"
                                    tabindex="0"
                                    @click="back"
                                >
                                    @lang('installer::app.installer.index.back')
                                </div>

                                <button
                                    type="submit"
                                    class="primary-button"
                                    tabindex="0"
                                >
                                    @lang('installer::app.installer.index.continue')
                                </button>
                            </div>
                        </form>
                    </x-installer::form>
                </div>

                <!-- Ready For Installation -->
                <div
                    class="w-full max-w-[568px] rounded-lg border-[1px] border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'readyForInstallation'"
                >
                    <x-installer::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="envDatabase"
                    >
                        <form
                            @submit.prevent="handleSubmit($event, FormSubmit)"
                            enctype="multipart/form-data"
                        >
                            <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.ready-for-installation.install')
                                </p>
                            </div>

                            <div class="flex h-[484px] flex-col justify-center gap-4 overflow-y-auto border-b border-gray-300 px-7 py-4">
                                <div class="grid gap-1">
                                    <p class="text-lg font-semibold text-gray-800">
                                        @lang('installer::app.installer.index.ready-for-installation.install-info')
                                    </p>

                                    <div class="grid gap-4">
                                        <label class="text-sm text-gray-600">
                                            @lang('installer::app.installer.index.ready-for-installation.install-info-button')
                                        </label>

                                        <div class="grid gap-3">
                                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                                <span class="icon-right text-xl"></span>

                                                <p>@lang('installer::app.installer.index.ready-for-installation.create-databsae-table')</p>
                                            </div>

                                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                                <span class="icon-right text-xl"></span>

                                                <p>@lang('installer::app.installer.index.ready-for-installation.populate-database-table')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-4 py-2.5">
                                <div
                                    class="cursor-pointer text-base font-semibold text-blue-600"
                                    role="button"
                                    :aria-label="@lang('installer::app.installer.index.back')"
                                    tabindex="0"
                                    @click="back"
                                >
                                    Back
                                </div>

                                <button
                                    type="submit"
                                    class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3 py-1.5 font-semibold text-gray-50 hover:opacity-90"
                                >
                                    @lang('installer::app.installer.index.ready-for-installation.start-installation')
                                </button>
                            </div>
                        </form>
                    </x-installer::form>
                </div>

                <!-- Installation Processing -->
                <div
                    class="w-full max-w-[568px] rounded-lg border-[1px] border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'installProgress'"
                >
                    <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                        <p class="text-xl font-bold text-gray-800">
                            @lang('installer::app.installer.index.installation-processing.title')
                        </p>
                    </div>

                    <div class="flex h-[484px] flex-col justify-center gap-4 overflow-y-auto px-7 py-4">
                        <div class="flex flex-col gap-4">
                            <p class="text-lg font-bold text-gray-800">
                                @lang('installer::app.installer.index.installation-processing.bagisto')
                            </p>

                            <div class="grid gap-2.5">
                                <!-- Spinner -->
                                <img
                                    class="text-navyBlue h-5 w-5 animate-spin"
                                    src="{{ bagisto_asset('images/installer/spinner.svg', 'installer') }}"
                                    alt="Loading"
                                />

                                <p class="text-sm text-gray-600">
                                    @lang('installer::app.installer.index.installation-processing.bagisto-info')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Environment Configuration .ENV -->
                <div
                    class="w-full max-w-[568px] rounded-lg border-[1px] border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'envConfiguration'"
                >
                    <x-installer::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="envSetup"
                    >
                        <form
                            @submit.prevent="handleSubmit($event, nextForm)"
                            enctype="multipart/form-data"
                        >
                            <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.environment-configuration.title')
                                </p>
                            </div>

                            <div class="flex h-[484px] flex-col gap-3 overflow-y-auto border-b border-gray-300 px-7 py-4">
                                <!-- Application Name -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.application-name')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="app_name"
                                        ::value="envData.app_name ?? 'Bagisto'"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.application-name')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.bagisto')"
                                    />

                                    <x-installer::form.control-group.error control-name="app_name" />
                                </x-installer::form.control-group>

                                <!-- Application Default URL -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.default-url')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="app_url"
                                        ::value="envData.app_url ?? 'https://localhost'"
                                        rules="required"
                                        :label="trans('installer::app.installer.index.environment-configuration.default-url')"
                                        :placeholder="trans('installer::app.installer.index.environment-configuration.default-url-link')"
                                    />

                                    <x-installer::form.control-group.error control-name="app_url" />
                                </x-installer::form.control-group>

                                <!-- Application Default Timezone -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.environment-configuration.default-timezone')
                                    </x-installer::form.control-group.label>

                                    @php
                                        date_default_timezone_set('UTC');

                                        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

                                        $current = date_default_timezone_get();
                                    @endphp

                                    <x-installer::form.control-group.control
                                        type="select"
                                        name="app_timezone"
                                        ::value="envData.app_timezone ?? $current"
                                        rules="required"
                                        :aria-label="trans('installer::app.installer.index.environment-configuration.default-timezone')"
                                        :label="trans('installer::app.installer.index.environment-configuration.default-timezone')"
                                    >
                                        <option
                                            value=""
                                            disabled
                                        >
                                            @lang('installer::app.installer.index.environment-configuration.select-timezone')
                                        </option>

                                        @foreach($tzlist as $key => $value)
                                            <option
                                                value="{{ $value }}"
                                                {{ $value === $current ? 'selected' : '' }}
                                            >
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </x-installer::form.control-group.control>

                                    <x-installer::form.control-group.error control-name="app_timezone" />
                                </x-installer::form.control-group>

                                <div
                                    class="p-1.5"
                                    :style="warning['container'], warning['message']"
                                >
                                    <i class="icon-limited !text-black"></i>

                                    @lang('installer::app.installer.index.environment-configuration.warning-message')
                                </div>

                                <div class="grid grid-cols-2 gap-2.5">
                                    <!-- Application Default Locale -->
                                    <x-installer::form.control-group class="w-full">
                                        <x-installer::form.control-group.label class="required">
                                            @lang('installer::app.installer.index.environment-configuration.default-locale')
                                        </x-installer::form.control-group.label>

                                        <x-installer::form.control-group.control
                                            type="select"
                                            name="app_locale"
                                            value="{{ app()->getLocale() }}"
                                            rules="required"
                                            :aria-label="trans('installer::app.installer.index.environment-configuration.default-locale')"
                                            :label="trans('installer::app.installer.index.environment-configuration.default-locale')"
                                        >
                                            @foreach ($locales as $value => $label)
                                                <option value="{{ $value }}">
                                                    @lang("installer::app.installer.index.$label")
                                                </option>
                                            @endforeach
                                        </x-installer::form.control-group.control>

                                        <x-installer::form.control-group.error control-name="app_locale" />
                                    </x-installer::form.control-group>

                                    <!-- Application Default Currency -->
                                    <x-installer::form.control-group class="w-full">
                                        <x-installer::form.control-group.label class="required">
                                            @lang('installer::app.installer.index.environment-configuration.default-currency')
                                        </x-installer::form.control-group.label>

                                        <x-installer::form.control-group.control
                                            type="select"
                                            name="app_currency"
                                            ::value="envData.app_currency ?? 'USD'"
                                            :aria-label="trans('installer::app.installer.index.environment-configuration.default-currency')"
                                            rules="required"
                                            :label="trans('installer::app.installer.index.environment-configuration.default-currency')"
                                        >
                                            <option value="" disabled>Select Currencies</option>

                                            @foreach ($currencies as $value => $label)
                                                <option value="{{ $value }}" @if($value == 'USD') selected @endif>
                                                    @lang("installer::app.installer.index.environment-configuration.$label")
                                                </option>
                                            @endforeach
                                        </x-installer::form.control-group.control>

                                        <x-installer::form.control-group.error control-name="app_currency" />
                                    </x-installer::form.control-group>
                                </div>

                                <div class="grid grid-cols-2 gap-2.5">
                                    <!-- Allowed Locales -->
                                    <x-installer::form.control-group class="w-full">
                                        <x-installer::form.control-group.label class="required">
                                            @lang('installer::app.installer.index.environment-configuration.allowed-locales')
                                        </x-installer::form.control-group.label>

                                        @foreach ($locales as $key => $locale)
                                            <x-installer::form.control-group class="!mb-0 flex w-max cursor-pointer select-none items-center gap-1">
                                                @php
                                                    $selectedOption = ($key == config('app.locale'));
                                                @endphp

                                                <x-installer::form.control-group.control
                                                    type="hidden"
                                                    :name="$key"
                                                    :value="(bool) $selectedOption"
                                                />

                                                <x-installer::form.control-group.control
                                                    type="checkbox"
                                                    :id="'allowed_locale[' . $key . ']'"
                                                    :name="$key"
                                                    :for="'allowed_locale[' . $key . ']'"
                                                    value="1"
                                                    :checked="(bool) $selectedOption"
                                                    :disabled="(bool) $selectedOption"
                                                    @change="pushAllowedLocales"
                                                />

                                                <x-installer::form.control-group.label
                                                    for="allowed_locale[{{ $key }}]"
                                                    class="cursor-pointer !text-sm !font-semibold"
                                                >
                                                    @lang("installer::app.installer.index.$locale")
                                                </x-installer::form.control-group.label>
                                            </x-installer::form.control-group>
                                        @endforeach
                                    </x-installer::form.control-group>

                                    <!-- Allowed Currencies -->
                                    <x-installer::form.control-group class="w-full">
                                        <x-installer::form.control-group.label class="required">
                                            @lang('installer::app.installer.index.environment-configuration.allowed-currencies')
                                        </x-installer::form.control-group.label>

                                        @foreach ($currencies as $key => $currency)
                                            <x-installer::form.control-group class="!mb-0 flex w-max cursor-pointer select-none items-center gap-1">
                                                @php
                                                    $selectedOption = $key == config('app.currency');
                                                @endphp

                                                <x-installer::form.control-group.control
                                                    type="hidden"
                                                    :name="$key"
                                                    :value="$selectedOption"
                                                />

                                                <x-installer::form.control-group.control
                                                    type="checkbox"
                                                    :id="'currency[' . $key . ']'"
                                                    :name="$key"
                                                    value="1"
                                                    :for="'currency[' . $key . ']'"
                                                    :checked="$selectedOption"
                                                    :disabled="$selectedOption"
                                                    @change="pushAllowedCurrency"
                                                />

                                                <x-installer::form.control-group.label
                                                    for="currency[{{ $key }}]"
                                                    class="cursor-pointer !text-sm !font-semibold"
                                                >
                                                    @lang("installer::app.installer.index.environment-configuration.$currency")
                                                </x-installer::form.control-group.label>
                                            </x-installer::form.control-group>
                                        @endforeach
                                    </x-installer::form.control-group>
                                </div>
                            </div>

                            <div class="flex items-center justify-end px-4 py-2.5">
                                <x-installer::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('installer::app.installer.index.continue')"
                                    tabindex="0"
                                    ::loading="isLoading"
                                    ::disabled="isLoading"
                                />
                            </div>
                        </form>
                    </x-installer::form>
                </div>

                <!-- Create Sample Products -->
                <div
                    class="w-full max-w-[568px] rounded-lg border border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'createSampleProducts'"
                >
                    <x-installer::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="createSampleProducts"
                    >
                        <form
                            @submit.prevent="handleSubmit($event, FormSubmit)"
                            enctype="multipart/form-data"
                        >
                            <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.sample-products.title')
                                </p>
                            </div>

                            <div class="flex h-[484px] flex-col gap-3 overflow-y-auto border-b border-gray-300 px-7 py-4">
                                <!-- Sample Products -->
                                <x-admin::form.control-group.label>
                                    @lang("installer::app.installer.index.sample-products.sample-products")
                                </x-admin::form.control-group.label>

                                <x-installer::form.control-group.control
                                    type="select"
                                    id="sample_products"
                                    name="sample_products"
                                    for="sample_products"
                                    :value="0"
                                >
                                    <option value="1">
                                        @lang('installer::app.installer.index.sample-products.yes')
                                    </option>

                                    <option value="0">
                                        @lang('installer::app.installer.index.sample-products.no')
                                    </option>
                                </x-installer::form.control-group.control>

                                <a
                                    href="{{ Storage::disk('public')->url('data-transfer/samples/products.csv') }}"
                                    download="products.csv"
                                    id="source-sample-link"
                                    class="mt-1 cursor-pointer text-right text-sm text-blue-600 transition-all hover:underline"
                                >
                                    @lang('installer::app.installer.index.sample-products.download-sample')
                                </a>
                            </div>

                            <div class="flex items-center justify-end px-4 py-2.5">
                                <x-installer::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('installer::app.installer.index.continue')"
                                    tabindex="0"
                                    ::loading="isLoading"
                                    ::disabled="isLoading"
                                />
                             </div>
                        </form>
                    </x-installer::form>
                </div>

                <!-- Create Administrator -->
                <div
                    class="w-full max-w-[568px] rounded-lg border border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'createAdmin'"
                >
                    <x-installer::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="createAdmin"
                    >
                        <form
                            @submit.prevent="handleSubmit($event, FormSubmit)"
                            enctype="multipart/form-data"
                        >
                            <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                                <p class="text-xl font-bold text-gray-800">
                                    @lang('installer::app.installer.index.create-administrator.title')
                                </p>
                            </div>

                            <div class="flex h-[484px] flex-col gap-3 overflow-y-auto border-b border-gray-300 px-7 py-4">
                                <!-- Admin -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.create-administrator.admin')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="admin"
                                        rules="required"
                                        value="Admin"
                                        :label="trans('installer::app.installer.index.create-administrator.admin')"
                                        :placeholder="trans('installer::app.installer.index.create-administrator.bagisto')"
                                    />

                                    <x-installer::form.control-group.error control-name="admin" />
                                </x-installer::form.control-group>

                                <!-- Email -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.create-administrator.email')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="text"
                                        name="email"
                                        rules="required"
                                        value="admin@example.com"
                                        :label="trans('installer::app.installer.index.create-administrator.email')"
                                        :placeholder="trans('installer::app.installer.index.create-administrator.email-address')"
                                    />

                                    <x-installer::form.control-group.error control-name="email" />
                                </x-installer::form.control-group>

                                <!-- Password -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.create-administrator.password')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="password"
                                        name="password"
                                        rules="required|min:6"
                                        :value="old('password')"
                                        :label="trans('installer::app.installer.index.create-administrator.password')"
                                    />

                                    <x-installer::form.control-group.error control-name="password" />
                                </x-installer::form.control-group>

                                <!-- Confirm Password -->
                                <x-installer::form.control-group class="mb-2.5">
                                    <x-installer::form.control-group.label class="required">
                                        @lang('installer::app.installer.index.create-administrator.confirm-password')
                                    </x-installer::form.control-group.label>

                                    <x-installer::form.control-group.control
                                        type="password"
                                        name="confirm_password"
                                        rules="required|confirmed:@password"
                                        :value="old('confirm_password')"
                                        :label="trans('installer::app.installer.index.create-administrator.confirm-password')"
                                    />

                                    <x-installer::form.control-group.error control-name="confirm_password" />
                                </x-installer::form.control-group>
                            </div>

                            <div class="flex items-center justify-end px-4 py-2.5">
                                <button
                                    type="submit"
                                    class="primary-button"
                                    tabindex="0"
                                >
                                    @lang('installer::app.installer.index.continue')
                                </button>
                            </div>

                        </form>
                    </x-installer::form>
                </div>

                <!-- Installation Completed -->
                <div
                    class="w-full max-w-[568px] rounded-lg border border-gray-300 bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
                    v-if="currentStep == 'installationCompleted'"
                >
                    <div class="flex items-center justify-between gap-2.5 border-b border-gray-300 px-4 py-3">
                        <p class="text-xl font-bold text-gray-800">
                            @lang('installer::app.installer.index.installation-completed.title')
                        </p>
                    </div>

                    <div class="flex h-[484px] flex-col justify-center gap-4 overflow-y-auto border-b border-gray-300 px-7 py-4">
                        <div class="flex flex-col gap-4">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full border border-green-500">
                                <span class="icon-tick text-xl font-semibold text-green-500"></span>
                            </div>

                            <div class="grid gap-2.5">
                                <p class="text-lg font-semibold text-gray-800">
                                    @lang('installer::app.installer.index.installation-completed.title')
                                </p>

                                <p class="text-sm text-gray-600">
                                    @lang('installer::app.installer.index.installation-completed.title-info')
                                </p>

                                <!-- Admin & Shop both buttons -->
                                <div class="flex items-center gap-4">
                                    <a
                                        href="{{ URL('/admin/login')}}"
                                        class="cursor-pointer rounded-md border border-blue-700 bg-white px-3 py-1.5 font-semibold text-blue-600 hover:opacity-90"
                                    >
                                        @lang('installer::app.installer.index.installation-completed.admin-panel')
                                    </a>

                                    <a
                                        href="{{ URL('/')}}"
                                        class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3 py-1.5 font-semibold text-gray-50 hover:opacity-90"
                                    >
                                        @lang('installer::app.installer.index.installation-completed.customer-panel')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-4 py-2.5">
                        <a
                            href="https://forums.bagisto.com"
                            class="cursor-pointer text-xs font-semibold text-blue-600"
                        >
                            @lang('installer::app.installer.index.installation-completed.bagisto-forums')
                        </a>

                        <a
                            href="https://bagisto.com/en/extensions"
                            class="cursor-pointer rounded-md border border-blue-700 bg-white px-3 py-1.5 font-semibold text-blue-600 hover:opacity-90"
                        >
                            @lang('installer::app.installer.index.installation-completed.explore-bagisto-extensions')
                        </a>
                    </div>
                </div>
            </script>

            <script type="module">
                app.component('v-server-requirements', {
                    template: '#v-server-requirements-template',

                    data() {
                        return {
                            step: '',

                            currentStep: 'start',

                            envData: {},

                            locales: {
                                allowed: [],
                            },

                            currencies: {
                                allowed: [],
                            },

                            stepStates: {
                                start: 'active',
                                systemRequirements: 'pending',
                                envDatabase: 'pending',
                                readyForInstallation: 'pending',
                                envConfiguration: 'pending',
                                createSampleProducts: 'pending',
                                createAdmin: 'pending',
                                installationCompleted: 'pending',
                            },

                            steps: [
                                'start',
                                'systemRequirements',
                                'envDatabase',
                                'readyForInstallation',
                                'installProgress',
                                'envConfiguration',
                                'createSampleProducts',
                                'createAdmin',
                                'installationCompleted',
                            ],

                            warning: {
                                container: 'background: #fde68a',

                                message: 'color: #1F2937',
                            },

                            isLoading: false,
                        }
                    },

                    mounted() {
                        const preventUnload = (event) => {
                            event.preventDefault();
                        };

                        window.addEventListener('beforeunload', preventUnload);
                    },

                    methods: {
                        FormSubmit(params, { setErrors }) {
                            const stepActions = {
                                envDatabase: () => {
                                    if (params.db_connection === 'mysql') {
                                        this.completeStep('envDatabase', 'readyForInstallation', 'active', 'complete', setErrors);

                                        this.envData = { ...this.envData, ...params };
                                    } else {
                                        setErrors({ 'db_connection': ["Bagisto currently supports MySQL only."] });
                                    }
                                },

                                readyForInstallation: (setErrors) => {
                                    this.currentStep = 'installProgress';

                                    this.startMigration(setErrors);
                                },

                                createSampleProducts: (setErrors) => {
                                    this.createSampleProducts(params, setErrors);
                                },

                                createAdmin: (setErrors) => {
                                    this.isLoading = true;

                                    this.saveAdmin(params, setErrors);
                                },
                            };

                            const index = this.steps.find(step => step === this.currentStep);

                            if (stepActions[index]) {
                                stepActions[index]();
                            }
                        },

                        nextForm(params) {
                            const stepActions = {
                                start: () => {
                                    this.completeStep('start', 'systemRequirements', 'active', 'complete');
                                },

                                systemRequirements: () => {
                                    this.completeStep('systemRequirements', 'envDatabase', 'active', 'complete');

                                    this.currentStep = 'envDatabase';
                                },

                                envConfiguration: () => {
                                    this.envData = { ...params };

                                    let data = {
                                        allowed_locales: this.locales.allowed,
                                        allowed_currencies: this.currencies.allowed,
                                    };

                                    this.startSeeding(data, this.envData);
                                },

                            };

                            const index = this.steps.find(step => step === this.currentStep);

                            if (stepActions[index]) {
                                stepActions[index]();
                            }
                        },

                        pushAllowedCurrency() {
                            const currencyName = event.target.name;

                            const index = this.currencies.allowed.indexOf(currencyName);

                            if (index === -1) {
                                this.currencies.allowed.push(currencyName);
                            } else {
                                this.currencies.allowed.splice(index, 1);
                            }
                        },

                        pushAllowedLocales() {
                            const localeName = event.target.name;

                            if (! Array.isArray(this.locales.allowed)) {
                            this.locales.allowed = [];
                            }

                            const index = this.locales.allowed.indexOf(localeName);

                            if (index === -1) {
                                this.locales.allowed.push(localeName);
                            } else {
                                this.locales.allowed.splice(index, 1);
                            }
                        },

                        completeStep(fromStep, toStep, toState, nextState, setErrors) {
                            this.stepStates[fromStep] = nextState;

                            this.currentStep = toStep;

                            this.stepStates[toStep] = toState;
                        },

                        startMigration(setErrors) {
                            this.currentStep = 'installProgress';

                            this.$axios.post("{{ route('installer.env_file_setup') }}", this.envData)
                                .then((response) => {
                                    this.runMigartion(setErrors);
                            })
                            .catch(error => {
                                setErrors(error.response.data.errors);
                            });
                        },

                        runMigartion(setErrors) {
                            this.$axios.post("{{ route('installer.run_migration') }}")
                                .then((response) => {
                                    this.completeStep('readyForInstallation', 'envConfiguration', 'active', 'complete');

                                    this.currentStep = 'envConfiguration';
                                })
                                .catch(error => {
                                    alert(error.response.data.error);

                                    this.currentStep = 'envDatabase';
                                });
                        },

                        startSeeding(selectedParams, allParameters) {
                            this.$axios.post("{{ route('installer.run_seeder') }}", {
                                'allParameters': allParameters,
                                'selectedParameters': selectedParams
                            })
                                .then((response) => {
                                    this.completeStep('readyForInstallation', 'createSampleProducts', 'active', 'complete');

                                    this.currentStep = 'createSampleProducts';
                            })
                                .catch(error => {
                                    setErrors(error.response.data.errors);
                                });
                        },

                        createSampleProducts(params, setErrors) {
                            if (params.sample_products == 1){
                                this.isLoading = true;

                                this.$axios.post("{{ route('installer.sample_products_setup') }}",{
                                    'selectedLocales': this.locales.allowed,
                                    'selectedCurrencies': this.currencies.allowed,
                                })
                                    .then((response) => {
                                        this.isLoading = false;

                                        this.completeStep('createSampleProducts', 'createAdmin', 'active', 'complete');

                                        this.currentStep = 'createAdmin';
                                    })
                                    .catch(error => {
                                        setErrors(error.response.data.errors);
                                    });
                            } else {
                                this.completeStep('createSampleProducts', 'createAdmin', 'active', 'complete');

                                this.currentStep = 'createAdmin';
                            }
                        },

                        saveAdmin(params, setErrors) {
                            this.$axios.post("{{ route('installer.admin_config_setup') }}", params)
                                .then((response) => {
                                    this.isLoading = false;

                                    this.currentStep = 'installationCompleted';

                                    if (response.data) {
                                        this.completeStep('createAdmin', 'installationCompleted', 'active', 'complete', setErrors);
                                    }
                                })
                                .catch(error => {
                                    setErrors(error.response.data.errors);
                                });
                        },

                        setLocale(params) {
                            const newLocale = params.locale;
                            const url = new URL(window.location.href);

                            if (! url.searchParams.has('locale')) {
                                url.searchParams.set('locale', newLocale);
                                window.location.href = url.toString();
                            }
                        },

                        back() {
                            if (this.$refs[this.currentStep] && this.$refs[this.currentStep].setValues) {
                                this.$refs[this.currentStep].setValues(this.envData);
                            }

                            let index = this.steps.indexOf(this.currentStep);

                            if (index > 0) {
                                this.currentStep = this.steps[index - 1];
                            }
                        }
                    },
                });
            </script>
        @endPushOnce

        @stack('scripts')
    </body>
</html>
