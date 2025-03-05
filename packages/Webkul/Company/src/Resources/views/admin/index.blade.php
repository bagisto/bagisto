<x-admin::layouts>

    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.company.index.title')
        </x-slot>

        @push('styles')
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        @endpush
        <!-- Page Content -->
        <div class="page-content">

            <header class="bg-[#ff3333]">
                <form id="filters_form" action="{{ route('admin.company.index') }}" method="get" class="w-100">
                    @csrf

                    <div class="p-4 flex items-center justify-between">
                        <div class="text-white font-bold text-lg">Tra cứu mã số thuế</div>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="attribute_value" value="{{ request()->get('attribute_value') }}" placeholder="Nhập mã số thuế, CMND, tên công ty"
                                   class="p-2 rounded-md">
                            <select class="p-2 rounded-md" name="attribute_name">
                                <option selected value> Tìm tự động </option>
                                @foreach(\Webkul\Company\Enums\TypeAttribute::selectFilterCompany() as $key => $val)
                                    <option {{ request()->get('attribute_name') == $key ? 'selected' : '' }} value="{{$key}}">{{ $val }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-gray-800 text-white p-2 rounded-md">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </header>
            <main class="p-4">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Thông tin công ty</h1>
                <hr class="border-t-2 border-red-500 mb-4">
                <div class="space-y-4">
                    @foreach($companies as $company)
                        <div>
                            <h2 class="text-blue-600 font-bold cursor-pointer" onclick="window.location.href='{{ route('admin.company.detail', ['id' => Arr::get($company, 'id')]) }}'">{{ \Illuminate\Support\Arr::get($company, 'ten_vn') }}</h2>
                            <p><i class="fas fa-hashtag"></i> Mã số thuế: {{ \Illuminate\Support\Arr::get($company, 'ma_so_thue') }}</p>
                            <p><i class="fas fa-user"></i> Người đại diện: <span class="font-bold">{{ \Illuminate\Support\Arr::get($company,'', 'Chưa xác định') }}</span></p>
                            <p><i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Arr::get($company, '', 'Chưa xác định') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>

</x-admin::layouts>
