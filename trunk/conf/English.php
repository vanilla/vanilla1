<?php
/*
 Some extensions will have custom language definitions.
 You should enter any custom language definitions for an extension here.
*/

// Cleanup Extension
$Context->Dictionary["PERMISSION_DATABASE_CLEANUP"] = "Database Cleanup Permission";
$Context->Dictionary["XHiddenDiscussions"] = "There are currently \\1 hidden discussions.";
$Context->Dictionary["XHiddenComments"] = "There are currently \\1 hidden comments.";
$Context->Dictionary["SystemCleanup"] = "System Cleanup";
$Context->Dictionary["BackupDatabase"] = "Backup Database";
$Context->Dictionary["BackupDatabaseNotes"] = "If you find that this feature creates a blank file, you will need to fully define the path to mysqldump on your server. You can define this value on line 21 of the extensions/Cleanup.php file. Also be sure that the user you have specified to connect to the database has access to execute mysqldump.";
$Context->Dictionary["ClickHereToBackupDatabase"] = "Click here to create a database backup";
$Context->Dictionary["RemoveUsersConfirm"] = "Are you sure you wish to remove these users?\\nThis action cannot be undone!";
$Context->Dictionary["CleanupUsers"] = "Cleanup Users";
$Context->Dictionary["RemoveUsersMessage"] = "There are currently \\1 members who have never posted a comment. Remove non-participating members that have been on the forum for more than \\2 days: ";
$Context->Dictionary["Go"] = "Go";
$Context->Dictionary["CleanupDiscussions"] = "Cleanup Discussions";
$Context->Dictionary["CleanupComments"] = "Cleanup Comments";
$Context->Dictionary["CommentsRemovedSuccessfully"] = "All hidden comments were successfully deleted.";
$Context->Dictionary["DiscussionsRemovedSuccessfully"] = "All hidden discussions were successfully deleted.";
$Context->Dictionary["PurgeDiscussions"] = "Purge Discussions";
$Context->Dictionary["DiscussionsPurgedSuccessfully"] = "All discussions have been removed from the database.";
$Context->Dictionary["XHiddenDiscussions"] = "There are currently \\1 hidden discussions: ";
$Context->Dictionary["XHiddenComments"] = "There are currently \\1 hidden comments: ";
$Context->Dictionary["ClickHereToRemoveAllHiddenDiscussions"] = "Remove";
$Context->Dictionary["RemoveDiscussionsConfirm"] = "Are you sure you wish to delete all hidden discussions from the database?\\nThis action cannot be undone!";
$Context->Dictionary["ClickHereToRemoveAllHiddenComments"] = "Remove";
$Context->Dictionary["RemoveCommentsConfirm"] = "Are you sure you wish to delete all hidden comments from the database?\\nThis action cannot be undone!";
$Context->Dictionary["ClickHereToPurgeAllDiscussions"] = "Click here to completely purge all discussions and comments from the database";
$Context->Dictionary["PurgeDiscussionsConfirm"] = "Are you sure you wish to completely DELETE ALL DISCUSSIONS from the database?\\nThis action cannot be undone!!";
$Context->Dictionary["UsersRemovedSuccessfully"] = "\\1 members were removed.";
$Context->Dictionary["MasterAdministrator"] = "Administrative privileges for all other features";

// CommentProtection Extension
$Context->Dictionary["AllowHtml"] = "Allow HTML in this comment";
$Context->Dictionary["BlockHtml"] = "Block HTML in this comment";
$Context->Dictionary["BlockComment"] = "block comment";
$Context->Dictionary["BlockCommentTitle"] = "Block HTML in this comment";
$Context->Dictionary["UnblockComment"] = "unblock comment";
$Context->Dictionary["UnblockCommentTitle"] = "Allow HTML in this comment";
$Context->Dictionary["BlockUserHtml"] = "Block HTML in all comments by this user on the forum";
$Context->Dictionary["AllowUserHtml"] = "Allow HTML in all comments by this user on the forum";
$Context->Dictionary["BlockUser"] = "block user";
$Context->Dictionary["BlockUserTitle"] = "Block HTML in all comments by this user on the forum";
$Context->Dictionary["UnblockUser"] = "unblock user";
$Context->Dictionary["UnblockUserTitle"] = "Allow HTML in all comments by this user on the forum";

// Atom Extension
$Context->Dictionary["AtomFeed"] = "Atom";
$Context->Dictionary["FailedFeedAuthenticationTitle"] = "Failed Authentication";
$Context->Dictionary["FailedFeedAuthenticationText"] = "Feeds for this forum require user authentication.";

// RSS2 Extension
$Context->Dictionary["RSS2Feed"] = "RSS2";

// Style Extension
$Context->Dictionary["SelectStyleToEdit"] = "1. Select the style you would like to edit";
$Context->Dictionary["ModifyStyleDefinition"] = "2. Modify the style definition";
$Context->Dictionary["DefineTheNewStyle"] = "Define the new style";
$Context->Dictionary["StyleName"] = "Style name";
$Context->Dictionary["StyleNameNotes"] = "The style name will be visible on the user's account modification page. Html is not allowed.";
$Context->Dictionary["StyleAuthor"] = "Style author";
$Context->Dictionary["StyleAuthorNotes"] = "The name of the author of this style. Enter the name exactly as it appears on the user's account.";
$Context->Dictionary["StyleUrl"] = "Style url";
$Context->Dictionary["StyleUrlNotes"] = "You can enter any valid URL to a web-based directory here, such as: <strong>http://www.mywebsite.com/mynewstyle/</strong>
	<br />The folder must contain all of the files relevant to styling the forum, such as: global.css";
$Context->Dictionary["PreviewImageFilename"] = "Preview image filename";
$Context->Dictionary["PreviewImageFilenameNotes"] = "If there is a preview image in the style folder, enter the image name here. Preview images are automatically sized to 200 pixels high by 370 pixels wide.";
$Context->Dictionary["StyleManagement"] = "Style Management";
$Context->Dictionary["SelectStyleToRemove"] = "1. Select the style you would like to remove";
$Context->Dictionary["SelectAReplacementStyle"] = "2. Select a replacement style";
$Context->Dictionary["ReplacementStyleNotes"] = "When you remove a style from the system, any users using that style will not be able to view the site properly. The replacement style will be assigned to all users who are currently assigned to the style you are removing.";
$Context->Dictionary["CreateANewStyle"] = "Create a new style";
$Context->Dictionary["ChangeYourStylesheet"] = "Change Stylesheet";
$Context->Dictionary["ForumAppearanceNotes"] = "Change the way the forum appears by changing your style. Listed below are available styles. Alternately, you can specify your own style using the input at the bottom of the page.";
$Context->Dictionary["NoPreview"] = "No preview available";
$Context->Dictionary["CustomStyle"] = "Use your own, custom style";
$Context->Dictionary["CustomStyleUrl"] = "Custom style url";
$Context->Dictionary["CustomStyleNotes"] = "Any web-accessable folder will work, such as: http://www.mysite.com/mystyle/
	<p>Your custom style folder should contain all files relevant to your style, including a global.css file.</p>
	<p>For more information about how to style the forum, <a href=\"http://lussumo.com/docs\">read the documentation</a>.</p>";
$Context->Dictionary["UseCustomStyle"] = "Click here to use your custom style";
$Context->Dictionary["By"] = "by";
$Context->Dictionary["Styles"] = "Styles";
?>