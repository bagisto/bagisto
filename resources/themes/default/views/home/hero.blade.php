<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<section class="max-w-7xl mx-auto px-4 mt-6 relative">

    <!-- HERO BANNER -->
    <div class="rounded-3xl overflow-hidden">
        <div class="grid md:grid-cols-2 min-h-[420px]">

            <!-- LEFT SIDE -->
            <div class="bg-[#E9E4DF] flex items-center">
                <div class="px-6 md:px-16 py-8 md:py-12">
                    <p class="text-sm text-[#371E0F] mb-4">{{ __('home.spa_name') }}</p>

                    <h1 class="font-oswald text-[32px] md:text-[64px] leading-[1.1] text-[#371E0F] uppercase">
                        {{ __('home.title.line1') }} 
                        <span class="text-[#DFAA8B]">{{ __('home.title.highlight1') }}</span><br>
                        {{ __('home.title.line2') }} 
                        <span class="text-[#DFAA8B]">{{ __('home.title.highlight2') }}</span>
                    </h1>

                    <p class="mt-6 text-[#371E0F] text-sm max-w-md">
                        {{ __('home.description') }}
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="bg-[#5A2B14] flex items-end justify-center">
                <img 
                    src="{{ asset('images/hero_bg.png') }}"
                    class="h-full object-contain"
                    alt="Model">
            </div>

        </div>
    </div>

    <!-- BOOKING CARD -->
    <div class="absolute bottom-[-50px] w-full px-4">
        <form id="bookingForm" action="{{ route('booking.search') }}" method="GET" class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

  <!-- SERVICE -->  
    <div>
        <label class="text-xs text-gray-500 block mb-1">{{ __('home.service_label') }}</label>
        <select name="service_category_id" required class="w-full border rounded-full px-4 py-2 text-sm">
        <option value="">{{ __('home.select_service') ?? 'Select service' }}</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">
                {{ $category->translate(app()->getLocale())?->name ?? $category->name }}
            </option>
        @endforeach
        </select>
        <span class="text-red-500 text-xs hidden" id="serviceError">
        {{ __('home.select_service_error') ?? 'Please select a service' }}
        </span>
    </div>
                <!-- LOCATION -->
                <div>
                    <label class="text-xs text-gray-500 block mb-1">{{ __('home.location_label') }}</label>
                    <select name="service_location" required class="w-full border rounded-full px-4 py-2 text-sm">
                        <option value="">{{ __('home.select_location') ?? 'Select location' }}</option>
                        @foreach($service_locations as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                    <span class="text-red-500 text-xs hidden" id="locationError">{{ __('home.select_location_error') ?? 'Please select a location' }}</span>
                </div>

                <!-- DATE -->
                <div>
                    <label class="text-xs text-gray-500 block mb-1">{{ __('home.date_label') }}</label>
                    <input 
                        type="date"
                        name="service_date"
                        required
                        class="w-full border rounded-full px-4 py-2 text-sm">
                    <span class="text-red-500 text-xs hidden" id="dateError">{{ __('home.select_date_error') ?? 'Please select a date' }}</span>
                </div>

                <!-- TIME -->
                <div>
                    <label class="text-xs text-gray-500 block mb-1">{{ __('home.time_label') }}</label>
                    <input 
                        type="time"
                        id="service_time"
                        name="service_time"
                        required
                        class="w-full border rounded-full px-4 py-2 text-sm"
                        placeholder="{{ __('home.select_time') ?? 'Select Time' }}">
                    <span class="text-red-500 text-xs hidden" id="timeError">{{ __('home.select_time_error') ?? 'Please select a time' }}</span>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="flex items-center">
                    <button 
                        type="submit"
                        class="w-full bg-[#DFAA8B] hover:bg-[#c58a61] text-white rounded-full py-2 text-sm font-medium">
                        {{ __('home.search_button') }}
                    </button>
                </div>

            </div>
        </form>
    </div>

</section>

<script>
    // Front-end validation
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        let valid = true;

        const service = this.service_category_id;
        const location = this.service_location;
        const date = this.service_date;
        const time = this.service_time;

        // Hide all error spans first
        document.querySelectorAll('#bookingForm span.text-red-500').forEach(span => span.classList.add('hidden'));

        if(!service.value){
            document.getElementById('serviceError').classList.remove('hidden');
            valid = false;
        }
        if(!location.value){
            document.getElementById('locationError').classList.remove('hidden');
            valid = false;
        }
        if(!date.value){
            document.getElementById('dateError').classList.remove('hidden');
            valid = false;
        }
        if(!time.value){
            document.getElementById('timeError').classList.remove('hidden');
            valid = false;
        }

        if(!valid) e.preventDefault();
    });
</script>