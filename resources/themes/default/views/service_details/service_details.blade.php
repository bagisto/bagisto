<section class="max-w-7xl mx-auto px-4 py-24">

    <!-- Top Section -->
    <div class="grid grid-cols-12 gap-10">

<!-- Thumbnails -->
<div class="col-span-1 flex flex-col gap-4">
    @foreach($otherImages as $galleryImage)
        <img 
            src="{{ asset('storage/' . $galleryImage->path) }}" 
            class="w-16 h-16 object-cover rounded-lg cursor-pointer border"
            alt="{{ $serviceFlat->name }}"
        >
    @endforeach
</div>
        <!-- Main Image -->
        <div class="col-span-5">
            <div class="relative">
                <img 
                    src="{{ $serviceFlat->product->base_image_url ?? asset('images/main-service.jpg') }}"
                    alt="{{ $serviceFlat->name }}"
                    class="w-full h-[420px] object-cover rounded-xl"
                >

                <!-- Wishlist Icon -->
                <div class="absolute top-4 right-4 bg-white p-2 rounded-full shadow">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 10-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Service Info -->
        <div class="col-span-6 flex flex-col justify-start">

            <h1 class="font-oswald text-3xl uppercase text-[#2a1f14] mb-4">
                {{ $serviceFlat->name }}
            </h1>

            <!-- Price -->
            <div class="flex items-center gap-4 mb-2">
                <span class="text-[#c07a3a] text-xl font-semibold">
                    {{ core()->currency($serviceFlat->price) }}
                </span>

                @if($serviceFlat->qty < 1)
                    <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full">
                        Sold out
                    </span>
                @endif
            </div>

            <p class="text-xs text-gray-400 mb-6">
                All prices include tax
            </p>

            <!-- Info Boxes -->
            <div class="grid grid-cols-2 gap-4 mb-6">

                <!-- No Address Hassle -->
                <div class="flex items-center gap-3 bg-gray-100 rounded-lg p-4">
                    <svg class="w-5 h-5 text-[#c07a3a]" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 00-7 17l-1 3 3-1a10 10 0 1010-19z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium">No Address Hassle</p>
                        <p class="text-xs text-gray-500">We will collect the address for you</p>
                    </div>
                </div>

                <!-- Free Delivery -->
                <div class="flex items-center gap-3 bg-gray-100 rounded-lg p-4">
                    <svg class="w-5 h-5 text-[#c07a3a]" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M3 3h18v13H3z"/>
                        <path d="M16 16v2a2 2 0 01-2 2H10a2 2 0 01-2-2v-2"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium">Free delivery</p>
                        <p class="text-xs text-gray-500">On orders over AED 300</p>
                    </div>
                </div>

            </div>

<!-- Book Now Button -->
<button onclick="openModal()"
    class="inline-block bg-[#c07a3a] text-white px-8 py-3 rounded-full text-sm tracking-wide hover:bg-[#a8652f] transition"
    style="width:25%">
    BOOK NOW
</button>

        </div>

    </div>

    <!-- Description Section -->
    <div class="mt-16">

        <h2 class="font-oswald uppercase tracking-wide text-[#2a1f14] mb-4">
            Description
        </h2>

        <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-3xl">
            {!! $serviceFlat->short_description !!}
        </p>

        <!-- Example Details Section -->
        <h3 class="font-oswald uppercase tracking-wide text-[#2a1f14] mb-4">
            Mushaf Holder Details:
        </h3>

        <ul class="text-gray-500 text-sm space-y-2">
            <li>Brand: Floward</li>
            <li>Type: Holy Quran</li>
            <li>Color: Brown</li>
            <li>Material: Leather</li>
        </ul>

    </div>

</section>

<!-- MODAL -->
<form action="{{ route('shop.add.cart', $serviceFlat->url_key) }}" id="bookingForm" method="post">
@csrf

<input type="hidden" name="product_id" value="{{ $serviceFlat->product_id }}">
<input type="hidden" name="quantity" value="1">
<input type="hidden" name="professional_id" id="professional_id">

<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

