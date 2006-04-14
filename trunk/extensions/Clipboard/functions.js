
// Insert clipboard data
function GetClipping(AjaxUrl, ClippingSelect) {
	var ClippingID = ClippingSelect.options[ClippingSelect.selectedIndex].value;
	ClippingSelect.selectedIndex = 0;
   ChangeLoaderText("Loading...");
   SwitchLoader(1);
	
   var Parameters = "c="+ClippingID;
   var dm = new DataManager();
	dm.RequestCompleteEvent = InsertClipping;
	dm.RequestFailedEvent = DoNothing;
	dm.LoadData(AjaxUrl+"?"+Parameters);
}


function InsertClipping(Request) {
	ChangeLoaderText("Complete");
	var CommentBox;
	var frmPostComment = document.getElementById("frmPostComment");
	var frmPostDiscussion = document.getElementById("frmPostDiscussion");
	if (frmPostComment) CommentBox = frmPostComment.Body;
	if (frmPostDiscussion) CommentBox = frmPostDiscussion.Body;
	if (CommentBox) CommentBox.value += Request.responseText;
	CloseLoader();
}


function ToggleClipboard() {
   var ClipboardButton = document.getElementById("ClipboardButton");
   var ClipboardContainer = document.getElementById("ClipboardContainer");
   if (ClipboardButton && ClipboardContainer) {
      if (ClipboardContainer.style.display == "block") {
         ClipboardContainer.style.display = "none";
         ClipboardButton.className = "ClipboardOff";
      } else {
         ClipboardContainer.style.display = "block";
         ClipboardButton.className = "ClipboardOn";         
         var WhisperButton = document.getElementById("WhisperButton");
         var WhisperContainer = document.getElementById("WhisperContainer");
         if (WhisperButton && WhisperContainer) {
            WhisperButton.className = "WhisperOff";
            WhisperContainer.style.display = "none";   
         }
      }
   }
}