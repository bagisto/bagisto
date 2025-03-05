<!doctype html>
<html lang="en">
<head>
    <title>@lang('company::company.company_detail')</title>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="base-url"
          content="{{ url()->to('/') }}">

    <link
        rel="icon"
        sizes="16x16"
        href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
    />

    <link
        rel="preload"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        as="style"
    >
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
    >

    <link
        rel="preload"
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        as="style"
    >
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
    >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    {!! view_render_event('bagisto.shop.layout.head.after') !!}

</head>
<body>
@php
    $status = \Illuminate\Support\Arr::get($company, 'tinh_trang_phap_ly');
@endphp
<div class="page-content px-10">
    <div class="bg-white p-4 shadow-md rounded-md">
        <h2 class="min-h-10 font-bold text-gray-500">@lang('company::company.company_detail')</h2>
        <h1 class="text-2xl font-bold mb-2 min-h-10">{{ \Illuminate\Support\Arr::get($company, 'ma_so_dn') }}
            - {{ \Illuminate\Support\Arr::get($company, 'ten_vn') }}</h1>
        <div class="border-b-2 border-red-500 mb-4"></div>

        <div class="mb-6">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <span class="w-32 font-semibold">@lang('company::company.tax_code')</span>
                    <span>{{ \Illuminate\Support\Arr::get($company, 'ma_so_dn') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="w-32 font-semibold">@lang('company::company.address')</span>
                    <span>{{ \Illuminate\Support\Arr::get($company, 'dia_chi', 'Chưa xác định') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="w-32 font-semibold">@lang('company::company.representative')</span>
                    <span>{{ \Illuminate\Support\Arr::get($company, 'nguoi_dai_dien', 'Chưa xác định') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="w-32 font-semibold">@lang('company::company.company_type')</span>
                    <span>{{ \Illuminate\Support\Arr::get($company, 'loai_hinh', 'Chưa xác định') }}</span>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="w-32 font-semibold">@lang('company::company.career_list')</span>
                    <span>{{ \Illuminate\Support\Arr::get($company, 'danh_sach_nganh_nghe', 'Chưa xác định') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="w-32 font-semibold">@lang('company::company.data_start')</span>
                    <span>{{ \Illuminate\Support\Arr::get($company, 'ngay_dang_ky') }}</span>
                </div>
            </div>
            <div
                class="mt-4 p-4 {{ $status == 'Đang hoạt động' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600'  }} rounded-md flex items-center max-w-[250px]">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>{{ $status }}</span>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2 pt-6">@lang('company::company.same_field')</h2>
        <div class="border-b-2 border-red-500 mb-4"></div>

        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-blue-600 cursor-pointer">CÔNG TY TNHH A</h3>
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="w-32 font-semibold">@lang('company::company.tax_code'):</span>
                        <span>123456789</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-32 font-semibold">@lang('company::company.representative'):</span>
                        <span class="text-blue-600">Nguyễn văn A</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-32 font-semibold">@lang('company::company.address'):</span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2">@lang('company::company.same_owner')</h2>
        <div class="border-b-2 border-red-500 mb-4"></div>

        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-blue-600 cursor-pointer">CÔNG TY TNHH B</h3>
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="w-32 font-semibold">@lang('company::company.tax_code'):</span>
                        <span>987654321</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-32 font-semibold">@lang('company::company.representative'):</span>
                        <span class="text-blue-600">Nguyễn văn B</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-32 font-semibold">@lang('company::company.address'):</span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.tailwindcss.com"></script>
</html>
