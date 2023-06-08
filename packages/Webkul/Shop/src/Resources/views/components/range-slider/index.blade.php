<v-range-slider></v-range-slider>

@pushOnce('scripts')
    <script type="text/x-template" id="v-range-slider-template">
        <div class="relative h-[4px] w-full mt-[30px] mb-[24px]">
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
    </script>

    <script type="module">
        app.component('v-range-slider', {
            template: '#v-range-slider-template',

            props: {
                trackHeight: {
                    type: Number,
                    deafult: 1
                }
            },

            data() {
                return {
                    min: 10,

                    max: 210,

                    minValue: 40,

                    maxValue: 150,

                    step: 5,

                    totalSteps: 0,

                    percentPerStep: 1,

                    trackWidth: null,

                    isDragging: false,

                    pos: {
                        curTrack: null
                    }
                }
            },

            mounted() {
                this.totalSteps = (this.max - this.min) / this.step;

                this.percentPerStep = 100 / this.totalSteps;
                
                document.querySelector('#track1').style.left = this.valueToPercent(this.minValue) + '%'
                
                document.querySelector('#track2').style.left = this.valueToPercent(this.maxValue) + '%'
                
                this.setTrackHightlight()

                var self = this;

                ['mouseup', 'mousemove'].forEach( type => {
                    document.body.addEventListener(type, (event) => {
                        // ev.preventDefault();
                        if (self.isDragging && self.pos.curTrack){
                            self[type](event, self.pos.curTrack)
                        }
                    })
                });

                ['mousedown', 'mouseup', 'mousemove', 'touchstart', 'touchmove', 'touchend'].forEach( type => {
                    document.querySelector('#track1').addEventListener(type, (event) => {
                        event.stopPropagation();
                        self[type](event, 'track1')
                    })

                    document.querySelector('#track2').addEventListener(type, (event) => {
                        event.stopPropagation();
                        self[type](event, 'track2')
                    })
                })

                document.querySelector('.track').addEventListener('click', function(event) {
                    event.stopPropagation();
                    self.setClickMove(event)
                })

                document.querySelector('#track-highlight').addEventListener('click', function(event) {
                    event.stopPropagation();
                    self.setClickMove(event)
                })
            },

            methods: {
                moveTrack(track, event) {
                    let percentInPx = this.getPercentInPx();
                    
                    let trackX = Math.round(this.$refs._vpcTrack.getBoundingClientRect().left);
                    let clientX = event.clientX;
                    let moveDiff = clientX-trackX;

                    let moveInPct = moveDiff / percentInPx

                    if (moveInPct < 1 || moveInPct > 100) return;

                    let value = ( Math.round(moveInPct / this.percentPerStep) * this.step ) + this.min;

                    if (track === 'track1'){
                        if (value >= (this.maxValue - this.step)) return;

                        this.minValue = value;
                    }

                    if (track === 'track2'){
                        if(value <= (this.minValue + this.step)) return;

                        this.maxValue = value;
                    }
                    
                    this.$refs[track].style.left = moveInPct + '%';
                    this.setTrackHightlight()
                        
                },

                mousedown(event, track) {
                    if (this.isDragging) return;

                    this.isDragging = true;
                    this.pos.curTrack = track;
                },

                touchstart(event, track) {
                    this.mousedown(event, track)
                },

                mouseup(event, track) {
                    if (! this.isDragging) return;

                    this.isDragging = false
                },

                touchend(event, track) {
                    this.mouseup(event, track)
                },

                mousemove(event, track) {
                    if (! this.isDragging) return;

                    this.moveTrack(track, event)
                },

                touchmove(event, track) {
                    this.mousemove(event.changedTouches[0], track)
                },

                valueToPercent(value) {
                    return ((value - this.min) / this.step) * this.percentPerStep
                },

                setTrackHightlight(){
                    this.$refs.trackHighlight.style.left = this.valueToPercent(this.minValue) + '%'

                    this.$refs.trackHighlight.style.width = (this.valueToPercent(this.maxValue) - this.valueToPercent(this.minValue)) + '%'
                },

                getPercentInPx() {
                    let trackWidth = this.$refs._vpcTrack.offsetWidth;
                    let oneStepInPx = trackWidth / this.totalSteps;
                    
                    let percentInPx = oneStepInPx / this.percentPerStep;
                    
                    return percentInPx;
                },

                setClickMove(event) {
                    let track1Left = this.$refs.track1.getBoundingClientRect().left;
                    let track2Left = this.$refs.track2.getBoundingClientRect().left;

                    
                    if (event.clientX < track1Left){
                        this.moveTrack('track1', event)
                    } else if ((event.clientX - track1Left) < (track2Left - event.clientX) ){
                        this.moveTrack('track1', event)
                    } else {
                        this.moveTrack('track2', event)
                    }
                }
            }
        });
    </script>
@endPushOnce