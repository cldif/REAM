import * as reqWrappers from "../ajaxRequests/wrappers.js";

let deleteRoomUrl = $("#js-passthrough-delete-entity-url").text();
let websiteIndexUrl = $("#js-passthrough-index-url").text();
let addDocumentUrl = $("#js-passthrough-add-document-url").text();
let delDocumentUrl = $("#js-passthrough-del-document-url").text();
let getDocumentUrl = $("#js-passthrough-get-document-url").text();

let docNameToBeDeleted = undefined;
let fileTypeToBeAdded = undefined;

/* Ajax Requests bindings */
$("#del-room-btn").on("click", function() {
  reqWrappers.delEntity(deleteRoomUrl, websiteIndexUrl);
});

$("#add-doc-btn").on("click", function() {
  let file = document.getElementById("file-input").files[0];
  reqWrappers.addDoc(addDocumentUrl, file, fileTypeToBeAdded);
});

$("#del-doc-btn").on("click", function() {
  reqWrappers.delDoc(delDocumentUrl, docNameToBeDeleted);
});

$(".get-doc-btn").on("click", function() {
  let fileName = this.name;
  reqWrappers.getDoc(getDocumentUrl, fileName);
});

/* Preparing requests headers when opening a modal */
$(".del-doc-modal-button").on("click", function() {
  docNameToBeDeleted = this.name;
});

$(".add-doc-modal-button").on("click", function() {
  docTypeToBeAdded = this.name;
});
