<!-- Notes Form -->
<div class="p-4 bg-white dark:bg-gray-900  rounded box-shadow">
    <p class="p-4 pb-0 text-base text-gray-800 leading-none dark:text-white font-semibold">
        @lang('admin::app.customers.customers.view.notes.add-note')
    </p>

    <x-admin::form :action="route('admin.customer.note.store', $customer->id)">
        <div class="p-4">
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

            <div class="flex justify-between items-center">
                <label
                    class="flex gap-1 w-max items-center p-1.5 cursor-pointer select-none"
                    for="customer_notified"
                >
                    <input
                        type="checkbox"
                        name="customer_notified"
                        id="customer_notified"
                        value="1"
                        class="hidden peer"
                    >

                    <span class="icon-uncheckbox rounded-md text-2xl cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"></span>

                    <p class="flex gap-x-1 items-center cursor-pointer text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 font-semibold">
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
    <span class="block w-full border-b dark:border-gray-800"></span>

    @foreach ($customer->notes as $note)
        <div class="grid gap-1.5 p-4">
            <p class="text-base text-gray-800 dark:text-white leading-6">
                {{$note->note}}
            </p>

            <!-- Notes List Title and Time -->
            <p class="flex gap-2 text-gray-600 dark:text-gray-300 items-center">
                @if ($note->customer_notified)
                    <span class="h-fit text-2xl rounded-full icon-done text-blue-600 bg-blue-100"></span>

                    @lang('admin::app.customers.customers.view.notes.customer-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                @else
                    <span class="h-fit text-2xl rounded-full icon-cancel-1 text-red-600 bg-red-100"></span>

                    @lang('admin::app.customers.customers.view.notes.customer-not-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                @endif
            </p>
        </div>

        <span class="block w-full border-b dark:border-gray-800"></span>
    @endforeach
</div>