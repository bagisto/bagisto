<!doctype html>
<html lang="vi">
<head>
    <title>@lang('company::company.title')</title>
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
<div class="page-content">
    <header class="bg-[#ff3333]">
        <form id="filters_form" action="{{ route('shop.company.index') }}" method="get" class="w-100">
            @csrf
            <div class="p-4 flex items-center justify-between px-10">
                <div class="text-white font-bold text-lg">@lang('company::company.title_header')</div>
                <div class="flex items-center space-x-2">
                    <input type="text" name="attribute_value" value="{{ request()->get('attribute_value') }}"
                           placeholder="@lang('company::company.search_input_placeholder')"
                           class="p-2 rounded-md">
                    <select class="p-2 rounded-md" name="attribute_name">
                        <option selected value> @lang('company::company.auto_search')</option>
                        @foreach(\Webkul\Company\Enums\TypeAttribute::selectFilterCompany() as $key => $val)
                            <option
                                {{ request()->get('attribute_name') == $key ? 'selected' : '' }} value="{{$key}}">{{ $val }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-800 text-white p-2 rounded-md">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </header>
    <main class="p-4 px-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">@lang('company::company.company_info')</h1>
        <hr class="border-t-2 border-red-500 mb-4">
        <div class="space-y-4">
            @foreach($companies as $company)
                <div>
                    <h2 class="text-blue-600 font-bold cursor-pointer"
                        onclick="window.location.href='{{ route('shop.company.detail', ['id' => $company->id]) }}'">{{ \Illuminate\Support\Arr::get($company, 'ten_vn') }}</h2>
                    <p><i class="fas fa-hashtag"></i> @lang('company::company.tax_code'): {{ \Illuminate\Support\Arr::get($company, 'ma_so_dn') }}</p>
                    <p><i class="fas fa-user"></i> @lang('company::company.representative'): <span
                            class="font-bold">{{ \Illuminate\Support\Arr::get($company,'nguoi_dai_dien', 'Chưa xác định') }}</span>
                    </p>
                    <p>
                        <i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Arr::get($company, 'dia_chi', 'Chưa xác định') }}
                    </p>
                </div>
            @endforeach
        </div>
    </main>
</div>
</body>
<script src="https://cdn.tailwindcss.com"></script>
</html>
