initBackend = {
    sweetAlertError: (message) => {
        Swal.fire({
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    },

    formatErrorMessage: (jqXHR, exception) => {
        if (jqXHR.status === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Not connected.\nPlease verify your network connection.',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: 'error',
                title: 'The request not found.',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (jqXHR.status == 401) {
            Swal.fire({
                icon: 'error',
                title: 'Sorry!! You session has expired. Please login to continue access.',
                showConfirmButton: false,
                timer: 1500
            });
            return (
                window.setTimeout(() => {
                    location.reload();
                }, 1000)
            );
        } else if (jqXHR.status == 500) {
            Swal.fire({
                icon: 'error',
                title: 'Internal Server Error.',
                showConfirmButton: false,
                timer: 1500
            });
        } else if (exception === 'parsererror') {
            Swal.fire({
                icon: 'error',
                title: 'Requested JSON parse failed.',
                showConfirmButton: false,
                timer: 1500
            });
        } else if (exception === 'timeout') {
            Swal.fire({
                icon: 'error',
                title: 'Time out error',
                showConfirmButton: false,
                timer: 1500
            });
        } else if (exception === 'abort') {
            Swal.fire({
                icon: 'error',
                title: 'Ajax request aborted.',
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Unknown error occured. Please try again.',
                showConfirmButton: false,
                timer: 1500
            });
        };
    },
}