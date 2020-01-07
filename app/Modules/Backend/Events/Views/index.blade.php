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
        <a href="#" class="btn btn-success test-button">
            <i class="now-ui-icons ui-1_simple-add"></i>&nbsp;Test
        </a>
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
    $(document).on('click', '.test-button', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/dashboard/events/render_event',
            method: 'GET',
            success: (data) => {
                var eventData;

                $.each(data, function (k,v) {
                    eventData = {
                        title: v.en_title,
                        start: v.event_date,
                        className: 'event-green'
                    };
                    $calendar.fullCalendar('renderEvent', eventData, true); 
                });

                $calendar.fullCalendar('unselect');
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
        });
    });

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
                            className: 'event-green'
                        };
                        $calendar.fullCalendar('renderEvent', eventData, true); 
                    }
                    $calendar.fullCalendar('unselect');
                });
            },
            editable: true,
            eventLimit: true,
            events: <?php echo json_encode($events); ?>,
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