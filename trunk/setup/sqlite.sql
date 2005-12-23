/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Shell SQLite database - for use with a fresh, manual install of Vanilla 1.0
*/

CREATE TABLE LUM_User (
  UserID integer primary key,
  RoleID integer NOT NULL default '0',
  StyleID integer NOT NULL default '1',
  CustomStyle varchar(255) default NULL,
  FirstName varchar(50) NOT NULL default '',
  LastName varchar(50) NOT NULL default '',
  Name varchar(20) NOT NULL default '',
  Password varchar(32) default NULL,
  VerificationKey varchar(50) NOT NULL default '',
  EmailVerificationKey varchar(50) default NULL,
  Email varchar(200) NOT NULL default '',
  UtilizeEmail integer NOT NULL default '0',
  ShowName integer NOT NULL default '1',
  Icon varchar(255) default NULL,
  Picture varchar(255) default NULL,
  Attributes text NOT NULL,
  CountVisit integer NOT NULL default '0',
  CountDiscussions integer NOT NULL default '0',
  CountComments integer NOT NULL default '0',
  DateFirstVisit varchar(19) NOT NULL default '0000-00-00 00:00:00',
  DateLastActive varchar(19) NOT NULL default '0000-00-00 00:00:00',
  RemoteIp varchar(100) NOT NULL default '',
  LastDiscussionPost varchar(19) default NULL,
  DiscussionSpamCheck integer NOT NULL default '0',
  LastCommentPost varchar(19) default NULL,
  CommentSpamCheck integer NOT NULL default '0',
  UseQuickKeys integer NOT NULL default '0',
  UserBlocksCategories integer NOT NULL default '0',
  DefaultFormatType varchar(20) default NULL,
  Discovery text,
  Preferences text,
  SendNewApplicantNotifications integer NOT NULL default '0',
  CountBlogs integer NOT NULL default '0'
);

create index user_role on LUM_User(RoleID);
create index user_style on LUM_User(StyleID);
create index user_name on LUM_User(Name);

-- Create Administrative user with username/password Admin/Admin
INSERT INTO LUM_User VALUES (1,6,1,'','Admin','User','Admin','Admin','','','admin@yourdomain.com','1','1','','','',0,0,0,'2005-12-22 00:00:00','2005-12-22 00:00:00','',null,1,null,1,'0','0','Text',NULL,'','1',0);

CREATE TABLE LUM_Role (
  RoleID integer primary key,
  Name varchar(100) NOT NULL default '',
  Icon varchar(155) NOT NULL default '',
  Description varchar(200) NOT NULL default '',
  Active integer NOT NULL default '1',
  PERMISSION_SIGN_IN integer NOT NULL default '0',
  PERMISSION_HTML_ALLOWED integer NOT NULL,
  PERMISSION_RECEIVE_APPLICATION_NOTIFICATION integer NOT NULL default '0',
  Permissions text NOT NULL,
  Priority integer NOT NULL default '0',
  UnAuthenticated integer NOT NULL default '0'
);

