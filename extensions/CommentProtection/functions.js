function BlockComment(CommentID, CurrentStatus, PermanentBlock, ForceStatus, UnblockInnerHtml, UnblockTitle, BlockInnerHtml, BlockTitle) {
	var Anchor = document.getElementById("BlockComment_"+CommentID);
	var Comment = document.getElementById("CommentContents_"+CommentID);
	if (Anchor && Comment) {
		var HtmlStatus = 0;
		if (ForceStatus) {
			HtmlStatus = ForceStatus;
		} else if (typeof(Comment.name) == "undefined") {
			HtmlStatus = CurrentStatus;
		} else {
			HtmlStatus = Comment.name;
		}
		if (HtmlStatus == "1") {
			EncodeElement(Comment);
			Comment.name = 0;
			Anchor.innerHTML = UnblockInnerHtml;
			Anchor.title = UnblockTitle;
		} else {
			DecodeElement(Comment);
			Comment.name = 1;
			Anchor.innerHTML = BlockInnerHtml;
			Anchor.title = BlockTitle;
		}
	}
	// Save the setting for this user
	if (PermanentBlock) SaveCommentBlock(CommentID, HtmlStatus);
}

function BlockUser (AuthUserID, CurrentStatus, UnblockInnerHtml, UnblockTitle, BlockInnerHtml, BlockTitle, UnblockCommentInnerHtml, UnblockCommentTitle, BlockCommentInnerHtml, BlockCommentTitle) {
	// Retrieve & Loop through all relevant elements
	var Comments = GetElements("div", "CommentContents_");
	var CommentID = 0;
	var HtmlStatus = -1;
	for(i = 0; i < Comments.length; i++) {
		CommentID = Comments[i].id.replace("CommentContents_","");
		// See if the comment belongs to this user
		var Anchor = document.getElementById("BlockUser_"+AuthUserID+"_Comment_"+CommentID);
		if (Anchor) {
			// If so, block the comment
			if (HtmlStatus == -1) HtmlStatus = (Anchor.name == "")?CurrentStatus:Anchor.name;
			BlockComment(CommentID, CurrentStatus, 0, HtmlStatus, UnblockCommentInnerHtml, UnblockCommentTitle, BlockCommentInnerHtml, BlockCommentTitle);
			// And flip the switch
			if (HtmlStatus == "1") {
				Anchor.name = 0;
				Anchor.innerHTML = UnblockInnerHtml;
				Anchor.title = UnblockTitle;
			} else {
				Anchor.name = 1;
				Anchor.innerHTML = BlockInnerHtml;
				Anchor.title = BlockTitle;
			}
		}
	}
	// Save the setting for this user
	SaveUserBlock(AuthUserID, HtmlStatus);
}

function BlockSaved(Request) {
	ChangeLoaderText("Complete");
	CloseLoader();
}

function DecodeElement(Element) {
	var String = Element.innerHTML;
	var regex_lt = new RegExp("&lt;", "gi");
	var regex_gt = new RegExp("&gt;","gi");
	String = String.replace(regex_lt,"<");
	String = String.replace(regex_gt,">");
	Element.innerHTML = String;
}

function EncodeElement(Element) {
	var String = Element.innerHTML;
	var regex_br1 = new RegExp("<br>", "gi");
	var regex_br2 = new RegExp("::br::", "gi");
	var regex_lt = new RegExp("<", "gi");
	var regex_gt = new RegExp(">","gi");
	String = String.replace(regex_br1,"::br::");
	String = String.replace(regex_lt,"&lt;");
	String = String.replace(regex_gt,"&gt;");
	String = String.replace(regex_br2,"<br />");
	Element.innerHTML = String;
}

// Block a comment's html from view
function SaveCommentBlock(BlockCommentID, BlockComment) {
	ChangeLoaderText("Processing...");
	SwitchLoader(1);
   var Url = "./extensions/CommentProtection/block.php";
   var Parameters = "BlockCommentID="+BlockCommentID+"&Block="+BlockComment;
   var DataManager = new Ajax.Request(
      Url,
      { method: 'get', parameters: Parameters, onComplete: BlockSaved }
   );
}

// Block a user's html from view
function SaveUserBlock(BlockUserID, BlockUser) {
	ChangeLoaderText("Processing...");
	SwitchLoader(1);
   var Url = "./extensions/CommentProtection/block.php";
   var Parameters = "BlockUserID="+BlockUserID+"&Block="+BlockUser;
   var DataManager = new Ajax.Request(
      Url,
      { method: 'get', parameters: Parameters, onComplete: BlockSaved }
   );
}