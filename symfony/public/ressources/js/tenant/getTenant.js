let deleteTenantUrl = $("#js-passthrough-delete-tenant-url").text();
let websiteIndexUrl = $("#js-passthrough-index-url").text();
let addDocumentUrl = $("#js-passthrough-add-document-url").text();
let delDocumentUrl = $("#js-passthrough-del-document-url").text();
let getDocumentUrl = $("#js-passthrough-get-document-url").text();

let docNameToBeDeleted = undefined;
let docTypeToBeAdded = undefined;

/* Ajax Requests definitions */

function deleteTenantAR() {
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

function addDocumentAR(url, file, fileType) {
  let formData = new FormData();
  formData.append("document", file);

  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      headers: { documentType: fileType },
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

function delDocumentAR(url, fileName) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      type: "DELETE",
      headers: { documentName: fileName },
      success: function(result) {
        resolve(result);
      },
      error: function(result) {
        reject(result);
      }
    });
  });
}

function getDocumentAR(url, fileName) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      type: "GET",
      headers: { documentName: fileName },
      xhrFields: {
        responseType: "blob"
      },
      success: function(blob) {
        var link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName;
        link.click();
        resolve(blob);
      },
      error: function(result) {
        reject(result);
      }
    });
  });
}

/* Ajax Requests bindings */

$("#del-room-btn").on("click", function() {
  deleteTenantAR()
    .then(data => {
      console.log("%c Room deleted successfully.", "color:green;");
      console.log(data);
    })
    .catch(error => {
      console.log("%c Error during room deletion.", "color:red;");
      console.log(error);
    })
    .finally(() => {
      $(location).attr("href", websiteIndexUrl);
    });
});

$("#add-doc-btn").on("click", function() {
  let file = document.getElementById("file-input").files[0];

  addDocumentAR(addDocumentUrl, file, docTypeToBeAdded)
    .then(data => {
      console.log("%c Document added successfully.", "color:green;");
      console.log(data);
    })
    .catch(error => {
      console.log("%c Error when adding a document.", "color:red;");
      console.log(error);
    })
    .finally(() => {
      location.reload(true);
      docTypeToBeAdded = undefined;
    });
});

$("#del-doc-btn").on("click", function() {
  let fileName = docNameToBeDeleted;

  delDocumentAR(delDocumentUrl, fileName)
    .then(data => {
      console.log("%c Document deleted successfully.", "color:green;");
      console.log(data);
    })
    .catch(error => {
      console.log("%c Error when deleting a document.", "color:red;");
      console.log(error);
    })
    .finally(() => {
      location.reload(true);
      fileName = undefined;
    });
});

$(".get-doc-btn").on("click", function() {
  let fileName = this.name;

  getDocumentAR(getDocumentUrl, fileName)
    .then(data => {
      console.log("%c Document downloaded successfully.", "color:green;");
      console.log(data);
    })
    .catch(error => {
      console.log("%c Error when downloading a document.", "color:red;");
      console.log(error);
    });
});

/* Event binding */
$(".del-doc-modal-button").on("click", function() {
  docNameToBeDeleted = this.name;
});

$(".add-doc-modal-button").on("click", function() {
  docTypeToBeAdded = this.name;
});
