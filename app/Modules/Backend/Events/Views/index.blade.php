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
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-10 ml-auto mr-auto">
            <div class="card card-calendar">
                <div class="card-body ">
                    <div id="calendar"></div>
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
                
                if (view.name != 'month') {
                    $(element).find('.fc-scroller').perfectScrollbar();
                }
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
                Swal.fire({
                title: 'Create an Event',
                html: '<div class="form-group">' +
                    '<input class="form-control" placeholder="Event Title" id="input-field">' +
                    '</div>',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
                }).then((result) => {
                var eventData;
                event_title = $('#input-field').val();

                if (event_title) {
                    eventData = {
                    title: event_title,
                    start: start,
                    end: end
                    };
                    $calendar.fullCalendar('renderEvent', eventData, true); 
                }
                $calendar.fullCalendar('unselect');
                });
            },
            editable: true,
            eventLimit: true,
            
            events: [{
                title: 'All Day Event',
                start: new Date(y, m, 1),
                className: 'event-default'
                },
                {
                title: 'Change',
                start: new Date(y, m, d - 1, 10, 30),
                allDay: false,
                className: 'event-green'
                },
                {
                title: 'Lunch',
                start: new Date(y, m, d + 7, 12, 0),
                end: new Date(y, m, d + 7, 14, 0),
                allDay: false,
                className: 'event-red'
                },
                {
                title: 'Nud-pro Launch',
                start: new Date(y, m, d - 2, 12, 0),
                allDay: true,
                className: 'event-azure'
                },
                {
                title: 'Birthday Party',
                start: new Date(y, m, d + 1, 19, 0),
                end: new Date(y, m, d + 1, 22, 30),
                allDay: false,
                className: 'event-azure'
                },
                {
                title: 'Click for Creative Tim',
                start: new Date(y, m, 21),
                end: new Date(y, m, 22),
                url: 'http://www.creative-tim.com/',
                className: 'event-orange'
                },
                {
                title: 'Click for Google',
                start: new Date(y, m, 21),
                end: new Date(y, m, 22),
                url: 'http://www.creative-tim.com/',
                className: 'event-orange'
                }
            ]
        });
    });
</script>
{{-- @endif --}}
@endpush