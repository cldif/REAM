const slideDuration = 500;
let formsMinWidth;
const formsMaxWidth = "50%";

function getFormsMinWidth() {
  /* This function could surely be improved.
  It's a very ugly solution, but it works. */

  $(".father-form").show();
  res = $(".including-forms").width();
  $(".father-form").hide();
  return res;
}

function initLegalRrepresentative() {
  formsMinWidth = getFormsMinWidth();
  updateLegalRepresentative();
}

let updateLegalRepresentative = () => {
  let selectorValue = $("#tenant_parent").val();
  console.log(`Tenant parent selector value : ${selectorValue}`);

  if (selectorValue == "1") {
    let collapse = $(".mother-form")
      .slideUp(slideDuration)
      .promise();
    collapse
      .then(() =>
        $(".father-form")
          .slideDown(slideDuration)
          .promise()
      )
      .then(() => {
        $(".including-forms").animate({ width: formsMinWidth });
      });
  }

  if (selectorValue == "2") {
    let collapse = $(".father-form")
      .slideUp(slideDuration)
      .promise();
    collapse
      .then(() =>
        $(".mother-form")
          .slideDown(slideDuration)
          .promise()
      )
      .then(() => {
        $(".including-forms").animate({ width: formsMinWidth });
      });
  } else if (selectorValue == "3") {
    $(".including-forms").animate({ width: formsMaxWidth });
    $(".mother-form").slideDown(slideDuration);
    $(".father-form").slideDown(slideDuration);
  }
};

$("#tenant_parent").on("change", updateLegalRepresentative);

initLegalRrepresentative();
