let deleteRoomUrl = $("#js-passthrough-delete-room-url").text();
let websiteIndexUrl = $("#js-passthrough-index-url").text();
let addDocumentUrl = $("#js-passthrough-add-document-room-url").text();

/* Ajax Requests definitions */

function deleteRoomAR() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: deleteRoomUrl,
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

function addDocumentAR(url, file, name) {
  let formData = new FormData();
  formData.append("document", file);

  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      type: "POST",
      headers: { documentName: name },
      data: formData,
      processData: false,
      contentType: false,
      success: function(result) {
        resolve(result);
      },
      error: function(result) {
        reject(result);
      }
    });
  });
}

/* Ajax Requests bindings */

$("#delete-room-button").on("click", function() {
  deleteRoomAR()
    .then(data => {
      console.log("%c Room deleted successfully", "color:green;");
      console.log(data);
      $(location).attr("href", websiteIndexUrl);
    })
    .catch(error => {
      console.log("%c Error during room deletion", "color:red;");
      console.log(error);
      $(location).attr("href", websiteIndexUrl);
    });
});

$("#add-document-button").on("click", function() {
  let file = document.getElementById("file-input").files[0];
  let fileName = $("#file-name").val();

  addDocumentAR(addDocumentUrl, file, fileName)
    .then(data => {
      console.log("%c Document added successfully", "color:green;");
      console.log(data);
      location.reload(true);
    })
    .catch(error => {
      console.log("%c Error when adding a document.", "color:red;");
      console.log(error);
      location.reload(true);
    });
});
