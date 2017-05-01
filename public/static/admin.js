/**
 * Javaskripten, die nur im Admin-Teil verwendet werden
 */

// Seitenwechsel im gesamten Backend 
function adminPage(mode, id, filter) {
  this.frmAdminPage.mode.value = mode;
  this.frmAdminPage.id.value = id;
  this.frmAdminPage.filter.value = filter;
  this.frmAdminPage.submit();
}
function del(mode, objektname, id, filter) {
  if (!confirm('Wollen Sie ' + objektname + ' Nr. ' + id + ' wirklich löschen?')) {
    return;
  }
  adminPage(mode, id, filter);
}

// Ausfüllhilfe in ArtikelEdit
function writeAhref() {
  var ta = document.getElementById('text');
  var pos = ta.selectionStart;
  var content = ta.value;
  content = content.substring(0, pos) 
  + '<a href="" target="_blank"></a>'
  + content.substring(pos)
  ;
  ta.value = content;
}

