<x-admin::layouts>

    <x-slot:title>
        @lang('admin::app.company.index.title')
    </x-slot:title>

        @push('styles')
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        @endpush
        @php
            $status = \Illuminate\Support\Arr::get($company, 'status');
        @endphp
        <div class="page-content">
            <div class="bg-white p-4 shadow-md rounded-md">
                <h1 class="text-2xl font-bold mb-2">{{ \Illuminate\Support\Arr::get($company, 'ma_so_thue') }} - {{ \Illuminate\Support\Arr::get($company, 'ten_vn') }}</h1>
                <div class="border-b-2 border-red-500 mb-4"></div>

                <div class="mb-6">
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="w-32 font-semibold">Mã số thuế</span>
                            <span>{{ \Illuminate\Support\Arr::get($company, 'ma_so_thue') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-32 font-semibold">Địa chỉ</span>
                            <span>{{ \Illuminate\Support\Arr::get($company, 'address', 'Chưa xác định') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-32 font-semibold">Người đại diện</span>
                            <span>{{ \Illuminate\Support\Arr::get($company, '', 'Chưa xác định') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-32 font-semibold">Ngày hoạt động</span>
                            <span>{{ \Illuminate\Support\Arr::get($company, 'ngay_dang_ky') }}</span>
                        </div>
                    </div>
                    <div class="mt-4 p-4 {{ $status == 'Đang hoạt động' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600'  }} rounded-md flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>{{ $status }}</span>
                    </div>
                </div>

                <h2 class="text-xl font-semibold mb-2 pt-6">Thông tin công ty cùng lĩnh vực</h2>
                <div class="border-b-2 border-red-500 mb-4"></div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-600 cursor-pointer">CÔNG TY TNHH A</h3>
                        <div class="space-y-1">
                            <div class="flex items-center">
                                <span class="w-32 font-semibold">Mã số thuế:</span>
                                <span>123456789</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-32 font-semibold">Người đại diện:</span>
                                <span class="text-blue-600">Nguyễn văn A</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-32 font-semibold">Địa chỉ:</span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-semibold mb-2">Thông tin công ty cùng chủ sở hữu</h2>
                <div class="border-b-2 border-red-500 mb-4"></div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-600 cursor-pointer">CÔNG TY TNHH B</h3>
                        <div class="space-y-1">
                            <div class="flex items-center">
                                <span class="w-32 font-semibold">Mã số thuế:</span>
                                <span>987654321</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-32 font-semibold">Người đại diện:</span>
                                <span class="text-blue-600">Nguyễn văn B</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-32 font-semibold">Địa chỉ:</span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-admin::layouts>
