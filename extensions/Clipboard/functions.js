
// Insert clipboard data
function GetClipping(ClippingSelect) {
	var ClippingID = ClippingSelect.options[ClippingSelect.selectedIndex].value;
	ClippingSelect.selectedIndex = 0;
   ChangeLoaderText("Loading...");
   SwitchLoader(1);
   var Url = "./extensions/Clipboard/getclipping.php";
   var Parameters = "c="+ClippingID;
   var DataManager = new Ajax.Request(
      Url,
      { method: 'get', parameters: Parameters, onComplete: InsertClipping }
   );	
}


function InsertClipping(Request) {
	ChangeLoaderText("Complete");
	var CommentBox;
	if (document.frmPostComment) CommentBox = document.frmPostComment.Body;
	if (document.frmPostDiscussion) CommentBox = document.frmPostDiscussion.Body;
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