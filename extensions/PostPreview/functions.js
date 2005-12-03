
function PreviewPost(FormName, Sender, WaitText) {
	Sender.disabled = true;
	Sender.value = WaitText;
   var frm = document[FormName];
   frm.IsPreview.value = "1";
   frm.submit();
}