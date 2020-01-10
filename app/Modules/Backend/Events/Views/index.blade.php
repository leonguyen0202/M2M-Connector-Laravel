@extends('backend.master')

@push('meta')
<title>
    Event Calendar - {{implode(" ", explode("_", env('APP_NAME')))}}
</title>
@endpush

@push('customCSS')
<style>
    .event-box .row .calendar-trash {
        flex-grow: 1;
    }
</style>
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
        @can('delete-event', User::class)
        <div class="col-md-2">
            <div class="card card-calendar">
                <div class="card-body text-center event-box">
                    <span class="badge badge-success mx-auto">Draft</span>

                    <div class="row draft-box"></div>

                    <span class="badge badge-danger mx-auto">Trash</span>
                    <div class="row trash-element trash-box" id="calendarTrash">
                        <div class="card card-background"
                            style="background-color:#f38181; background-image: url( {{ url(asset('images/cover/trash-can-cover.png')) }} )">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        <!-- end col-md-2 -->
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
<script src="{{ asset('js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
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
            selectable: '{!! render_conditional_class( Auth::user()->can("create-event"), "true", "" ) !!}',
            selectHelper: '{!! render_conditional_class( Auth::user()->can("create-event"), "true", "" ) !!}',
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
                if(start.isBefore(moment())) {
                    $('#calendar').fullCalendar('unselect');
                    return false;
                };
                Swal.mixin({
                    confirmButtonText: 'Next &rarr;',
                    showCancelButton: true,
                    customClass: {
                        input: 'input-class',
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger',
                    },
                    buttonsStyling: false,
                    progressSteps: ['1', '2', '3', '4', '5'],
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
                        buttonsStyling: false,
                        customClass: {
                            input: 'title-field',
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger',
                        },
                        inputValidator: (value) => {
                            
                            title = $('.title-field').val();

                            return new Promise((resolve) => {
                                if (!value) {
                                    resolve('You need to provide us a title!')
                                } else {
                                    $.ajax({
                                        url: '/dashboard/events/check_title/'+ title,
                                        method: 'GET',
                                        success: (data) => {
                                            if (data.error) {
                                                resolve(''+data.error+'');
                                            } else {
                                                resolve();
                                            };
                                        },
                                        error: (jqXHR, textStatus, errorThrown) => {
                                            console.log(jqXHR);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });
                                }
                            });
                        },
                    },
                    {
                        title: 'Select image',
                        input: 'file',
                        customClass: {
                            input: 'event-background-image',
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger',
                        },
                        inputAttributes: {
                            accept: 'image/jpg, image/png, image/jpeg',
                            'aria-label': 'Upload your profile picture'
                        },
                        onBeforeOpen: () => {
                            $(".event-background-image").change(function () {
                                var reader = new FileReader();
                                reader.onload = (e) => {
                                    console.log(e);
                                };
                                reader.readAsDataURL(this.files[0]);
                            });
                        },
                        onClose: () => {
                            background = $(".event-background-image")[0].files[0];
                        },
                    },
                    {
                        title: 'Create description',
                        input: 'textarea',
                        customClass: {
                            input: 'event-description',
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger',
                        },
                        onBeforeOpen: () => {
                            tinymce.init({
                                mode: "textareas",
                                selector: 'textarea.event-description',
                                height: 500,
                                plugins: "fullscreen",
                                branding: false
                            });
                        },
                        onClose: () => {
                            tinymce.triggerSave();
                            event_description = tinymce.activeEditor.getContent();
                        }
                    },
                    {
                        title: 'Got Google Form?',
                        icon: 'question',
                        input: 'text',
                        inputPlaceholder: 'Provide us url of your form if have',
                        showCancelButton: true,
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger',
                        },
                        buttonsStyling: false,
                    }
                ]).then((result) => {
                    if (result.value) {

                        var className = 'event-azure';

                        if (result.value[0] == 'member') {
                            className = 'event-orange';
                        };

                        var form = new FormData();

                        form.append('type', result.value[0]);

                        form.append('title', result.value[1]);
                        
                        form.append("fileToUpload", background);

                        form.append('description', event_description);

                        form.append('url', result.value[4]);

                        form.append('className', className);

                        form.append('start', start.format("YYYY-MM-DD HH:mm:ss"));

                        form.append('end', end.format("YYYY-MM-DD HH:mm:ss"));

                        $.ajax({
                            url: '/dashboard/events',
                            method: 'POST',
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            cache: false,
                            data: form,
                            headers: {
                                'X-CSRF-Token': $('input[name=_token]').val(),
                            },
                            success: (data) => {
                                console.log(data);
                            },
                            error: (jqXHR, textStatus, errorThrown) => {
                                console.log(jqXHR);
                                console.log(textStatus);
                                console.log(errorThrown);
                            }
                        });

                        eventData = {
                            title: result.value[1],
                            start: start,
                            end:end,
                            className: className
                        };
                        
                        $calendar.fullCalendar('renderEvent', eventData, true);
                    };
                    $calendar.fullCalendar('unselect');
                });
            },
            editable: '{!! render_conditional_class( Auth::user()->can("edit-event"), "true", "" ) !!}',
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
            eventDragStop: (event,jsEvent) => {

                var trashEl = $('#calendarTrash');
                var ofs = trashEl.offset();

                var x1 = ofs.left;
                var x2 = ofs.left + trashEl.outerWidth(true);
                var y1 = ofs.top;
                var y2 = ofs.top + trashEl.outerHeight(true);

                if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 && jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            Swal.fire({
                                type: 'success',
                                title: 'Successfully delete data!',
                                html: '<span class="text-success">Your page will be refreshed shortly.</span>',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            console.log(event.title);

                            $('#calendar').fullCalendar('removeEvents', event.id);
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                type: 'info',
                                title: 'Your data is safe!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        };
                    });
                }
            }
        });

        $(".event-box").css( {'height':( $("#calendar").height() + 'px' )} );

        $(".draft-box").css( {'height': ( ($("#calendar").height() / 2) + 'px' ) } );

        $(".trash-box").css({ 'height': ( ($(".event-box").height() - $(".draft-box").height()) + 'px' ) });
    });
</script>
{{-- @endif --}}
@endpush