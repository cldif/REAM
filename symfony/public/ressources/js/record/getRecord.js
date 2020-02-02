import * as reqWrappers from "../ajaxRequests/wrappers.js";

let deleteEntityUrl = $("#js-passthrough-delete-entity-url").text();
let websiteIndexUrl = $("#js-passthrough-index-url").text();
let getDocumentUrl = $("#js-passthrough-get-document-url").text();

/* Ajax Requests bindings */
$("#del-entity-btn").on("click", function() {
  reqWrappers.delEntity(deleteEntityUrl, websiteIndexUrl);
});

$(".get-doc-btn").on("click", function() {
  reqWrappers.getDoc(getDocumentUrl, this.name);
});
