const slideDuration = 500;

let updateGarant = () => {
  let selectorValue = $("#record_guarantorChoice").val();
  console.log(`Garant selector value : ${selectorValue}`);

  if (selectorValue == "3") {
    $(".guarantor-form").slideDown(slideDuration);
  } else {
    $(".guarantor-form").slideUp(slideDuration);
  }
};

$("#record_guarantorChoice").on("change", updateGarant);

updateGarant(); // Force update for initialization
