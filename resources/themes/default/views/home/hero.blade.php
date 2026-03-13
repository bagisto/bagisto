<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- HERO BANNER -->
<section class="home-hero-section-wrap relative">
    <div class="large-container home-hero-bg-wrap">
        <div class="container">
            <div class="hero-banner-content-col">
                <p class="text-[#000] mb-4">
                    {{ __('home.spa_name') }}
                </p>

                <h1 class="font-oswald text-[32px] md:text-[90px] text-[#371E0F] uppercase">
                    {{ __('home.title.line1') }}
                    <span class="text-[#DFAA8B]">{{ __('home.title.highlight1') }}</span>
                    {{ __('home.title.line2') }}
                    <span class="text-[#DFAA8B]">{{ __('home.title.highlight2') }}</span>
                </h1>

                <p class="mt-6 text-[#000] max-w-md txt-lg">
                    {{ __('home.description') }}
                </p>
            </div>
        </div>
    </div>

    <!-- BOOKING CARD -->
    <div class="container">
        <div class="home-booking-filters-wrap">
            <form id="bookingForm" action="{{ route('booking.search') }}" method="GET" class="">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                    <!-- SERVICE -->
                    <div class="form-group">
                        <label class="txt-md heading-font heading-color block mb-2">
                            {{ __('home.service_label') }}
                        </label>
                        <select name="service_category_id" required class="form-select">
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
                    <div class="form-group">
                        <label class="txt-md heading-font heading-color block mb-2">
                            {{ __('home.location_label') }}
                        </label>
                        <select name="service_location" required class="form-select">
                            <option value="">{{ __('home.select_location') ?? 'Select location' }}</option>
                            @foreach($service_locations as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                            @endforeach
                        </select>
                        <span class="text-red-500 text-xs hidden" id="locationError">{{
                            __('home.select_location_error')
                            ?? 'Please select a location' }}</span>
                    </div>

                    <!-- DATE -->
                    <div class="form-group">
                        <label class="txt-md heading-font heading-color block mb-2">
                            {{ __('home.date_label') }}
                        </label>
                        <input type="date" name="service_date" required class="form-control">
                        <span class="text-red-500 text-xs hidden" id="dateError">
                            {{ __('home.select_date_error')
                            ??
                            'Please select a date' }}</span>
                    </div>

                    <!-- TIME -->
                    <div class="form-group">
                        <label class="txt-md heading-font heading-color block mb-2">{{ __('home.time_label') }}</label>
                        <input type="time" id="service_time" name="service_time" required class="form-control"
                            placeholder="{{ __('home.select_time') ?? 'Select Time' }}">
                        <span class="text-red-500 text-xs hidden" id="timeError">{{ __('home.select_time_error')
                            ??
                            'Please select a time' }}</span>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="flex items-center">
                        <button type="submit" class="home-filter-search-btn">
                            {{ __('home.search_button') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Front-end validation
    document.getElementById('bookingForm').addEventListener('submit', function (e) {
        let valid = true;

        const service = this.service_category_id;
        const location = this.service_location;
        const date = this.service_date;
        const time = this.service_time;

        // Hide all error spans first
        document.querySelectorAll('#bookingForm span.text-red-500').forEach(span => span.classList.add('hidden'));

        if (!service.value) {
            document.getElementById('serviceError').classList.remove('hidden');
            valid = false;
        }
        if (!location.value) {
            document.getElementById('locationError').classList.remove('hidden');
            valid = false;
        }
        if (!date.value) {
            document.getElementById('dateError').classList.remove('hidden');
            valid = false;
        }
        if (!time.value) {
            document.getElementById('timeError').classList.remove('hidden');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
</script>