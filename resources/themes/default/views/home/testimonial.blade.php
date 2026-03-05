<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">

<script>
tailwind.config = {
  theme:{
    extend:{
      fontFamily:{
        oswald:['Oswald','sans-serif'],
        jost:['Jost','sans-serif'],
      }
    }
  }
}
</script>

<section class="w-full max-w-[1400px] mx-auto px-6 md:px-8 py-16 md:py-24">

<!-- TITLE -->
<h2 class="font-oswald text-[#371E0F] uppercase text-center mb-12"
style="
font-weight:400;
font-size:60px;
line-height:100%;
letter-spacing:0%;
">
Testimonial
</h2>


<div class="relative max-w-[900px] mx-auto">

<!-- TESTIMONIAL 1 -->
<div class="testimonial-item">

<div class="flex flex-col md:flex-row items-stretch rounded-2xl overflow-hidden">

<!-- IMAGE -->
<div class="flex-shrink-0 w-full md:w-[260px]">
<img
src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=500&fit=crop&crop=face"
class="w-full h-[280px] md:h-full object-cover"/>
</div>

<!-- CONTENT -->
<div class="flex-1 bg-white px-6 md:px-10 py-8 flex flex-col justify-between">

<div>

<div class="text-[#DFAA8B] text-6xl mb-6 leading-none">“</div>

<p class="font-oswald text-[#78718B] mb-8"
style="
font-weight:400;
font-size:24px;
line-height:100%;
letter-spacing:10%;
">
Best service ever .. and they came at the time
</p>

<p class="font-oswald uppercase text-[#371E0F]"
style="
font-weight:400;
font-size:24px;
line-height:100%;
letter-spacing:10%;
">
Mariam
</p>

<div class="flex gap-1 text-yellow-400 text-lg mt-3">
★ ★ ★ ★ <span class="text-gray-300">★</span>
</div>

</div>

</div>

</div>
</div>


<!-- TESTIMONIAL 2 -->
<div class="testimonial-item hidden">

<div class="flex flex-col md:flex-row items-stretch rounded-2xl overflow-hidden">

<div class="flex-shrink-0 w-full md:w-[260px]">
<img
src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=400&h=500&fit=crop&crop=face"
class="w-full h-[280px] md:h-full object-cover"/>
</div>

<div class="flex-1 bg-white px-6 md:px-10 py-8 flex flex-col justify-between">

<div>

<div class="text-[#DFAA8B] text-6xl mb-6 leading-none">“</div>

<p class="font-oswald text-[#78718B] mb-8"
style="
font-weight:400;
font-size:24px;
line-height:100%;
letter-spacing:10%;
">
Very professional and clean work. Highly recommended!
</p>

<p class="font-oswald uppercase text-[#371E0F]"
style="
font-weight:400;
font-size:24px;
line-height:100%;
letter-spacing:10%;
">
Aisha
</p>

<div class="flex gap-1 text-yellow-400 text-lg mt-3">
★ ★ ★ ★ ★
</div>

</div>

</div>

</div>
</div>


<!-- NAVIGATION -->
<div class="flex justify-between items-center mt-10">

<button onclick="changeTestimonial(-1)"
class="w-10 h-10 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#DFAA8B] transition-colors">
‹
</button>

<button onclick="changeTestimonial(1)"
class="w-10 h-10 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#DFAA8B] transition-colors">
›
</button>

</div>

</div>

</section>


<script>

let currentIndex = 0;
const items = document.querySelectorAll('.testimonial-item');

function changeTestimonial(direction){

items[currentIndex].classList.add('hidden');

currentIndex += direction;

if(currentIndex < 0) currentIndex = items.length - 1;
if(currentIndex >= items.length) currentIndex = 0;

items[currentIndex].classList.remove('hidden');

}

</script>