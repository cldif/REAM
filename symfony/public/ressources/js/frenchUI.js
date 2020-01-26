function getNodesThatContain(text) {
  var textNodes = $(document)
    .find(":not(iframe, script)")
    .contents()
    .filter(function() {
      return this.nodeType == 3 && this.textContent.indexOf(text) > -1;
    });
  return textNodes.parent();
}

function replaceInDocument(oldString, newString) {
  getNodesThatContain(oldString).each(function() {
    var oldText = $(this).text();
    newText = oldText.replace(oldString, newString);
    $(this).text(newText);
  });
}

replaceInDocument("identityCard", "Carte d'identité");
