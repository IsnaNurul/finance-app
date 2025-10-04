import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const useSwall = () => {
    const success = (title, message) => {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
        });
    };

    const error = (title, message) => {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
        });
    };

    const warning = (title, message) => {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
        });
    };

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    const successToast = (message) => {
        Toast.fire({
            icon: 'success',
            title: 'Success',
            text: message,
        });
    };

    const errorToast = (message) => {
        Toast.fire({
            icon: 'error',
            title: 'Error',
            text: message,
        });
    };

    const confirmation = (title, text, fn) => {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                fn();
            }
        });
    };

    const confirmDelete = (fn) => {
        confirmation('Are you sure?', "You won't be able to revert this!", fn);
    };

    return {
        success,
        error,
        warning,
        successToast,
        errorToast,
        confirmation,
        confirmDelete,
    };
};

export default useSwall;
