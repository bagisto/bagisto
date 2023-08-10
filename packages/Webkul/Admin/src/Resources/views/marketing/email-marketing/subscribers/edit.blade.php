<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.newsletters.edit.title')
    </x-slot:title>

    {{-- Input Form --}}
    <x-admin::form :action="route('admin.customers.subscribers.update', $subscriber->id)">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.newsletters.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a href="{{ route('admin.customers.subscribers.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.marketing.email-marketing.newsletters.edit.cancel-btn')
                    </span>
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.marketing.email-marketing.newsletters.edit.save-btn')
                </button>
            </div>
        </div>

         {{-- Informations --}}
         <div class="flex gap-[10px] mt-[28px] mb-2">
            <div class="flex flex-col gap-[8px] flex-1">
                {{-- General Section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.marketing.email-marketing.campaigns.create.general')
                    </p>

                    <div class="mb-[10px]">
                        {{-- Email --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.newsletters.edit.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                value="{{ $subscriber->email ?: old('email') }}"
                                rules="required"
                                class="mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.newsletters.edit.email') }}"
                                disabled
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error 
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Subscribed --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.newsletters.edit.subscribed')
                            </x-admin::form.control-group.label>

                            @php $selectedOption = old('status') ?: ($subscriber->is_subscribed == 1 ? 'true' : 'false') @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                rules="required"
                                class="cursor-pointer mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.newsletters.edit.subscribed') }}"
                            >
                                @foreach (['true', 'false'] as $state)
                                    <option 
                                        value="{{ $state == 'true' ? 1 : 0 }}" 
                                        {{ $selectedOption == $state ? 'selected' : '' }}
                                    >
                                        @lang('admin::app.marketing.email-marketing.newsletters.edit.' . $state)
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="status"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>