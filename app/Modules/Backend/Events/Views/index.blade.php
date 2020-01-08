@extends('backend.master')

@push('meta')
<title>
    Event Calendar - {{implode(" ", explode("_", env('APP_NAME')))}}
</title>
@endpush

@section('content')
<div class="panel-header">
    <div class="header text-center">
        <h2 class="title">Event Calendar</h2>
        <p class="category">
            You can now view or create new event via our calendar
        </p>
        &nbsp;
        @csrf
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-10 ml-auto mr-auto">
            <div class="card card-calendar">
                <div class="card-body ">
                    <div id="calendar">

                    </div>
                </div>
            </div>
        </div>
        <!-- end col-md-10 -->
    </div>
    <!-- end row -->
</div>
@endsection

@push('customJS')
<script type="text/javascript">
    $(document).ready(function() {
        
        $calendar = $('#calendar');

        today = new Date();
        y = today.getFullYear();
        m = today.getMonth();
        d = today.getDate();

        $calendar.fullCalendar({
            viewRender: function(view, element) {
                
            },
            header: {
                left: 'title',
                center: 'month,agendaWeek,agendaDay',
                right: 'prev,next,today'
            },
            defaultDate: today,
            selectable: true,
            selectHelper: true,
            views: {
                month: { 
                titleFormat: 'MMMM YYYY'
                },
                week: {
                titleFormat: " MMMM D YYYY"
                },
                day: {
                titleFormat: 'D MMM, YYYY'
                }
            },

            select: function(start, end) {
                Swal.mixin({
                    confirmButtonText: 'Next &rarr;',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    progressSteps: ['1', '2'],
                }).queue([
                    {
                        title: 'Choose calendar display type',
                        input: 'select',
                        inputOptions: {
                            member: 'Team member',
                            event: 'Events',
                        },
                        inputPlaceholder: 'Choose one option',
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (!value) {
                                    resolve('You need to select one option :)')
                                } else {
                                    type = value;

                                    resolve()
                                }
                            })
                        }
                    },
                    {
                        title: 'Create an title',
                        input: 'text',
                        inputPlaceholder: 'Provide us a title to post on calendar',
                        showCancelButton: true,
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (!value) {
                                    resolve('You need to provide us a title!')
                                } else {
                                    type = value;

                                    resolve()
                                }
                            })
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to provide us a title!'
                            }
                        }
                    },
                ]).then((result) => {
                    if (result.value) {
                        console.log(start.format());
                        console.log(end.format());

                        if (result.value[1]) {
                            eventData = {
                                title: result.value[1],
                                start: start,
                                end:end,
                                className: 'event-azure'
                            };
                            $calendar.fullCalendar('renderEvent', eventData, true); 
                        }
                        $calendar.fullCalendar('unselect');
                    };
                });
            },
            editable: true,
            eventLimit: true,
            events: {
                url: '/dashboard/events/render_event',
                method: 'POST',
                data: {
                    '_token': $('input[name=_token]').val()
                }
            },
            eventRender: function (event, element, view) {

                if (event.allDay === 'true') {

                    event.allDay = true;

                } else {

                    event.allDay = false;

                }

            },
        });
    });
</script>
{{-- @endif --}}
@endpush