import './bootstrap';
import Chart from 'chart.js/auto';
import 'bootstrap-icons/font/bootstrap-icons.css';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Chart = Chart;



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