function DeleteAttachment(AjaxUrl, AttachmentID)
{
	var ConfirmText = ("Are you sure you want to delete the attachment?");
	if (confirm(ConfirmText))
	{
		var AttachmentFileElement = document.getElementById("Attachment_" + AttachmentID);
		if( AttachmentFileElement ) AttachmentFileElement.innerHTML = "Deleting...";

		var Parameters = "AttachmentID="+AttachmentID;
		var dm = new DataManager();
		dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
		dm.RequestFailedEvent = HandleFailure;
		dm.LoadData(AjaxUrl+"?Action=RemoveAttachment&"+Parameters);
	}
}