let deleteTenantUrl = $("#js-passthrough-delete-tenant-url").text();
let websiteIndex = $("#js-passthrough-index-url").text();

function makeAjaxRequest() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: deleteTenantUrl,
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

$("#delete-tenant-button").on("click", function() {
  makeAjaxRequest()
    .then(data => {
      console.log("%c Deleted successfully", "color:green;");
      console.log(data);
      $(location).attr("href", websiteIndex);
    })
    .catch(error => {
      console.log("%c Error during deletion", "color:red;");
      console.log(data);
      $(location).attr("href", websiteIndex);
    });
});
