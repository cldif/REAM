const slideDuration = 500;

let updateGarant = () => {
  let selectorValue = $("#record_garantChoice").val();
  console.log(`Garant selector value : ${selectorValue}`);

  if (selectorValue == "3") {
    $(".garant-form").slideDown(slideDuration);
  } else {
    $(".garant-form").slideUp(slideDuration);
  }
};

$("#record_garantChoice").on("change", updateGarant);

updateGarant(); // Force update for initialization
