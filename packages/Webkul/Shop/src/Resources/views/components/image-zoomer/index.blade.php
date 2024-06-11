<v-gallery-zoomer {{ $attributes }}></v-gallery-zoomer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-gallery-zoomer-template"
    >
        <transition
            tag="div"
            class="bg-white"
            name="modal-content"
            enter-class="duration-300 ease-out"
            enter-from-class="translate-y-4 opacity-0 md:translate-y-0 md:scale-95"
            enter-to-class="translate-y-0 opacity-100 md:scale-100"
            leave-class="duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100 md:scale-100"
            leave-to-class="translate-y-4 opacity-0 md:translate-y-0 md:scale-95"
        >
            <div
                ref="parentContainer" 
                class="fixed inset-0 z-10 flex transform flex-col gap-4 overflow-y-auto transition"
                v-show="isOpen"
            >
                <!-- Close -->
                <span
                    class="icon-cancel absolute top-3 z-[1000] cursor-pointer text-3xl ltr:right-3 rtl:left-3"
                    @click="toggle"
                >
                </span>

                <span
                    class="icon-arrow-left fixed left-2.5 top-1/2 z-10 -mt-12 w-auto cursor-pointer rounded-full bg-[rgba(0,0,0,0.8)] p-3 text-2xl font-bold text-white opacity-30 transition-all hover:opacity-100"
                    v-if="attachments.length >= 2"
                    @click="navigate(currentIndex -= 1)"
                >
                </span>

                <span
                    class="icon-arrow-right fixed right-2.5 top-1/2 z-10 -mt-12 w-auto cursor-pointer rounded-full bg-[rgba(0,0,0,0.8)] p-3 text-2xl font-bold text-white opacity-30 transition-all hover:opacity-100"
                    v-if="attachments.length >= 2"
                    @click="navigate(currentIndex += 1)"
                >
                </span>
                    
                <!-- Main Image -->
                <div 
                    ref="mediaContainer" 
                    class="h-full w-full overflow-hidden"
                >
                    <div
                        class="relative m-auto flex w-full items-center justify-center"
                        :class="{
                            'h-full': ! isZooming,
                            'h-auto': isZooming
                        }"
                    >
                        <div
                            v-for="(attachment, index) in attachments"
                            class="h-full items-center justify-center"
                            ref="slides"
                        >
                            <video 
                                class="max-h-full max-w-full transition-transform duration-300 ease-out"
                                controls 
                                v-if="attachment.type == 'video'"
                            >
                                <source :src="attachment.url" type="video/mp4">
                                <source :src="attachment.url" type="video/ogg">
                                    Your browser does not support HTML video.
                            </video>

                            <template v-if="attachment.type === 'image'">
                                <!-- For Desktop -->
                                <img
                                    :src="attachment.url"
                                    class="max-h-full max-w-full transition-transform duration-300 ease-out max-md:hidden"
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

                                <!-- For Mobile -->
                                <img
                                    :src="attachment.url"
                                    class="max-h-full max-w-full transition-transform duration-300 ease-out md:hidden"
                                    :class="{
                                        'cursor-zoom-in': ! isZooming,
                                        'cursor-grab': ! isDragging && isZooming,
                                        'cursor-grabbing': isDragging && isZooming,
                                    }"
                                    :style="{transform: `translate(${translateX}px, ${translateY}px)`}"
                                />    
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Thumbnails -->
                <div class="mb-4 flex justify-center space-x-2">
                    <template v-for="(attachment, index) in attachments">
                        <img
                            class="h-16 w-16 transform cursor-pointer rounded-md border border-navyBlue border-transparent object-cover transition-transform hover:!border-navyBlue"
                            :class="{
                                '!border-navyBlue': currentIndex === index + 1,
                            }"
                            :src="attachment.url"
                            :key="index"
                            v-if="attachment.type === 'image'"
                            @click="navigate(currentIndex = index + 1)"
                        />

                        <video
                            class="h-16 w-16 transform cursor-pointer rounded-md border border-navyBlue border-transparent object-cover transition-transform hover:!border-navyBlue"
                            :class="{
                                '!border-navyBlue': currentIndex === index + 1,
                            }"
                            :src="attachment.url"
                            :key="index"
                            v-if="attachment.type === 'video'"
                            @click="navigate(currentIndex = index + 1)"
                        />
                    </template>
                </div>
            </div>
        </transition>
    </script>

    <script type="module">
        app.component('v-gallery-zoomer', {
            template: '#v-gallery-zoomer-template',

            props: {
                attachments: {
                    type: Object,

                    required: true,

                    default: () => [],
                },

                isImageZooming: {
                    type: Boolean,

                    default: false,
                },

                initialIndex: {
                    type: String,
                    
                    default: 0,
                },
            },

            watch: {
                isImageZooming(newVal, oldVal) {  
                    this.currentIndex = parseInt(this.initialIndex.split('_').pop()) + 1;

                    this.navigate(this.currentIndex);

                    this.toggle();
                },
            },
        
            data() {
                return {
                    isOpen: this.isImageZooming,

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
                    if (index > this.attachments.length) {
                        this.currentIndex = 1;
                    }

                    if (index < 1) {
                        this.currentIndex = this.attachments.length;
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

                    const remainingHeight = this.$refs.parentContainer.clientHeight - this.$refs.mediaContainer.clientHeight;

                    const maxTranslateY = Math.min(0, window.innerHeight - (event.srcElement.height + remainingHeight));

                    const clampedTranslateY = Math.max(maxTranslateY, Math.min(newTranslateY, 0));

                    this.translateY = clampedTranslateY;
                    
                    this.startDragY = event.clientY;
                    
                    this.startDragX = event.clientX;

                    this.translateX += deltaX;
                },

                handleMouseWheel(event) {
                    const deltaY = event.clientY - this.startDragY;

                    let newTranslateY = this.translateY - event.deltaY / Math.abs(event.deltaY) * 100;
                    
                    const remainingHeight = this.$refs.parentContainer.clientHeight - this.$refs.mediaContainer.clientHeight;

                    const maxTranslateY = Math.min(0, window.innerHeight - (event.srcElement.height + remainingHeight));

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