INSERT INTO LUM_Role VALUES (1,'Unauthenticated','','','1','0','0','0','a:32:{s:23:"PERMISSION_ADD_COMMENTS";N;s:27:"PERMISSION_START_DISCUSSION";N;s:28:"PERMISSION_STICK_DISCUSSIONS";N;s:27:"PERMISSION_HIDE_DISCUSSIONS";N;s:28:"PERMISSION_CLOSE_DISCUSSIONS";N;s:27:"PERMISSION_EDIT_DISCUSSIONS";N;s:34:"PERMISSION_VIEW_HIDDEN_DISCUSSIONS";N;s:24:"PERMISSION_EDIT_COMMENTS";N;s:24:"PERMISSION_HIDE_COMMENTS";N;s:31:"PERMISSION_VIEW_HIDDEN_COMMENTS";N;s:44:"PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION";N;s:25:"PERMISSION_ADD_CATEGORIES";N;s:26:"PERMISSION_EDIT_CATEGORIES";N;s:28:"PERMISSION_REMOVE_CATEGORIES";N;s:26:"PERMISSION_SORT_CATEGORIES";N;s:28:"PERMISSION_VIEW_ALL_WHISPERS";N;s:29:"PERMISSION_APPROVE_APPLICANTS";N;s:27:"PERMISSION_CHANGE_USER_ROLE";N;s:21:"PERMISSION_EDIT_USERS";N;s:31:"PERMISSION_IP_ADDRESSES_VISIBLE";N;s:30:"PERMISSION_MANAGE_REGISTRATION";N;s:21:"PERMISSION_SORT_ROLES";N;s:20:"PERMISSION_ADD_ROLES";N;s:21:"PERMISSION_EDIT_ROLES";N;s:23:"PERMISSION_REMOVE_ROLES";N;s:28:"PERMISSION_CHECK_FOR_UPDATES";N;s:38:"PERMISSION_CHANGE_APPLICATION_SETTINGS";N;s:28:"PERMISSION_MANAGE_EXTENSIONS";N;s:26:"PERMISSION_MANAGE_LANGUAGE";N;s:24:"PERMISSION_MANAGE_STYLES";N;s:27:"PERMISSION_ALLOW_DEBUG_INFO";N;s:27:"PERMISSION_DATABASE_CLEANUP";N;}',0,'1');
INSERT INTO LUM_Role VALUES (2,'Banned','','','1','0','0','0','a:32:{s:23:"PERMISSION_ADD_COMMENTS";N;s:27:"PERMISSION_START_DISCUSSION";N;s:28:"PERMISSION_STICK_DISCUSSIONS";N;s:27:"PERMISSION_HIDE_DISCUSSIONS";N;s:28:"PERMISSION_CLOSE_DISCUSSIONS";N;s:27:"PERMISSION_EDIT_DISCUSSIONS";N;s:34:"PERMISSION_VIEW_HIDDEN_DISCUSSIONS";N;s:24:"PERMISSION_EDIT_COMMENTS";N;s:24:"PERMISSION_HIDE_COMMENTS";N;s:31:"PERMISSION_VIEW_HIDDEN_COMMENTS";N;s:44:"PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION";N;s:25:"PERMISSION_ADD_CATEGORIES";N;s:26:"PERMISSION_EDIT_CATEGORIES";N;s:28:"PERMISSION_REMOVE_CATEGORIES";N;s:26:"PERMISSION_SORT_CATEGORIES";N;s:28:"PERMISSION_VIEW_ALL_WHISPERS";N;s:29:"PERMISSION_APPROVE_APPLICANTS";N;s:27:"PERMISSION_CHANGE_USER_ROLE";N;s:21:"PERMISSION_EDIT_USERS";N;s:31:"PERMISSION_IP_ADDRESSES_VISIBLE";N;s:30:"PERMISSION_MANAGE_REGISTRATION";N;s:21:"PERMISSION_SORT_ROLES";N;s:20:"PERMISSION_ADD_ROLES";N;s:21:"PERMISSION_EDIT_ROLES";N;s:23:"PERMISSION_REMOVE_ROLES";N;s:28:"PERMISSION_CHECK_FOR_UPDATES";N;s:38:"PERMISSION_CHANGE_APPLICATION_SETTINGS";N;s:28:"PERMISSION_MANAGE_EXTENSIONS";N;s:26:"PERMISSION_MANAGE_LANGUAGE";N;s:24:"PERMISSION_MANAGE_STYLES";N;s:27:"PERMISSION_ALLOW_DEBUG_INFO";N;s:27:"PERMISSION_DATABASE_CLEANUP";N;}',1,'0');
INSERT INTO LUM_Role VALUES (3,'Member','','','1','1','1','0','a:32:{s:23:"PERMISSION_ADD_COMMENTS";i:1;s:27:"PERMISSION_START_DISCUSSION";i:1;s:28:"PERMISSION_STICK_DISCUSSIONS";N;s:27:"PERMISSION_HIDE_DISCUSSIONS";N;s:28:"PERMISSION_CLOSE_DISCUSSIONS";N;s:27:"PERMISSION_EDIT_DISCUSSIONS";N;s:34:"PERMISSION_VIEW_HIDDEN_DISCUSSIONS";N;s:24:"PERMISSION_EDIT_COMMENTS";N;s:24:"PERMISSION_HIDE_COMMENTS";N;s:31:"PERMISSION_VIEW_HIDDEN_COMMENTS";N;s:44:"PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION";N;s:25:"PERMISSION_ADD_CATEGORIES";N;s:26:"PERMISSION_EDIT_CATEGORIES";N;s:28:"PERMISSION_REMOVE_CATEGORIES";N;s:26:"PERMISSION_SORT_CATEGORIES";N;s:28:"PERMISSION_VIEW_ALL_WHISPERS";N;s:29:"PERMISSION_APPROVE_APPLICANTS";N;s:27:"PERMISSION_CHANGE_USER_ROLE";N;s:21:"PERMISSION_EDIT_USERS";N;s:31:"PERMISSION_IP_ADDRESSES_VISIBLE";N;s:30:"PERMISSION_MANAGE_REGISTRATION";N;s:21:"PERMISSION_SORT_ROLES";N;s:20:"PERMISSION_ADD_ROLES";N;s:21:"PERMISSION_EDIT_ROLES";N;s:23:"PERMISSION_REMOVE_ROLES";N;s:28:"PERMISSION_CHECK_FOR_UPDATES";N;s:38:"PERMISSION_CHANGE_APPLICATION_SETTINGS";N;s:28:"PERMISSION_MANAGE_EXTENSIONS";N;s:26:"PERMISSION_MANAGE_LANGUAGE";N;s:24:"PERMISSION_MANAGE_STYLES";N;s:27:"PERMISSION_ALLOW_DEBUG_INFO";N;s:27:"PERMISSION_DATABASE_CLEANUP";N;}',2,'0');
INSERT INTO LUM_Role VALUES (4,'Moderator','','','1','1','1','0','a:32:{s:23:"PERMISSION_ADD_COMMENTS";i:1;s:27:"PERMISSION_START_DISCUSSION";i:1;s:28:"PERMISSION_STICK_DISCUSSIONS";i:1;s:27:"PERMISSION_HIDE_DISCUSSIONS";i:1;s:28:"PERMISSION_CLOSE_DISCUSSIONS";i:1;s:27:"PERMISSION_EDIT_DISCUSSIONS";i:1;s:34:"PERMISSION_VIEW_HIDDEN_DISCUSSIONS";i:1;s:24:"PERMISSION_EDIT_COMMENTS";i:1;s:24:"PERMISSION_HIDE_COMMENTS";i:1;s:31:"PERMISSION_VIEW_HIDDEN_COMMENTS";i:1;s:44:"PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION";i:1;s:25:"PERMISSION_ADD_CATEGORIES";N;s:26:"PERMISSION_EDIT_CATEGORIES";N;s:28:"PERMISSION_REMOVE_CATEGORIES";N;s:26:"PERMISSION_SORT_CATEGORIES";N;s:28:"PERMISSION_VIEW_ALL_WHISPERS";N;s:29:"PERMISSION_APPROVE_APPLICANTS";N;s:27:"PERMISSION_CHANGE_USER_ROLE";N;s:21:"PERMISSION_EDIT_USERS";N;s:31:"PERMISSION_IP_ADDRESSES_VISIBLE";N;s:30:"PERMISSION_MANAGE_REGISTRATION";N;s:21:"PERMISSION_SORT_ROLES";N;s:20:"PERMISSION_ADD_ROLES";N;s:21:"PERMISSION_EDIT_ROLES";N;s:23:"PERMISSION_REMOVE_ROLES";N;s:28:"PERMISSION_CHECK_FOR_UPDATES";N;s:38:"PERMISSION_CHANGE_APPLICATION_SETTINGS";N;s:28:"PERMISSION_MANAGE_EXTENSIONS";N;s:26:"PERMISSION_MANAGE_LANGUAGE";N;s:24:"PERMISSION_MANAGE_STYLES";N;s:27:"PERMISSION_ALLOW_DEBUG_INFO";N;s:27:"PERMISSION_DATABASE_CLEANUP";N;}',3,'0');
INSERT INTO LUM_Role VALUES (5,'User Administrator','','','1','1','1','1','a:32:{s:23:"PERMISSION_ADD_COMMENTS";i:1;s:27:"PERMISSION_START_DISCUSSION";i:1;s:28:"PERMISSION_STICK_DISCUSSIONS";N;s:27:"PERMISSION_HIDE_DISCUSSIONS";N;s:28:"PERMISSION_CLOSE_DISCUSSIONS";N;s:27:"PERMISSION_EDIT_DISCUSSIONS";N;s:34:"PERMISSION_VIEW_HIDDEN_DISCUSSIONS";N;s:24:"PERMISSION_EDIT_COMMENTS";N;s:24:"PERMISSION_HIDE_COMMENTS";N;s:31:"PERMISSION_VIEW_HIDDEN_COMMENTS";N;s:44:"PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION";N;s:25:"PERMISSION_ADD_CATEGORIES";N;s:26:"PERMISSION_EDIT_CATEGORIES";N;s:28:"PERMISSION_REMOVE_CATEGORIES";N;s:26:"PERMISSION_SORT_CATEGORIES";N;s:28:"PERMISSION_VIEW_ALL_WHISPERS";N;s:29:"PERMISSION_APPROVE_APPLICANTS";i:1;s:27:"PERMISSION_CHANGE_USER_ROLE";i:1;s:21:"PERMISSION_EDIT_USERS";i:1;s:31:"PERMISSION_IP_ADDRESSES_VISIBLE";i:1;s:30:"PERMISSION_MANAGE_REGISTRATION";i:1;s:21:"PERMISSION_SORT_ROLES";N;s:20:"PERMISSION_ADD_ROLES";N;s:21:"PERMISSION_EDIT_ROLES";N;s:23:"PERMISSION_REMOVE_ROLES";N;s:28:"PERMISSION_CHECK_FOR_UPDATES";N;s:38:"PERMISSION_CHANGE_APPLICATION_SETTINGS";N;s:28:"PERMISSION_MANAGE_EXTENSIONS";N;s:26:"PERMISSION_MANAGE_LANGUAGE";N;s:24:"PERMISSION_MANAGE_STYLES";N;s:27:"PERMISSION_ALLOW_DEBUG_INFO";N;s:27:"PERMISSION_DATABASE_CLEANUP";N;}',4,'0');
INSERT INTO LUM_Role VALUES (6,'Master Administrator','','','1','1','1','1','a:32:{s:23:"PERMISSION_ADD_COMMENTS";i:1;s:27:"PERMISSION_START_DISCUSSION";i:1;s:28:"PERMISSION_STICK_DISCUSSIONS";i:1;s:27:"PERMISSION_HIDE_DISCUSSIONS";i:1;s:28:"PERMISSION_CLOSE_DISCUSSIONS";i:1;s:27:"PERMISSION_EDIT_DISCUSSIONS";i:1;s:34:"PERMISSION_VIEW_HIDDEN_DISCUSSIONS";i:1;s:24:"PERMISSION_EDIT_COMMENTS";i:1;s:24:"PERMISSION_HIDE_COMMENTS";i:1;s:31:"PERMISSION_VIEW_HIDDEN_COMMENTS";i:1;s:44:"PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION";i:1;s:25:"PERMISSION_ADD_CATEGORIES";i:1;s:26:"PERMISSION_EDIT_CATEGORIES";i:1;s:28:"PERMISSION_REMOVE_CATEGORIES";i:1;s:26:"PERMISSION_SORT_CATEGORIES";i:1;s:28:"PERMISSION_VIEW_ALL_WHISPERS";i:1;s:29:"PERMISSION_APPROVE_APPLICANTS";i:1;s:27:"PERMISSION_CHANGE_USER_ROLE";i:1;s:21:"PERMISSION_EDIT_USERS";i:1;s:31:"PERMISSION_IP_ADDRESSES_VISIBLE";i:1;s:30:"PERMISSION_MANAGE_REGISTRATION";i:1;s:21:"PERMISSION_SORT_ROLES";i:1;s:20:"PERMISSION_ADD_ROLES";i:1;s:21:"PERMISSION_EDIT_ROLES";i:1;s:23:"PERMISSION_REMOVE_ROLES";i:1;s:28:"PERMISSION_CHECK_FOR_UPDATES";i:1;s:38:"PERMISSION_CHANGE_APPLICATION_SETTINGS";i:1;s:28:"PERMISSION_MANAGE_EXTENSIONS";i:1;s:26:"PERMISSION_MANAGE_LANGUAGE";i:1;s:24:"PERMISSION_MANAGE_STYLES";i:1;s:27:"PERMISSION_ALLOW_DEBUG_INFO";i:1;s:27:"PERMISSION_DATABASE_CLEANUP";i:1;}',5,'0');


