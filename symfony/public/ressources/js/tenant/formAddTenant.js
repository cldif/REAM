const slideDuration = 500;

function initLegalRrepresentative() {
  let selectedOption = $("#tenant_parent").val();

  if (selectedOption == "Mere") {
    $(".mother-form").slideToggle(slideDuration);
  } else if (selectedOption == "Pere") {
    $(".father-form").slideToggle(slideDuration);
  }
}

$("#tenant_parent").on("change", function() {
  if (this.value == "Mere") {
    $(".mother-form")
      .delay(slideDuration)
      .slideToggle(slideDuration);
    $(".father-form").slideToggle(slideDuration);
  } else if (this.value == "Pere") {
    $(".father-form")
      .delay(slideDuration)
      .slideToggle(slideDuration);
    $(".mother-form").slideToggle(slideDuration);
  }
});

initLegalRrepresentative();
