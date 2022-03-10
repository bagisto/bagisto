<div class="grid-container">

    <calendar-filters></calendar-filters>
    
</div>

<calendar-component></calendar-component>

@push('scripts')
    <script type="text/x-template" id="calendar-filters-tempalte">
        <div class="form-group datagrid-filters" style="margin-bottom: 20px;">
            <div></div>
            
            <div class="filter-right" style="grid-template-columns: auto;">

                @include('bookingproduct::admin.sales.bookings.index.view-swither')

            </div>

        </div>
    </script>

    <script type="text/x-template" id="calendar-component-tempalte">
        <div class="calendar-container">

            <vue-cal
                hide-view-selector
                :watchRealTime="true"
                :twelveHour="true"
                :disable-views="['years', 'year', 'month', 'day']"
                style="height: calc(100vh - 240px);"
                :events="events"
                @ready="getBookings"
                @view-change="getBookings"
                :on-event-click="onEventClick"
            />

        </div>
    </script>

    <script>
        Vue.component('calendar-filters', {
            template: '#calendar-filters-tempalte',
        });


        Vue.component('calendar-component', {
            template: '#calendar-component-tempalte',
            
            data: function () {
                return {
                    events: []
                }
            },

            methods: {
                getBookings: function ({startDate, endDate}) {
                    this.$root.pageLoaded = false;

                    this.$http.get("{{ route('admin.sales.bookings.get', ['view_type' => 'calendar']) }}" + `&startDate=${new Date(startDate).toLocaleDateString("en-US")}&endDate=${new Date(endDate).toLocaleDateString("en-US")}`)
                        .then(response => {
                            this.$root.pageLoaded = true;

                            this.events = response.data.bookings;
                        })
                        .catch(error => {
                            this.$root.pageLoaded = true;
                        });
                },

                onEventClick : function (event) {
                    window.location.href = "{{ route('admin.sales.orders.view', 'order_id') }}/".replace('order_id', event.order_id)
                }
            }
        });
    </script>
@endpush