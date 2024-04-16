<!-- Notes Form -->
<div class="box-shadow rounded bg-white p-4 last:pb-0 dark:bg-gray-900">
    <p class="p-4 pb-0 text-base font-semibold leading-none text-gray-800 dark:text-white">
        @lang('admin::app.customers.customers.view.notes.add-note')
    </p>

    <x-admin::form :action="route('admin.customer.note.store', $customer->id)">
        <div class="border-b p-4 dark:border-gray-800">
            <!-- Note -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.control
                    type="textarea"
                    id="note"
                    name="note"
                    rules="required"
                    :label="trans('admin::app.customers.customers.view.notes.note')"
                    :placeholder="trans('admin::app.customers.customers.view.notes.note-placeholder')"
                    rows="3"
                />

                <x-admin::form.control-group.error control-name="note" />
            </x-admin::form.control-group>

            <div class="flex items-center justify-between">
                <label
                    class="flex w-max cursor-pointer select-none items-center gap-1 p-1.5"
                    for="customer_notified"
                >
                    <input
                        type="checkbox"
                        name="customer_notified"
                        id="customer_notified"
                        value="1"
                        class="peer hidden"
                    >

                    <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"></span>

                    <p class="flex cursor-pointer items-center gap-x-1 font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100">
                        @lang('admin::app.customers.customers.view.notes.notify-customer')
                    </p>
                </label>

                <!--Note Submit Button -->
                <button
                    type="submit"
                    class="secondary-button"
                >
                    @lang('admin::app.customers.customers.view.notes.submit-btn-title')
                </button>
            </div>
        </div>
    </x-admin::form>

    <!-- Notes List -->
    @foreach ($customer->notes as $note)
        <div class="grid gap-1.5 border-b p-4 last:border-none dark:border-gray-800">
            <p class="text-base leading-6 text-gray-800 dark:text-white">
                {{$note->note}}
            </p>

            <!-- Notes List Title and Time -->
            <p class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                @if ($note->customer_notified)
                    <span class="icon-done h-fit rounded-full bg-blue-100 text-2xl text-blue-600"></span>

                    @lang('admin::app.customers.customers.view.notes.customer-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                @else
                    <span class="icon-cancel-1 h-fit rounded-full bg-red-100 text-2xl text-red-600"></span>

                    @lang('admin::app.customers.customers.view.notes.customer-not-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                @endif
            </p>
        </div>
    @endforeach
</div>