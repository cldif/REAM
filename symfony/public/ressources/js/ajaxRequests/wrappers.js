import * as requests from "./requests.js";

export function delEntity(deleteEntityUrl, websiteIndexUrl) {
  requests.delEntityReq(deleteEntityUrl).then(() => {
    $(location).attr("href", websiteIndexUrl);
  });
}

export function addDoc(addDocumentUrl, file, fileType) {
  requests.addDocReq(addDocumentUrl, file, fileType).then(() => {
    location.reload(true);
  });
}

export function delDoc(delDocumentUrl, fileName) {
  requests.delDocReq(delDocumentUrl, fileName).then(() => {
    location.reload(true);
  });
}

export function getDoc(getDocumentUrl, fileName) {
  requests.getDocReq(getDocumentUrl, fileName).then(() => {
    location.reload(true);
  });
}
