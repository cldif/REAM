let deleteLocalUrl = $("#js-passthrough-delete-local-url").text();
let websiteIndex = $("#js-passthrough-index-url").text();

function makeAjaxRequest() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: deleteLocalUrl,
      type: "DELETE",
      success: function(result) {
        resolve(result);
      },
      error: function(result) {
        reject(result);
      }
    });
  });
}

$("#delete-local-button").on("click", function() {
  makeAjaxRequest()
    .then(data => {
      console.log("%c Deleted successfully", "color:green;");
      console.log(data);
      $(location).attr("href", websiteIndex);
    })
    .catch(error => {
      console.log("%c Error during deletion", "color:red;");
      console.log(error);
      $(location).attr("href", websiteIndex);
    });
});
