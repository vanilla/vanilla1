function SetBookmarkList(Identifier) {
   var Sender = document.getElementById('SetBookmark');   
   var BookmarkTitle = document.getElementById("BookmarkTitle");
	var BookmarkList = document.getElementById("BookmarkList");
	var Bookmark = document.getElementById("Bookmark_"+Identifier);
	var BookmarkForm = document.getElementById("frmBookmark");
	var OtherBookmarksExist = BookmarkForm.OtherBookmarksExist;
	if (Sender && BookmarkList) {
      if (Bookmark) {
         if (Sender.name == 0) {
            // removed bookmark
            Bookmark.style.display = "none";
            if (OtherBookmarksExist) {
               var Display = OtherBookmarksExist.value == 0 ? "none" : "block" ;
               if (BookmarkTitle) BookmarkTitle.style.display = Display;
               if (BookmarkList) BookmarkList.style.display = Display;
            }
         } else {
            Bookmark.style.display = "block";
            if (BookmarkTitle) BookmarkTitle.style.display = "block";
            if (BookmarkList) BookmarkList.style.display = "block";
         }
      }
	}
}