<!-- POPUP -->
<div class="bg-white w-[1093px] rounded-lg shadow-lg">

    <!-- HEADER -->
    <div class="relative h-[159px] px-10 pt-8 border-b">

        <!-- CLOSE -->
        <button type="button" onclick="closeModal()" class="absolute right-8 top-8 text-gray-500 hover:text-black text-2xl">
            ✕
        </button>

        <!-- HEADING -->
        <h2 class="font-['Oswald'] text-[36px] leading-[100%] uppercase text-[#371E0F]">
            You’ve added items to your cart
        </h2>

        <!-- SERVICE + PRICE -->
        <div class="flex items-center gap-6 mt-6">

            <span class="font-['Oswald'] text-[20px] text-[#371E0F]">
                Eyebrow Color
            </span>

            <span class="font-['Oswald'] text-[24px] tracking-[0.10em] uppercase text-[#DFAA8B]">
                AED 60
            </span>

        </div>
    </div>


<!-- BODY -->
<div class="px-10 py-8">

    <!-- SELECT PROFESSIONAL -->
    <h3 class="font-['Oswald'] text-[24px] uppercase tracking-[0.10em] text-black mb-6">
        Select a Professional
    </h3>

    <!-- CHECKBOX OPTIONS -->
    <div class="flex items-center gap-10 mb-8">

        <label class="flex items-center gap-3 text-[20px] text-[#371E0F] font-['Oswald']">
            <input type="radio" name="pro_select" checked>
            Auto Assigned
        </label>

        <label class="flex items-center gap-3 text-[20px] text-[#371E0F] font-['Oswald']">
            <input type="radio" name="pro_select">
            Select from Listing
        </label>

    </div>


    <!-- PROFESSIONAL GRID -->
    <div class="grid grid-cols-4 gap-6">

        <!-- ITEM -->
        @forelse($professionals as $professional)
        <div class="pro-card border border-[#E2E2E2] rounded-lg p-3 cursor-pointer relative"            onclick="selectPro(this)" data-id="{{ $professional->id }}">

            <!-- CHECK ICON -->
            <div class="check absolute top-3 right-3 hidden">
                <div class="w-6 h-6 rounded-full bg-[#DFAA8B] flex items-center justify-center text-white text-sm">
                    ✓
                </div>
            </div>

            <img src="/mnt/data/Frame 1171275010.png" class="rounded-md w-full mb-3">

            <div class="flex justify-between items-center">
                <span class="font-['Oswald'] text-[18px] tracking-[3px] text-[#371E0F]">{{ $professional->name }}</span>
                <span class="text-[#DFAA8B] text-[14px]">AED 60</span>
            </div>

            <div class="text-sm mt-1 text-[#371E0F]">⭐ 4.6</div>
        </div>
        @empty
        No Professional Available
        @endforelse

    </div>

</div>

<!-- CONTINUE BUTTON -->
<div class="flex justify-center pb-10">
    <button type="button"
        onclick="continueBooking()"
        class="w-[228px] h-[47px] rounded-[50px] bg-[#DFAA8B] text-[#371E0F] font-['Oswald'] text-[18px] uppercase flex items-center justify-center">
        Continue
    </button>
</div>



</div>
</div>
</form>


<script>

let manualMode = false;
let selectedProfessional = null;

function openModal(){
    document.getElementById('bookingModal').classList.remove('hidden');
    document.getElementById('bookingModal').classList.add('flex');
}

function closeModal(){
    document.getElementById('bookingModal').classList.add('hidden');
}

/* radio change */
document.querySelectorAll('input[name="pro_select"]').forEach((radio,index)=>{

    radio.addEventListener('change',function(){

        manualMode = index === 1;

        if(!manualMode){

            clearSelection();

            let first = document.querySelector('.pro-card');

            if(first){
                selectedProfessional = first.dataset.id;
            }

        }

    });

});


function selectPro(card){

    if(!manualMode) return;

    clearSelection();

    card.classList.remove('border-[#E2E2E2]');
    card.classList.add('border-[#DFAA8B]');
    card.querySelector('.check').classList.remove('hidden');

    selectedProfessional = card.dataset.id;

}


function clearSelection(){

    document.querySelectorAll('.pro-card').forEach(c=>{

        c.classList.remove('border-[#DFAA8B]');
        c.classList.add('border-[#E2E2E2]');
        c.querySelector('.check').classList.add('hidden');

    });

}


function continueBooking(){

    let professionalId;

    if(manualMode){

        if(!selectedProfessional){
            alert("Please select a professional");
            return;
        }

        professionalId = selectedProfessional;

    }else{

        let first = document.querySelector('.pro-card');

        if(!first){
            alert("No professionals available");
            return;
        }

        professionalId = first.dataset.id;

    }

    document.getElementById('professional_id').value = professionalId;

    document.getElementById('bookingForm').submit();

}

</script>