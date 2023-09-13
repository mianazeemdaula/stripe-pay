import './bootstrap';

import Swal from 'sweetalert2';
import 'bootstrap-icons/font/bootstrap-icons.css';

window.Swal = Swal;


$(() => {
    $(window).scrollTop(function () {
        // this will work when your window scrolled.
        var height = $(window).scrollTop();
        if (height > 5) {
            $("header").addClass("scrolled");
        } else {
            $("header").removeClass("scrolled");
        }
    });
})