
var botmanWidget = {
    title: 'M2M Connector Chat Bot',
    aboutText: 'M2M Connector Website',
    introMessage: "✋ Hi! I'm the awesome automated chat bot.",
};
$(document).ready(function () {
    $(".alert-danger").fadeTo(2000, 700).slideUp(700, function(){
        $(".alert-danger").slideUp(700);
    });
    
    var pageNumber = 2;

    var slug = $('#_slug').val();

    $('.index-load-more').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            url: "?page=" + pageNumber,
            method: "GET",
            success: (data) => {
                pageNumber += 1;
                if (data.button) {
                    $('.index-load-button').empty();
                    $('.index-load-button').append(data.button);
                } else {
                    $('.index-load-data').append(data.html);
                }
            },
            error: (data) => {

            },
        })
    });

    $('.blog-load-more').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: "blogs?page=" + pageNumber,
            method: "GET",
            success: (data) => {
                pageNumber += 1;
                if (data.button) {
                    $('.blog-button').empty();
                    $('.blog-button').append(data.button);
                } else {
                    $('.blog-data').append(data.html);
                }
            },
            error: (data) => {

            },
        })
    });

    $('.categories-load-more').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: "categories?page=" + pageNumber,
            method: "GET",
            success: (data) => {
                pageNumber += 1;
                if (data.button) {
                    $('.categories-button').empty();
                    $('.categories-button').append(data.button);
                } else {
                    $('.categories-load-data').append(data.html);
                }
            },
            error: (data) => {

            },
        })
    });

    $('.category-load-more').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            url: slug + "?page=" + pageNumber,
            method: "GET",
            beforeSend: () => {
                Swal.fire({
                    title: 'Requesting....',
                    html: '<span class="text-success">Waiting for data to be sent</span>',
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                })
            },
            success: (data) => {
                console.clear();

                Swal.disableLoading();

                Swal.close();

                pageNumber += 1;
                if (data.button) {
                    $('.category-button').empty();
                    $('.category-button').append(data.button);
                } else {
                    $('.category-load-data').append(data.html);
                }
            },
            error: (data) => {

            },
        })
    });

    
});

function sweetAlertError(message) {
    Swal.fire({
        type: 'error',
        title: message,
        showConfirmButton: false,
        timer: 1000
    })
};

function formatErrorMessage(jqXHR, exception) {
    if (jqXHR.status === 0) {
        return (
            sweetAlertError('Not connected.\nPlease verify your network connection.')
        );
    } else if (jqXHR.status == 404) {
        return (
            sweetAlertError('The request not found.')
        );
    } else if (jqXHR.status == 401) {
        Swal.fire({
            type: 'error',
            title: 'Sorry!! You session has expired. Please login to continue access.',
            showConfirmButton: false,
            timer: 1500
        })
        return (
            window.setTimeout(() => {
                location.reload();
            }, 1000)
        );
    } else if (jqXHR.status == 500) {
        return (
            sweetAlertError('Internal Server Error.')
        );
    } else if (exception === 'parsererror') {
        return (
            sweetAlertError('Requested JSON parse failed.')
        );
    } else if (exception === 'timeout') {
        return (
            sweetAlertError('Time out error.')
        );
    } else if (exception === 'abort') {
        return (
            sweetAlertError('Ajax request aborted.')
        );
    } else {
        return (
            sweetAlertError('Unknown error occured. Please try again.')
        );
    };
};
