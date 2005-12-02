CREATE TABLE `LUM_Category` (
  `CategoryID` int(2) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `Description` text NOT NULL,
  `Order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`CategoryID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_CategoryBlock` (
  `CategoryID` int(11) NOT NULL default '0',
  `UserID` int(11) NOT NULL default '0',
  `Blocked` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`CategoryID`,`UserID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_CategoryRoleBlock` (
  `CategoryID` int(11) NOT NULL default '0',
  `RoleID` int(11) NOT NULL default '0',
  `Blocked` enum('1','0') NOT NULL default '0'
) TYPE=MyISAM;

CREATE TABLE `LUM_Clipping` (
  `ClippingID` int(11) NOT NULL auto_increment,
  `UserID` int(11) NOT NULL default '0',
  `Label` varchar(30) NOT NULL default '',
  `Contents` text NOT NULL,
  PRIMARY KEY  (`ClippingID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_Comment` (
  `CommentID` int(8) NOT NULL auto_increment,
  `DiscussionID` int(8) NOT NULL default '0',
  `AuthUserID` int(10) NOT NULL default '0',
  `DateCreated` datetime default NULL,
  `EditUserID` int(10) default NULL,
  `DateEdited` datetime default NULL,
  `WhisperUserID` int(11) default NULL,
  `Body` text,
  `FormatType` varchar(20) default NULL,
  `Deleted` enum('1','0') NOT NULL default '0',
  `DateDeleted` datetime default NULL,
  `DeleteUserID` int(10) NOT NULL default '0',
  `RemoteIp` varchar(100) default '',
  PRIMARY KEY  (`CommentID`,`DiscussionID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_CommentBlock` (
  `BlockingUserID` int(11) NOT NULL default '0',
  `BlockedCommentID` int(11) NOT NULL default '0',
  `Blocked` enum('1','0') NOT NULL default '1'
) TYPE=MyISAM;

CREATE TABLE `LUM_Discussion` (
  `DiscussionID` int(8) NOT NULL auto_increment,
  `AuthUserID` int(10) NOT NULL default '0',
  `WhisperUserID` int(11) NOT NULL default '0',
  `FirstCommentID` int(11) NOT NULL default '0',
  `LastUserID` int(11) NOT NULL default '0',
  `Active` enum('1','0') NOT NULL default '1',
  `Closed` enum('1','0') NOT NULL default '0',
  `Sticky` enum('1','0') NOT NULL default '0',
  `Name` varchar(100) NOT NULL default '',
  `DateCreated` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastActive` datetime NOT NULL default '0000-00-00 00:00:00',
  `CountComments` int(4) NOT NULL default '1',
  `CategoryID` int(11) default NULL,
  `WhisperToLastUserID` int(11) default NULL,
  `WhisperFromLastUserID` int(11) default NULL,
  `DateLastWhisper` datetime default NULL,
  `TotalWhisperCount` int(11) NOT NULL default '0',
  PRIMARY KEY  (`DiscussionID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_DiscussionUserWhisperFrom` (
  `DiscussionID` int(11) NOT NULL default '0',
  `WhisperFromUserID` int(11) NOT NULL default '0',
  `LastUserID` int(11) NOT NULL default '0',
  `CountWhispers` int(11) NOT NULL default '0',
  `DateLastActive` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`DiscussionID`,`WhisperFromUserID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_DiscussionUserWhisperTo` (
  `DiscussionID` int(11) NOT NULL default '0',
  `WhisperToUserID` int(11) NOT NULL default '0',
  `LastUserID` int(11) NOT NULL default '0',
  `CountWhispers` int(11) NOT NULL default '0',
  `DateLastActive` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`DiscussionID`,`WhisperToUserID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_IpHistory` (
  `IpHistoryID` int(11) NOT NULL auto_increment,
  `RemoteIp` varchar(30) NOT NULL default '',
  `UserID` int(11) NOT NULL default '0',
  `DateLogged` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`IpHistoryID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_Role` (
  `RoleID` int(2) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `Icon` varchar(155) NOT NULL default '',
  `Description` varchar(200) NOT NULL default '',
  `CanLogin` enum('1','0') NOT NULL default '1',
  `CanPostDiscussion` enum('1','0') NOT NULL default '1',
  `CanPostComment` enum('1','0') NOT NULL default '1',
  `CanPostHTML` enum('1','0') NOT NULL default '1',
  `AdminUsers` enum('1','0') NOT NULL default '0',
  `AdminCategories` enum('1','0') NOT NULL default '0',
  `MasterAdmin` enum('1','0') NOT NULL default '0',
  `ShowAllWhispers` enum('1','0') NOT NULL default '0',
  `CanViewIps` enum('1','0') NOT NULL default '0',
  `Active` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`RoleID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_Style` (
  `StyleID` int(3) NOT NULL auto_increment,
  `AuthUserID` int(11) NOT NULL default '0',
  `Name` varchar(50) NOT NULL default '',
  `Url` varchar(255) NOT NULL default '',
  `PreviewImage` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`StyleID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_User` (
  `UserID` int(10) NOT NULL auto_increment,
  `RoleID` int(2) NOT NULL default '0',
  `StyleID` int(3) NOT NULL default '1',
  `CustomStyle` varchar(255) default NULL,
  `FirstName` varchar(50) NOT NULL default '',
  `LastName` varchar(50) NOT NULL default '',
  `Name` varchar(20) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  `VerificationKey` varchar(50) NOT NULL default '',
  `EmailVerificationKey` varchar(50) default NULL,
  `Email` varchar(200) NOT NULL default '',
  `UtilizeEmail` enum('1','0') NOT NULL default '0',
  `ShowName` enum('1','0') NOT NULL default '1',
  `Icon` varchar(255) default NULL,
  `Picture` varchar(255) default NULL,
  `Attributes` text NOT NULL,
  `CountVisit` int(8) NOT NULL default '0',
  `CountDiscussions` int(8) NOT NULL default '0',
  `CountComments` int(8) NOT NULL default '0',
  `DateFirstVisit` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastActive` datetime NOT NULL default '0000-00-00 00:00:00',
  `RemoteIp` varchar(100) NOT NULL default '',
  `LastDiscussionPost` datetime default NULL,
  `DiscussionSpamCheck` int(11) NOT NULL default '0',
  `LastCommentPost` datetime default NULL,
  `CommentSpamCheck` int(11) NOT NULL default '0',
  `UserBlocksCategories` enum('1','0') NOT NULL default '0',
  `DefaultFormatType` varchar(20) default NULL,
  `SendNewApplicantNotifications` enum('1','0') not null default '0',
  `Discovery` text,
  `Settings` text,
  PRIMARY KEY  (`UserID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_UserBlock` (
  `BlockingUserID` int(11) NOT NULL default '0',
  `BlockedUserID` int(11) NOT NULL default '0',
  `Blocked` enum('1','0') NOT NULL default '1'
) TYPE=MyISAM;

CREATE TABLE `LUM_UserBookmark` (
  `UserID` int(10) NOT NULL default '0',
  `DiscussionID` int(8) NOT NULL default '0',
  PRIMARY KEY  (`UserID`,`DiscussionID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_UserDiscussionWatch` (
  `UserID` int(10) NOT NULL default '0',
  `DiscussionID` int(8) NOT NULL default '0',
  `CountComments` int(11) NOT NULL default '0',
  `LastViewed` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`UserID`,`DiscussionID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_UserRoleHistory` (
  `UserID` int(10) NOT NULL default '0',
  `RoleID` int(2) NOT NULL default '0',
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `AdminUserID` int(10) NOT NULL default '0',
  `Notes` varchar(200) default NULL,
  `RemoteIp` varchar(100) default NULL,
  KEY `UserID` (`UserID`)
) TYPE=MyISAM;

CREATE TABLE `LUM_UserSearch` (
  `SearchID` int(11) NOT NULL auto_increment,
  `Label` varchar(30) NOT NULL default '',
  `UserID` int(11) NOT NULL default '0',
  `Keywords` varchar(100) NOT NULL default '',
  `Type` enum('Users','Topics','Comments') NOT NULL default 'Topics',
  PRIMARY KEY  (`SearchID`)
) TYPE=MyISAM;

INSERT INTO LUM_Role (`Name`,`Icon`,`Description`,`CanLogin`,`CanPostDiscussion`,`CanPostComment`,`CanPostHTML`,`AdminUsers`,`AdminCategories`,`ShowAllWhispers`,`Active`,`MasterAdmin`)
VALUES ('Banned', '', 'I have been banned', '0', '0', '0', '0', '0', '0', '0', '1', '0');

INSERT INTO LUM_Role (`Name`,`Icon`,`Description`,`CanLogin`,`CanPostDiscussion`,`CanPostComment`,`CanPostHTML`,`AdminUsers`,`AdminCategories`,`ShowAllWhispers`,`Active`,`MasterAdmin`)
VALUES ('Douchebag', './images/db.gif', 'I am a complete and utter douchebag', '1', '1', '1', '0', '0', '0', '0', '1', '0');

INSERT INTO LUM_Role (`Name`,`Icon`,`Description`,`CanLogin`,`CanPostDiscussion`,`CanPostComment`,`CanPostHTML`,`AdminUsers`,`AdminCategories`,`ShowAllWhispers`,`Active`,`MasterAdmin`)
VALUES ('Member', '', '', '1', '1', '1', '1', '0', '0', '0', '1', '0');

INSERT INTO LUM_Role (`Name`,`Icon`,`Description`,`CanLogin`,`CanPostDiscussion`,`CanPostComment`,`CanPostHTML`,`AdminUsers`,`AdminCategories`,`ShowAllWhispers`,`Active`,`MasterAdmin`)
VALUES ('Moderator', '', '', '1', '1', '1', '1', '0', '1', '0', '1', '0');

INSERT INTO LUM_Role (`Name`,`Icon`,`Description`,`CanLogin`,`CanPostDiscussion`,`CanPostComment`,`CanPostHTML`,`AdminUsers`,`AdminCategories`,`ShowAllWhispers`,`Active`,`MasterAdmin`)
VALUES ('User Administrator', '', '', '1', '1', '1', '1', '1', '0', '0', '1', '0');

INSERT INTO LUM_Role (`Name`,`Icon`,`Description`,`CanLogin`,`CanPostDiscussion`,`CanPostComment`,`CanPostHTML`,`AdminUsers`,`AdminCategories`,`ShowAllWhispers`,`Active`,`MasterAdmin`)
VALUES ('Master Administrator', '', '', '1', '1', '1', '1', '1', '1', '0', '1', '1');

INSERT INTO LUM_Category (`Name`,`Description`)
VALUES ('General', 'A place for discussions about anything');

INSERT INTO LUM_Style (`AuthUserID`,`Name`,`Url`,`PreviewImage`)
VALUES (0,'Vanilla','styles/vanilla/','preview.gif');
