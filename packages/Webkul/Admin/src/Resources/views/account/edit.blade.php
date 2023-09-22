<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.account.edit.title')
    </x-slot:title>

    <x-admin::form 
        :action="route('admin.account.update')"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.account.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                 <!-- Cancel Button -->
                <a
                    href="{{ route('admin.dashboard.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>

                <!-- Save Button -->
                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.account.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <!-- Full Pannel -->
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
             <!-- Left sub Component -->
             <div class="flex flex-col gap-[8px] flex-1">
                 <!-- General -->
                 <div class="p-[16px] bg-white dark:bg-gray-900  box-shadow rounded-[4px]">
                    <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.account.edit.general')
                    </p>

                    {{-- Image --}}
                    <x-admin::form.control-group>
                        <x-admin::media.images
                            name="image"
                            :uploaded-images="$user->image ? [['id' => 'image', 'url' => $user->image_url]] : []"
                        >
                        </x-admin::media.images>
                    </x-admin::form.control-group>

                    <p class="my-5 text-[14px] text-gray-400">
                        @lang('admin::app.account.edit.upload-image-info')
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.account.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name') ?: $user->name"
                            rules="required"
                            :label="trans('admin::app.account.edit.name')"
                            :placeholder="trans('admin::app.account.edit.name')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Email -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.account.edit.email')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="email"
                            name="email"
                            :value="old('email') ?: $user->email"
                            rules="required"
                            id="email"
                            :label="trans('admin::app.account.edit.email')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error 
                            control-name="email"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>
             </div>

             <!-- Right sub-component -->
             <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-md:w-full">
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                            @lang('admin::app.account.edit.change-password')
                        </p>
                    </x-slot:header>

                     <!-- Change Account Password -->
                    <x-slot:content>
                        <!-- Current Password -->
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.account.edit.current-password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                name="current_password"
                                rules="required|min:6"
                                :label="trans('admin::app.account.edit.current-password')"
                                :placeholder="trans('admin::app.account.edit.current-password')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="current_password"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <!-- Password -->
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.account.edit.password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                name="password"
                                rules="min:6"
                                ref="password"
                                :placeholder="trans('admin::app.account.edit.password')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="password"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <!-- Confirm Password -->
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.account.edit.confirm-password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                name="password_confirmation"
                                rules="confirmed:@password"
                                :label="trans('admin::app.account.edit.confirm-password')"
                                :placeholder="trans('admin::app.account.edit.confirm-password')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error 
                                control-name="password_confirmation"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>
             </div>
        </div>
    </x-admin::form>
</x-admin::layouts>