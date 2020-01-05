$(document).ready(function () {

});

$(document).on('click', '.blog-delete', function (e) {
    var parentElement = $(this).parent('.parent');

    var slug = $(parentElement).find('.token').val();

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/dashboard/blog/delete',
                method: "DELETE",
                data: {
                    slug: slug,
                    '_token': $('input[name=_token]').val()
                },
                beforeSend: () => {
                    Swal.fire({
                        title: 'Sending....',
                        html: '<span class="text-success">Waiting for data to be sent</span>',
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        },
                    })
                },
                success: (data) => {
                    Swal.disableLoading();

                    Swal.close();
                    
                    if (data.error) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops',
                            html: '<span class="text-danger">' + data.error + '</span>',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            type: 'success',
                            title: 'Successfully delete data!',
                            html: '<span class="text-success">Your page will be refreshed shortly.</span>',
                            showConfirmButton: false
                        })
                        window.setTimeout(() => {
                            location.reload();
                        }, 1000);
                    };
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    formatErrorMessage(jqXHR, errorThrown)
                }
            });

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Cancel button is pressed
            Swal.fire({
                type: 'info',
                title: 'Your data is safe!',
                showConfirmButton: false,
                timer: 1500
            });
        };
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
