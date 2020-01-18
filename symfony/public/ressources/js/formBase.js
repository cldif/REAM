// Setting up correct class names to use Material Select
$("select").removeClass("form-control");
$("select").addClass("mdb-select md-form");

// Material Select Initialization
$(document).ready(function() {
  $(".mdb-select").materialSelect();
});

// Allow to use material Date Picker, it should always be regarded as activated.
$(".always-active > label").addClass("active");
