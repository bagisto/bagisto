<v-gallery-zoomer {{ $attributes }}></v-gallery-zoomer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-gallery-zoomer-template"
    >
        <div>
            <transition
                tag="div"
                name="modal-overlay"
                enter-class="duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-class="duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    class="fixed inset-0 z-10 bg-gray-500 bg-opacity-50 transition-opacity"
                    v-show="isOpen"
                ></div>
            </transition>

            <transition
                tag="div"
                name="modal-content"
                enter-class="duration-300 ease-out"
                enter-from-class="translate-y-4 opacity-0 md:translate-y-0 md:scale-95"
                enter-to-class="translate-y-0 opacity-100 md:scale-100"
                leave-class="duration-200 ease-in"
                leave-from-class="translate-y-0 opacity-100 md:scale-100"
                leave-to-class="translate-y-4 opacity-0 md:translate-y-0 md:scale-95"
            >
                <div
                    class="fixed inset-0 z-10 transform overflow-y-auto transition" v-show="isOpen"
                >
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div @click="handleOuterClick" class="absolute left-1/2 top-1/2 z-[999] h-full w-full -translate-x-1/2 -translate-y-1/2 overflow-hidden bg-white max-md:w-[100%]">
                            <div class="flex items-center justify-between gap-5 bg-white p-2">
                                <span class="text-sm">@{{ getCurrentImageStatus }}</span>

                                <span
                                    class="icon-cancel cursor-pointer text-3xl"
                                    @click="toggle"
                                >
                                </span>
                            </div>

                            <div class="h-full w-full overflow-hidden">
                                <div
                                    class="relative m-auto flex w-full items-center justify-center"
                                    :class="{
                                        'h-full': ! isZooming,
                                        'h-auto': isZooming
                                    }"
                                >
                                    <div
                                        v-for="(image, index) in images"
                                        class="h-full items-center justify-center"
                                        ref="slides"
                                    >
                                        <img
                                            :src="image.original_image_url"
                                            class="max-h-full max-w-full transition-transform duration-300 ease-out"
                                            :class="{
                                                'cursor-zoom-in': ! isZooming,
                                                'cursor-grab': ! isDragging && isZooming,
                                                'cursor-grabbing': isDragging && isZooming,
                                            }"
                                            :style="{transform: `translate(${translateX}px, ${translateY}px)`}"
                                            @click.stop="handleClick"
                                            @mousedown.prevent="handleMouseDown"
                                            @mousemove.prevent="handleMouseMove"
                                            @mouseleave.prevent="resetImagePosition"
                                            @mouseup.prevent="resetImagePosition"
                                            @mousewheel="handleMouseWheel"
                                        />
                                    </div>

                                    <span
                                        class="icon-arrow-left fixed left-[10px] top-1/2 -mt-[22px] w-auto cursor-pointer rounded-full bg-[rgba(0,0,0,0.8)] p-[12px] text-[24px] font-bold text-white opacity-30 transition-all hover:opacity-100"
                                        v-if="images?.length >= 2 && ! isZooming"
                                        @click="navigate(currentIndex -= 1)"
                                    >
                                    </span>

                                    <span
                                        class="icon-arrow-right fixed right-[10px] top-1/2 -mt-[22px] w-auto cursor-pointer rounded-full bg-[rgba(0,0,0,0.8)] p-[12px] text-[24px] font-bold text-white opacity-30 transition-all hover:opacity-100"
                                        v-if="images?.length >= 2  && ! isZooming"
                                        @click="navigate(currentIndex += 1)"
                                    >
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-gallery-zoomer', {
            template: '#v-gallery-zoomer-template',

            props: {
                images: {
                    type: Object,
                    required: true,
                },
            },

            data() {
                return {
                    isOpen: false,

                    isDragging: false,

                    isZooming: false,

                    currentIndex: 1,

                    startDragX: 0,

                    startDragY: 0,

                    translateX: 0,

                    translateY: 0,

                    isMouseMoveTriggered: false,

                    isMouseDownTriggered: false,
                };
            },

            computed: {
                getCurrentImageStatus() {
                    return `${this.currentIndex} / ${this.images.length}`;
                },
            },

            mounted() {
                this.$emitter.on('v-show-images-zoomer', (currentIndex) =>  {
                    this.currentIndex = parseInt(currentIndex.split('_').pop()) + 1;

                    this.navigate(this.currentIndex);

                    this.toggle();
                });
            },

            methods: {
                toggle() {
                    this.isOpen = ! this.isOpen;

                    document.body.style.overflow = this.isOpen ? 'hidden' : '';
                },

                open() {
                    this.isOpen = true;

                    document.body.style.overflow = 'hidden';
                },

                navigate(index) {
                    if (index > this.images.length) {
                        this.currentIndex = 1;
                    }

                    if (index < 1) {
                        this.currentIndex = this.images.length;
                    }

                    let slides = this.$refs.slides;

                    for (let i = 0; i < slides.length; i++) {
                        if (i == this.currentIndex - 1) {
                            continue;
                        }

                        slides[i].style.display = 'none';
                    }

                    slides[this.currentIndex - 1].style.display = 'flex';

                    this.isZooming = false;

                    this.resetDrag();
                },

                handleClick(event) {
                    if (
                        this.isMouseMoveTriggered
                        && ! this.isMouseDownTriggered
                    ) {
                        return;
                    }

                    this.resetDrag();

                    this.isZooming = ! this.isZooming;
                },

                handleOuterClick() {
                    if (! this.isZooming) {
                        return;
                    }

                    this.isZooming = false;

                    resetDrag();
                },

                handleMouseDown(event) {
                    this.isMouseDownTriggered = true;

                    this.isDragging = true;

                    this.startDragX = event.clientX;

                    this.startDragY = event.clientY;
                },

                handleMouseMove(event) {
                    this.isMouseMoveTriggered = true;

                    this.isMouseDownTriggered = false;

                    if (! this.isDragging) {
                        return;
                    }

                    const deltaX = event.clientX - this.startDragX;

                    const deltaY = event.clientY - this.startDragY;

                    const newTranslateY = this.translateY + deltaY;

                    const maxTranslateY = Math.min(0, window.innerHeight - event.srcElement.height);

                    const clampedTranslateY = Math.max(maxTranslateY, Math.min(newTranslateY, 0));

                    this.translateY = clampedTranslateY;

                    this.startDragY = event.clientY;

                    this.startDragX = event.clientX;

                    this.translateX += deltaX;
                },

                handleMouseWheel(event) {
                    const deltaY = event.clientY - this.startDragY;

                    let newTranslateY = this.translateY - event.deltaY / Math.abs(event.deltaY) * 100; // Subtract instead of add

                    const maxTranslateY = Math.min(0, window.innerHeight - event.srcElement.height);

                    this.translateY = Math.max(maxTranslateY, Math.min(newTranslateY, 0));
                },

                resetImagePosition() {
                    this.isDragging = false;

                    this.translateX  = 0;

                    this.startDragX = 0;
                },

                resetDrag() {
                    this.isDragging = false;

                    this.startDragX = 0;

                    this.startDragY = 0;

                    this.translateX = 0;

                    this.translateY = 0;
                },
            },
        });
    </script>
@endPushOnce