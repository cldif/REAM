export function sendRequest(ajaxSettings) {
  return new Promise((resolve, reject) => {
    ajaxSettings.success = function(result) {
      resolve(result);
    };
    ajaxSettings.error = function(result) {
      reject(result);
    };

    $.ajax(ajaxSettings);
  });
}

export async function delEntityReq(url) {
  let ajaxSettings = {
    url: url,
    type: "DELETE"
  };

  try {
    const result = await sendRequest(ajaxSettings);
    console.log("%cEntity deleted successfully.", "color:green;");
    console.log("%cServer response :", "color: orange;");
    console.log(result);
  } catch (error) {
    console.log("%cError during entity deletion.", "color:red;");
    console.log("%cServer response :", "color: orange;");
    console.log(error.responseText);
    console.log(error);
  }
}

export async function addDocReq(url, file, fileType) {
  let formData = new FormData();
  formData.append("document", file);

  let ajaxSettings = {
    url: url,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false
  };
  if (fileType != undefined) {
    ajaxSettings.headers = { documentType: fileType };
  }

  try {
    const result = await sendRequest(ajaxSettings);
    console.log("%cDocument added successfully.", "color:green;");
    console.log("%cServer response :", "color: orange;");
    console.log(result);
  } catch (error) {
    console.log("%cError during document addition.", "color:red;");
    console.log("%cServer response :", "color: orange;");
    console.log(error.responseText);
    console.log(error);
  }
}

export async function delDocReq(url, fileName) {
  let ajaxSettings = {
    url: url,
    type: "DELETE",
    headers: { documentName: fileName }
  };

  try {
    const result = await sendRequest(ajaxSettings);
    console.log("%cDocument deleted successfully.", "color:green;");
    console.log("Server response : " + result);
  } catch (error) {
    console.log("%cError during document deletion.", "color:red;");
    console.log("%cServer response :", "color: orange;");
    console.log(error.responseText);
    console.log(error);
  }
}

export async function getDocReq(url, fileName) {
  let ajaxSettings = {
    url: url,
    type: "GET",
    headers: { documentName: fileName },
    xhrFields: {
      responseType: "blob"
    }
  };

  try {
    const result = await sendRequest(ajaxSettings);
    var link = document.createElement("a");
    link.href = window.URL.createObjectURL(result);
    link.download = fileName;
    link.click();

    console.log("%cDocument successfully retrieved.", "color:green;");
    console.log("%cServer response :", "color: orange;");
    console.log(result);
  } catch (error) {
    console.log("%cError when retrieving the document.", "color:red;");
    console.log("%cServer response :", "color: orange;");
    console.log(error.responseText);
    console.log(error);
  }
}