CREATE TABLE LUM_Category (
  CategoryID integer primary key,
  Name varchar(100) NOT NULL default '',
  Description text NOT NULL,
  Priority integer NOT NULL default '0'
);


INSERT INTO LUM_Category VALUES (1,'General Discussions','Talk about anything ... within reason',0);



CREATE TABLE LUM_CategoryBlock (
  CategoryID integer NOT NULL default '0',
  UserID integer NOT NULL default '0',
  Blocked integer NOT NULL default '1'
);

create index cat_block_user on LUM_CategoryBlock(UserID);



CREATE TABLE LUM_CategoryRoleBlock (
  CategoryID integer NOT NULL default '0',
  RoleID integer NOT NULL default '0',
  Blocked integer NOT NULL default '0'
);


-- Block Banned Members from the one category
INSERT INTO LUM_CategoryRoleBlock VALUES (1,2,'1');


CREATE TABLE LUM_Clipping (
  ClippingID integer primary key,
  UserID integer NOT NULL default '0',
  Label varchar(30) NOT NULL default '',
  Contents text NOT NULL
);


CREATE TABLE LUM_Comment (
  CommentID integer primary key,
  DiscussionID integer NOT NULL default '0',
  AuthUserID integer NOT NULL default '0',
  DateCreated varchar(19) default NULL,
  EditUserID integer default NULL,
  DateEdited varchar(19) default NULL,
  WhisperUserID integer default NULL,
  Body text,
  FormatType varchar(20) default NULL,
  Deleted integer NOT NULL default '0',
  DateDeleted varchar(19) default NULL,
  DeleteUserID integer NOT NULL default '0',
  RemoteIp varchar(100) default ''
);
create index comment_user on LUM_Comment(AuthUserID);
create index comment_whisper on LUM_Comment(WhisperUserID);
create index comment_discussion on LUM_Comment(DiscussionID);


CREATE TABLE LUM_CommentBlock (
  BlockingUserID integer NOT NULL default '0',
  BlockedCommentID integer NOT NULL default '0',
  Blocked integer NOT NULL default '1'
);


CREATE TABLE LUM_Discussion (
  DiscussionID integer primary key,
  AuthUserID integer NOT NULL default '0',
  WhisperUserID integer NOT NULL default '0',
  FirstCommentID integer NOT NULL default '0',
  LastUserID integer NOT NULL default '0',
  Active integer NOT NULL default '1',
  Closed integer NOT NULL default '0',
  Sticky integer NOT NULL default '0',
  Name varchar(100) NOT NULL default '',
  DateCreated varchar(19) NOT NULL default '0000-00-00 00:00:00',
  DateLastActive varchar(19) NOT NULL default '0000-00-00 00:00:00',
  CountComments int(4) NOT NULL default '1',
  CategoryID integer default NULL,
  WhisperToLastUserID integer default NULL,
  WhisperFromLastUserID integer default NULL,
  DateLastWhisper varchar(19) default NULL,
  TotalWhisperCount integer NOT NULL default '0'
);
create index discussion_user on LUM_Discussion(AuthUserID);
create index discussion_whisperuser on LUM_Discussion(WhisperUserID);
create index discussion_first on LUM_Discussion(FirstCommentID);
create index discussion_last on LUM_Discussion(LastUserID);
create index discussion_category on LUM_Discussion(CategoryID);
create index discussion_dateactive on LUM_Discussion(DateLastActive);


CREATE TABLE LUM_DiscussionUserWhisperFrom (
  DiscussionID integer NOT NULL default '0',
  WhisperFromUserID integer NOT NULL default '0',
  LastUserID integer NOT NULL default '0',
  CountWhispers integer NOT NULL default '0',
  DateLastActive varchar(19) NOT NULL default '0000-00-00 00:00:00'
);
create index discussion_user_whisper_lastuser on LUM_DiscussionUserWhisperFrom(LastUserID);
create index discussion_user_whisper_lastactive on LUM_DiscussionUserWhisperFrom(DateLastActive);


CREATE TABLE LUM_DiscussionUserWhisperTo (
  DiscussionID integer NOT NULL default '0',
  WhisperToUserID integer NOT NULL default '0',
  LastUserID integer NOT NULL default '0',
  CountWhispers integer NOT NULL default '0',
  DateLastActive varchar(19) NOT NULL default '0000-00-00 00:00:00'
);
create index discussion_user_whisperto_lastuser on LUM_DiscussionUserWhisperTo(LastUserID);
create index discussion_user_whisperto_lastactive on LUM_DiscussionUserWhisperTo(DateLastActive);



CREATE TABLE LUM_IpHistory (
  IpHistoryID integer primary key,
  RemoteIp varchar(30) NOT NULL default '',
  UserID integer NOT NULL default '0',
  DateLogged varchar(19) NOT NULL default '0000-00-00 00:00:00'
);


CREATE TABLE LUM_Style (
  StyleID integer primary key,
  AuthUserID integer NOT NULL default '0',
  Name varchar(50) NOT NULL default '',
  Url varchar(255) NOT NULL default '',
  PreviewImage varchar(20) NOT NULL default ''
);

INSERT INTO lum_style VALUES (1,1,'Vanilla','./themes/vanilla/styles/default/','preview.gif');


CREATE TABLE LUM_UserBlock (
  BlockingUserID integer NOT NULL default '0',
  BlockedUserID integer NOT NULL default '0',
  Blocked integer NOT NULL default '1'
);


CREATE TABLE LUM_UserBookmark (
  UserID integer NOT NULL default '0',
  DiscussionID integer NOT NULL default '0'
);


CREATE TABLE LUM_UserDiscussionWatch (
  UserID integer NOT NULL default '0',
  DiscussionID integer NOT NULL default '0',
  CountComments integer NOT NULL default '0',
  LastViewed varchar(19) NOT NULL default '0000-00-00 00:00:00'
);

CREATE TABLE LUM_UserRoleHistory (
  UserID integer NOT NULL default '0',
  RoleID integer NOT NULL default '0',
  Date varchar(19) NOT NULL default '0000-00-00 00:00:00',
  AdminUserID integer NOT NULL default '0',
  Notes varchar(200) default NULL,
  RemoteIp varchar(100) default NULL
);

INSERT INTO LUM_UserRoleHistory VALUES (1,6,'2005-12-22 00:00:00',0,'Administrative account created during installation process.','');


CREATE TABLE LUM_UserSearch (
  SearchID integer primary key,
  Label varchar(30) NOT NULL default '',
  UserID integer NOT NULL default '0',
  Keywords varchar(100) NOT NULL default '',
  Type varchar NOT NULL default 'Topics'
);