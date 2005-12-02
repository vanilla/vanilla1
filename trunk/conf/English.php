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
?>