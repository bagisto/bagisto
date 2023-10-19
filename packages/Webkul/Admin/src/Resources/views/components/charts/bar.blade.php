<v-charts-bar {{ $attributes }}></v-charts-bar>

@pushOnce('scripts')
    {{-- SEO Vue Component Template --}}
    <script type="text/x-template" id="v-charts-bar-template">
        <canvas
            :id="$.uid + '_chart'"
            class="flex items-end w-full aspect-[3.23/1]"
        ></canvas>
    </script>

    <script type="module">
        app.component('v-charts-bar', {
            template: '#v-charts-bar-template',

            props: ['labels', 'datasets'],

            data() {
                return {
                    chart: undefined,
                }
            },

            mounted() {
                this.prepare();
            },

            methods: {
                prepare() {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(document.getElementById(this.$.uid + '_chart'), {
                        type: 'bar',
                        
                        data: {
                            labels: this.labels,

                            datasets: this.datasets,
                        },
                
                        options: {
                            aspectRatio: 3.17,
                            
                            plugins: {
                                legend: {
                                    display: false
                                },

                                {{-- tooltip: {
                                    enabled: false,
                                } --}}
                            },
                            
                            scales: {
                                x: {
                                    beginAtZero: true,

                                    border: {
                                        dash: [8, 4],
                                    }
                                },

                                y: {
                                    beginAtZero: true,
                                    border: {
                                        dash: [8, 4],
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce