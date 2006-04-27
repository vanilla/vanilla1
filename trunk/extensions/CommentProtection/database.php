<?php
// Tables
$Context->DatabaseTables['CommentBlock'] = 'CommentBlock';
$Context->DatabaseTables['UserBlock'] = 'UserBlock';

// Columns
// CommentBlock Table
$Context->DatabaseColumns['CommentBlock']['BlockingUserID'] = 'BlockingUserID';
$Context->DatabaseColumns['CommentBlock']['BlockedCommentID'] = 'BlockedCommentID';
$Context->DatabaseColumns['CommentBlock']['Blocked'] = 'Blocked';
// UserBlock Table
$Context->DatabaseColumns['UserBlock']['BlockingUserID'] = 'BlockingUserID';
$Context->DatabaseColumns['UserBlock']['BlockedUserID'] = 'BlockedUserID';
$Context->DatabaseColumns['UserBlock']['Blocked'] = 'Blocked';
?>