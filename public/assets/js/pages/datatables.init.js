/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./resources/js/pages/datatables.init.js ***!
  \***********************************************/
$(document).ready(function () {
  $("#datatable").DataTable(), $("#datatable-buttons").DataTable({
    lengthChange: !1,
    buttons: ["copy", "excel", "pdf", "colvis"]
  }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm");
});
/******/ })()
;


// $(document).ready(function () {
//     // Show loading indicator
//     Swal.fire({
//       title: 'Loading...',
//       text: 'Setting up the table, please wait.',
//       allowOutsideClick: false,
//       didOpen: () => {
//         Swal.showLoading();
//       }
//     });

//     // Initialize DataTable
//     var table = $("#datatable").DataTable({
//       // Your DataTable options here
//       buttons: ["copy", "excel", "pdf", "colvis"],
//       lengthChange: false,
//       initComplete: function() {
//         // Close loading indicator once DataTable is fully initialized
//         Swal.close();
//       }
//     });

//     table.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
//     $(".dataTables_length select").addClass("form-select form-select-sm");
//   });

