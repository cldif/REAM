const slideDuration = 500;

function initLegalRrepresentative() {
  let selectedOption = $("#tenant_parent").val();

  if (selectedOption == "2") {
    $(".mother-form").slideToggle(slideDuration);
  } else if (selectedOption == "1") {
    $(".father-form").slideToggle(slideDuration);
  }
}

$("#tenant_parent").on("change", function() {
  if (this.value == "2") {
    $(".mother-form")
      .delay(slideDuration)
      .slideToggle(slideDuration);
    $(".father-form").slideToggle(slideDuration);
  } else if (this.value == "1") {
    $(".father-form")
      .delay(slideDuration)
      .slideToggle(slideDuration);
    $(".mother-form").slideToggle(slideDuration);
  }
});

initLegalRrepresentative();
