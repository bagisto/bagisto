<v-range-slider></v-range-slider>

@pushOnce('scripts')
    <script type="text/x-template" id="v-range-slider-template">
        <div>
            <div class="flex items-center gap-[15px]">
                <p class="text-[14px]">Range:</p>
                <p class="text-[14px] font-semibold">@{{ minValue + '-' + maxValue }}</p>
            </div>

            <div class="relative h-[4px] w-[246px] mt-[30px] mb-[24px]">
                <div class="absolute left-0 right-0 top-0 h-[4px] bg-[#F5F5F5] rounded-[12px]">
                    <div
                        id="track-highlight"
                        class="absolute left-0 right-0 top-0 h-[4px] bg-navyBlue"
                        ref="trackHighlight"
                    ></div>

                    <button
                        id="track1"
                        class="absolute z-[2] text-left border border-red-50 bg-white outline-none -top-[7px] h-[18px] w-[18px] -ml-[9px] -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-navyBlue undefined ring ring-offset-1"
                        ref="track1"
                    ></button>

                    <button
                        id="track2"
                        class="absolute z-[2] text-left border border-red-50 bg-white outline-none -top-[7px] h-[18px] w-[18px] -ml-[9px] -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-navyBlue undefined ring ring-offset-1"
                        ref="track2"
                    ></button>
                </div>

                <div class="track" ref="_vpcTrack"></div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-range-slider', {
            template: '#v-range-slider-template',

            props: {
                min: {
                    default: 10
                },

                max: {
                    default: 210
                }
            },

            data() {
                return {
                    minValue: 40,

                    maxValue: 150,

                    step: 5,

                    totalSteps: 0,

                    percentPerStep: 1,

                    trackWidth: null,

                    isDragging: false,

                    possition: {
                        currentTrack: null
                    }
                }
            },

            mounted() {
                this.totalSteps = (this.max - this.min) / this.step;

                this.percentPerStep = 100 / this.totalSteps;
                
                document.querySelector('#track1').style.left = this.valueToPercent(this.minValue) + '%';
                
                document.querySelector('#track2').style.left = this.valueToPercent(this.maxValue) + '%';
                
                this.setTrackHightlight();

                var self = this;

                ['mousedown', 'mouseup', 'mousemove', 'touchstart', 'touchmove', 'touchend'].forEach( type => {
                    if (type == 'mouseup' || type == 'mousemove') {
                        document.body.addEventListener(type, (event) => {
                            if (self.isDragging && self.possition.currentTrack) {
                                self[type](event, self.possition.currentTrack);
                            }
                        })
                    }

                    document.querySelector('#track1').addEventListener(type, (event) => {
                        event.stopPropagation();

                        self[type](event, 'track1');
                    })

                    document.querySelector('#track2').addEventListener(type, (event) => {
                        event.stopPropagation();

                        self[type](event, 'track2');
                    })
                })
            },

            methods: {
                moveTrack(track, event) {
                    //if (! this.isDragging) {
                        console.log(this.isDragging, track, event);
                    //}

                    let percentInPx = this.getPercentInPx();
                    
                    let trackX = Math.round(this.$refs._vpcTrack.getBoundingClientRect().left);
                    let clientX = event.clientX;
                    let moveDiff = clientX-trackX;

                    let moveInPct = moveDiff / percentInPx;

                    if (moveInPct < 1 || moveInPct > 100) return;

                    let value = ( Math.round(moveInPct / this.percentPerStep) * this.step ) + this.min;

                    if (track === 'track1') {
                        if (value >= (this.maxValue - this.step)) return;

                        this.minValue = value;
                    }

                    if (track === 'track2') {
                        if(value <= (this.minValue + this.step)) return;

                        this.maxValue = value;
                    }
                    
                    this.$refs[track].style.left = moveInPct + '%';

                    this.setTrackHightlight();
                },

                mousedown(event, track) {
                    if (this.isDragging) return;

                    this.isDragging = true;
                    this.possition.currentTrack = track;
                },

                touchstart(event, track) {
                    this.mousedown(event, track);
                },

                mouseup(event, track) {
                    if (! this.isDragging) return;

                    this.isDragging = false;
                },

                touchend(event, track) {
                    this.mouseup(event, track);
                },

                mousemove(event, track) {
                    if (! this.isDragging) return;

                    this.moveTrack(track, event);
                },

                touchmove(event, track) {
                    this.mousemove(event.changedTouches[0], track);
                },

                valueToPercent(value) {
                    return ((value - this.min) / this.step) * this.percentPerStep;
                },

                setTrackHightlight(){
                    this.$refs.trackHighlight.style.left = this.valueToPercent(this.minValue) + '%';

                    this.$refs.trackHighlight.style.width = (this.valueToPercent(this.maxValue) - this.valueToPercent(this.minValue)) + '%';
                },

                getPercentInPx() {
                    let trackWidth = this.$refs._vpcTrack.offsetWidth;
                    let oneStepInPx = trackWidth / this.totalSteps;
                    
                    let percentInPx = oneStepInPx / this.percentPerStep;
                    
                    return percentInPx;
                }
            }
        });
    </script>
@endPushOnce