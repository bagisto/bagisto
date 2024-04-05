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
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity z-10"
                    v-show="isOpen"
                ></div>
            </transition>

            <transition
                tag="div"
                name="modal-content"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
                enter-to-class="opacity-100 translate-y-0 md:scale-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 md:scale-100"
                leave-to-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
            >
                <div
                    class="fixed inset-0 z-10 transform transition overflow-y-auto" v-show="isOpen"
                >
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div @click="handleOuterClick" class="w-full h-full z-[999] absolute left-1/2 top-1/2 bg-white max-md:w-[100%] -translate-x-1/2 -translate-y-1/2 overflow-hidden">
                            <div class="flex gap-5 justify-between items-center p-2 bg-white">
                                <span class="text-sm">@{{ getCurrentImageStatus }}</span>

                                <span
                                    class="icon-cancel text-3xl cursor-pointer"
                                    @click="toggle"
                                >
                                </span>
                            </div>

                            <div class="w-full h-full overflow-hidden">
                                <div
                                    class="flex items-center justify-center relative m-auto w-full"
                                    :class="{
                                        'h-full': ! isZooming,
                                        'h-auto': isZooming
                                    }"
                                >
                                    <div
                                        v-for="(image, index) in images"
                                        class="items-center justify-center h-full"
                                        ref="slides"
                                    >
                                        <img
                                            :src="image.original_image_url"
                                            class="max-w-full max-h-full transition-transform duration-300 ease-out"
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
                                        class="icon-arrow-left text-[24px] font-bold text-white w-auto -mt-[22px] p-[12px] fixed top-1/2 left-[10px] bg-[rgba(0,0,0,0.8)] transition-all opacity-30 rounded-full hover:opacity-100 cursor-pointer"
                                        v-if="images?.length >= 2 && ! isZooming"
                                        @click="navigate(currentIndex -= 1)"
                                    >
                                    </span>

                                    <span
                                        class="icon-arrow-right text-[24px] font-bold text-white w-auto -mt-[22px] p-[12px] fixed top-1/2 right-[10px] bg-[rgba(0,0,0,0.8)] transition-all opacity-30 rounded-full hover:opacity-100 cursor-pointer"
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