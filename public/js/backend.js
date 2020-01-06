$(document).ready(function () {

});

$(document).on('click', '.blog-comments', function (e) {
    e.preventDefault();
    Swal.fire({
        type: 'success',
        title: 'Release soon',
        showConfirmButton: false,
        timer: 1000
    });
});

$(document).on('click', '.blog-view', function (e) {
    e.preventDefault();

    Swal.fire({
        type: 'success',
        title: 'Release soon',
        showConfirmButton: false,
        timer: 1000
    });
})

$(document).on('click', '.blog-delete', function (e) {
    e.preventDefault();
    
    var slug = $(this).data('slug');

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
                url: '/dashboard/blogs/'+slug,
                method: "DELETE",
                data: {
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
