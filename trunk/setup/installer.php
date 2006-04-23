<?php
// REPORT ALL ERRORS
error_reporting(E_ALL);
// DO NOT ALLOW PHP_SESS_ID TO BE PASSED IN THE QUERYSTRING
ini_set('session.use_only_cookies', 1);
// Track errors so explicit error messages can be reported should errors be encountered
ini_set('track_errors', 1);

// INCLUDE NECESSARY CLASSES & FUNCTIONS
include('../library/Framework/Framework.Functions.php');
include('../library/Framework/Framework.Class.Select.php');
include('../library/Framework/Framework.Class.SqlBuilder.php');
include('../library/Framework/Framework.Class.MessageCollector.php');
include('../library/Framework/Framework.Class.ErrorManager.php');
include('../library/Framework/Framework.Class.ConfigurationManager.php');

// Define the new table structure
// Table References:
// Note that the User table does not implicitly use the TablePrefix that
// is added to all other table names by the SqlBuilder object.
$DatabaseTables['LUM_Category'] = 'LUM_Category';
$DatabaseTables['LUM_CategoryBlock'] = 'LUM_CategoryBlock';
$DatabaseTables['LUM_CategoryRoleBlock'] = 'LUM_CategoryRoleBlock';
$DatabaseTables['LUM_Comment'] = 'LUM_Comment';
$DatabaseTables['LUM_Discussion'] = 'LUM_Discussion';
$DatabaseTables['LUM_DiscussionUserWhisperFrom'] = 'LUM_DiscussionUserWhisperFrom';
$DatabaseTables['LUM_DiscussionUserWhisperTo'] = 'LUM_DiscussionUserWhisperTo';
$DatabaseTables['LUM_IpHistory'] = 'LUM_IpHistory';
$DatabaseTables['LUM_Role'] = 'LUM_Role';
$DatabaseTables['LUM_Style'] = 'LUM_Style';
$DatabaseTables['LUM_User'] = 'LUM_User';
$DatabaseTables['LUM_UserBookmark'] = 'LUM_UserBookmark';
$DatabaseTables['LUM_UserDiscussionWatch'] = 'LUM_UserDiscussionWatch';
$DatabaseTables['LUM_UserRoleHistory'] = 'LUM_UserRoleHistory';

// Column References:
// The arrays represent: FieldName, DataType, Null, DefaultValue, IsPrimaryKey
// LUM_Category Table
$DatabaseColumns['LUM_Category']['CategoryID'] = array('CategoryID', 'int(2)', 'NO', '', '1');
$DatabaseColumns['LUM_Category']['Name'] = array('Name', 'varchar(100)', 'NO', '', '0');
$DatabaseColumns['LUM_Category']['Description'] = array('Description', 'text', 'NO', '', '0');
$DatabaseColumns['LUM_Category']['Priority'] = array('Priority', 'int(8)', 'NO', '0', '0');
// LUM_CategoryBlock Table
$DatabaseColumns['LUM_CategoryBlock']['CategoryID'] = array('CategoryID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_CategoryBlock']['UserID'] = array('UserID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_CategoryBlock']['Blocked'] = array('Blocked', "enum('1','0')", 'NO', '1', '0');
// LUM_CategoryRoleBlock Table
$DatabaseColumns['LUM_CategoryRoleBlock']['CategoryID'] = array('CategoryID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_CategoryRoleBlock']['RoleID'] = array('RoleID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_CategoryRoleBlock']['Blocked'] = array('Blocked', "enum('1','0')", 'NO', '0', '0');
// LUM_Comment Table
$DatabaseColumns['LUM_Comment']['CommentID'] = array('CommentID', 'int(8)', 'NO', '', '1');
$DatabaseColumns['LUM_Comment']['DiscussionID'] = array('DiscussionID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_Comment']['AuthUserID'] = array('AuthUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Comment']['DateCreated'] = array('DateCreated', 'datetime', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['EditUserID'] = array('EditUserID', 'int(8)', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['DateEdited'] = array('DateEdited', 'datetime', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['WhisperUserID'] = array('WhisperUserID', 'int(8)', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['Body'] = array('Body', 'text', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['FormatType'] = array('FormatType', 'varchar(20)', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['Deleted'] = array('Deleted', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_Comment']['DateDeleted'] = array('DateDeleted', 'datetime', 'YES', '', '0');
$DatabaseColumns['LUM_Comment']['DeleteUserID'] = array('DeleteUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Comment']['RemoteIp'] = array('RemoteIp', 'varchar(100)', 'YES', '', '0');
// LUM_Discussion Table
$DatabaseColumns['LUM_Discussion']['DiscussionID'] = array('DiscussionID', 'int(8)', 'NO', '', '1');
$DatabaseColumns['LUM_Discussion']['AuthUserID'] = array('AuthUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['WhisperUserID'] = array('WhisperUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['FirstCommentID'] = array('FirstCommentID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['LastUserID'] = array('LastUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['Active'] = array('Active', "enum('1','0')", 'NO', '1', '0');
$DatabaseColumns['LUM_Discussion']['Closed'] = array('Closed', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['Sticky'] = array('Sticky', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['Name'] = array('Name', 'varchar(100)', 'NO', '', '0');
$DatabaseColumns['LUM_Discussion']['DateCreated'] = array('DateCreated', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
$DatabaseColumns['LUM_Discussion']['DateLastActive'] = array('DateLastActive', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
$DatabaseColumns['LUM_Discussion']['CountComments'] = array('CountComments', 'int(4)', 'NO', '1', '0');
$DatabaseColumns['LUM_Discussion']['CategoryID'] = array('CategoryID', 'int(8)', 'YES', '', '0');
$DatabaseColumns['LUM_Discussion']['WhisperToLastUserID'] = array('WhisperToLastUserID', 'int(8)', 'YES', '', '0');
$DatabaseColumns['LUM_Discussion']['WhisperFromLastUserID'] = array('WhisperFromLastUserID', 'int(8)', 'YES', '', '0');
$DatabaseColumns['LUM_Discussion']['DateLastWhisper'] = array('DateLastWhisper', 'datetime', 'YES', '', '0');
$DatabaseColumns['LUM_Discussion']['TotalWhisperCount'] = array('TotalWhisperCount', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Discussion']['Sink'] = array('Sink', "enum('1','0')", 'NO', '0', '0');
// LUM_DiscussionUserWhisperFrom Table
$DatabaseColumns['LUM_DiscussionUserWhisperFrom']['DiscussionID'] = array('DiscussionID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_DiscussionUserWhisperFrom']['WhisperFromUserID'] = array('WhisperFromUserID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_DiscussionUserWhisperFrom']['LastUserID'] = array('LastUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_DiscussionUserWhisperFrom']['CountWhispers'] = array('CountWhispers', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_DiscussionUserWhisperFrom']['DateLastActive'] = array('DateLastActive', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
// LUM_DiscussionUserWhisperTo Table
$DatabaseColumns['LUM_DiscussionUserWhisperTo']['DiscussionID'] = array('DiscussionID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_DiscussionUserWhisperTo']['WhisperToUserID'] = array('WhisperToUserID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_DiscussionUserWhisperTo']['LastUserID'] = array('LastUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_DiscussionUserWhisperTo']['CountWhispers'] = array('CountWhispers', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_DiscussionUserWhisperTo']['DateLastActive'] = array('DateLastActive', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
// LUM_IpHistory Table
$DatabaseColumns['LUM_IpHistory']['IpHistoryID'] = array('IpHistoryID', 'int(8)', 'NO', '', '1');
$DatabaseColumns['LUM_IpHistory']['RemoteIp'] = array('RemoteIp', 'varchar(30)', 'NO', '', '0');
$DatabaseColumns['LUM_IpHistory']['UserID'] = array('UserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_IpHistory']['DateLogged'] = array('DateLogged', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
// LUM_Role Table
$DatabaseColumns['LUM_Role']['RoleID'] = array('RoleID', 'int(2)', 'NO', '', '1');
$DatabaseColumns['LUM_Role']['Name'] = array('Name', 'varchar(100)', 'NO', '', '0');
$DatabaseColumns['LUM_Role']['Icon'] = array('Icon', 'varchar(155)', 'NO', '', '0');
$DatabaseColumns['LUM_Role']['Description'] = array('Description', 'varchar(200)', 'NO', '', '0');
$DatabaseColumns['LUM_Role']['Active'] = array('Active', "enum('1','0')", 'NO', '1', '0');
$DatabaseColumns['LUM_Role']['PERMISSION_SIGN_IN'] = array('PERMISSION_SIGN_IN', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_Role']['PERMISSION_HTML_ALLOWED'] = array('PERMISSION_HTML_ALLOWED', 'enum('0','1')', 'NO', '0', '0');
$DatabaseColumns['LUM_Role']['PERMISSION_RECEIVE_APPLICATION_NOTIFICATION'] = array('PERMISSION_RECEIVE_APPLICATION_NOTIFICATION', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_Role']['Permissions'] = array('Permissions', 'text', 'NO', '', '0');
$DatabaseColumns['LUM_Role']['Priority'] = array('Priority', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Role']['UnAuthenticated'] = array('UnAuthenticated', "enum('1','0')", 'NO', '0', '0');
// LUM_Style Table
$DatabaseColumns['LUM_Style']['StyleID'] = array('StyleID', 'int(3)', 'NO', '', '1');
$DatabaseColumns['LUM_Style']['AuthUserID'] = array('AuthUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_Style']['Name'] = array('Name', 'varchar(50)', 'NO', '', '0');
$DatabaseColumns['LUM_Style']['Url'] = array('Url', 'varchar(255)', 'NO', '', '0');
$DatabaseColumns['LUM_Style']['PreviewImage'] = array('PreviewImage', 'varchar(20)', 'NO', '', '0');
// LUM_User Table
$DatabaseColumns['LUM_User']['UserID'] = array('UserID', 'int(8)', 'NO', '', '1');
$DatabaseColumns['LUM_User']['RoleID'] = array('RoleID', 'int(2)', 'NO', '0', '0');
$DatabaseColumns['LUM_User']['StyleID'] = array('StyleID', 'int(3)', 'NO', '1', '0');
$DatabaseColumns['LUM_User']['CustomStyle'] = array('CustomStyle', 'varchar(255)', 'YES', '', '0');
$DatabaseColumns['LUM_User']['FirstName'] = array('FirstName', 'varchar(50)', 'NO', '', '0');
$DatabaseColumns['LUM_User']['LastName'] = array('LastName', 'varchar(50)', 'NO', '', '0');
$DatabaseColumns['LUM_User']['Name'] = array('Name', 'varchar(20)', 'NO', '', '0');
$DatabaseColumns['LUM_User']['Password'] = array('Password', 'varchar(32)', 'YES', '', '0');
$DatabaseColumns['LUM_User']['VerificationKey'] = array('VerificationKey', 'varchar(50)', 'NO', '', '0');
$DatabaseColumns['LUM_User']['EmailVerificationKey'] = array('EmailVerificationKey', 'varchar(50)', 'YES', '', '0');
$DatabaseColumns['LUM_User']['Email'] = array('Email', 'varchar(200)', 'NO', '', '0');
$DatabaseColumns['LUM_User']['UtilizeEmail'] = array('UtilizeEmail', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_User']['ShowName'] = array('ShowName', "enum('1','0')", 'NO', '1', '0');
$DatabaseColumns['LUM_User']['Icon'] = array('Icon', 'varchar(255)', 'YES', '', '0');
$DatabaseColumns['LUM_User']['Picture'] = array('Picture', 'varchar(255)', 'YES', '', '0');
$DatabaseColumns['LUM_User']['Attributes'] = array('Attributes', 'text', 'NO', '', '0');
$DatabaseColumns['LUM_User']['CountVisit'] = array('CountVisit', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_User']['CountDiscussions'] = array('CountDiscussions', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_User']['CountComments'] = array('CountComments', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_User']['DateFirstVisit'] = array('DateFirstVisit', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
$DatabaseColumns['LUM_User']['DateLastActive'] = array('DateLastActive', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
$DatabaseColumns['LUM_User']['RemoteIp'] = array('RemoteIp', 'varchar(100)', 'NO', '', '0');
$DatabaseColumns['LUM_User']['LastDiscussionPost'] = array('LastDiscussionPost', 'datetime', 'YES', '', '0');
$DatabaseColumns['LUM_User']['DiscussionSpamCheck'] = array('DiscussionSpamCheck', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_User']['LastCommentPost'] = array('LastCommentPost', 'datetime', 'YES', '', '0');
$DatabaseColumns['LUM_User']['CommentSpamCheck'] = array('CommentSpamCheck', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_User']['UserBlocksCategories'] = array('UserBlocksCategories', "enum('1','0')", 'NO', '0', '0');
$DatabaseColumns['LUM_User']['DefaultFormatType'] = array('DefaultFormatType', 'varchar(20)', 'YES', '', '0');
$DatabaseColumns['LUM_User']['Preferences'] = array('Preferences', 'text', 'YES', '', '0');
$DatabaseColumns['LUM_User']['SendNewApplicantNotifications'] = array('SendNewApplicantNotifications', "enum('1','0')", 'NO', '0', '0');
// LUM_UserBookmark Table
$DatabaseColumns['LUM_UserBookmark']['UserID'] = array('UserID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_UserBookmark']['DiscussionID'] = array('DiscussionID', 'int(8)', 'NO', '0', '1');
// LUM_UserDiscussionWatch Table
$DatabaseColumns['LUM_UserDiscussionWatch']['UserID'] = array('UserID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_UserDiscussionWatch']['DiscussionID'] = array('DiscussionID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_UserDiscussionWatch']['CountComments'] = array('CountComments', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_UserDiscussionWatch']['LastViewed'] = array('LastViewed', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
// LUM_UserRoleHistory Table
$DatabaseColumns['LUM_UserRoleHistory']['UserID'] = array('UserID', 'int(8)', 'NO', '0', '1');
$DatabaseColumns['LUM_UserRoleHistory']['RoleID'] = array('RoleID', 'int(2)', 'NO', '0', '0');
$DatabaseColumns['LUM_UserRoleHistory']['Date'] = array('Date', 'datetime', 'NO', '0000-00-00 00:00:00', '0');
$DatabaseColumns['LUM_UserRoleHistory']['AdminUserID'] = array('AdminUserID', 'int(8)', 'NO', '0', '0');
$DatabaseColumns['LUM_UserRoleHistory']['Notes'] = array('Notes', 'varchar(200)', 'YES', '', '0');
$DatabaseColumns['LUM_UserRoleHistory']['RemoteIp'] = array('RemoteIp', 'varchar(100)', 'YES', '', '0');

class FauxContext {
   var $WarningCollector;
   var $ErrorManager;
   var $SqlCollector;
}

// Create warning & error handlers
$WarningCollector = new MessageCollector();
$ErrorManager = 
$Context = new FauxContext();
$Context->WarningCollector = &$WarningCollector;
$Context->ErrorManager = new ErrorManager();
$Context->SqlCollector = new MessageCollector();

// Retrieve all postback parameters
$PostBackAction = ForceIncomingString('PostBackAction', '');
$DBHost = ForceIncomingString('DBHost', '');
$DBName = ForceIncomingString('DBName', '');
$DBUser = ForceIncomingString('DBUser', '');
$DBPass = ForceIncomingString('DBPass', '');
$Username = ForceIncomingString('Username', '');
$Password = ForceIncomingString('Password', '');
$ConfirmPassword = ForceIncomingString('ConfirmPassword', '');
$SupportEmail = ForceIncomingString('SupportEmail', '');
$SupportName = ForceIncomingString('SupportName', '');
$ApplicationTitle = ForceIncomingString('ApplicationTitle', 'Vanilla');
$CookieDomain = ForceIncomingString('CookieDomain', '');
// Make the banner title the same as the application title
$ApplicationPath = ForceString(@$_SERVER['HTTP_HOST'], '').dirname(ForceString(@$_SERVER['PHP_SELF'], ''));
$WorkingDirectory = getcwd().'/';
$CurrentStep = 1;

// Step 1. Check for correct PHP, MySQL, and permissions
if ($PostBackAction == "Permissions") {
   
   // Make sure we are running at least PHP 4.1.0
   if (intval(str_replace('.', '', phpversion())) < 410) $WarningCollector->Add("It appears as though you are running PHP version ".phpversion().". Vanilla requires at least version 4.1.0 of PHP. You will need to upgrade your version of PHP before you can continue.");
   // Make sure MySQL is available
	if (!function_exists('mysql_connect')) $WarningCollector->Add("It appears as though you do not have MySQL enabled for PHP. You will need a working copy of MySQL and PHP's MySQL extensions enabled in order to run Vanilla.");   
   // Make sure the conf folder is writeable
   if (!is_writable('../conf/')) $WarningCollector->Add("Vanilla needs to have write permission enabled on the conf folder.");
      
   if ($WarningCollector->Count() == 0) $CurrentStep = 2;
} elseif ($PostBackAction == "Database") {
   $CurrentStep = 2;
   // Test the database params provided by the user
   $Connection = @mysql_connect($DBHost, $DBUser, $DBPass);
   if (!$Connection) {
      $WarningCollector->Add("We couldn't connect to the server you provided (".$DBHost."). The database responded with the following message:", $php_errormsg);
   } elseif (!mysql_select_db($DBName, $Connection)) {
      $WarningCollector->Add("We connected to the server, but we couldn't access the \"".$DBName."\" database. Are you sure it exists and that the specified user has access to it?");
   }
   
   // If the database connection worked, attempt to set up the database
   if ($WarningCollector->Count() == 0 && $Connection) {
      // Retrieve a list of all tables in the database
      $TableData = @mysql_query('show tables', $Connection);
      if (!$TableData) {
         $WarningCollector->Add("We had some problems identifying the tables already in your database: ". mysql_error($Connection));
      } else {
         
      }
      if (!@mysql_query($CurrentQuery, $Connection)) {
         if (eregi("Table 'LUM_([a-zA-Z]+)' already exists", mysql_error($Connection))) {
            $WarningCollector->Add("It looks like you're trying to overwrite an existing installation of the Vanilla database. Are you sure you want to do this? If so, you'll need to go and manually remove the existing tables yourself.");
         } else {
            $WarningCollector->Add("An error occurred while we were attempting to create the database tables. Mysql reported the following error: ".mysql_error($Connection));
         }
         $i = count($SqlLines)+1;
      }
      // Close the database connection
      @mysql_close($Connection);
   }
   
   // If the database was created successfully, save all parameters to the settings file
   if ($WarningCollector->Count() == 0) {
      // Open the settings file
      $ConstantsFile = $WorkingDirectory."appg/settings.php";
      $ConstantManager = new ConstantManager($Context);
      $ConstantManager->DefineConstantsFromFile($ConstantsFile);
      // Set the constants to their new values
      $ConstantManager->SetConstant("dbHOST", $DBHost);
      $ConstantManager->SetConstant("dbNAME", $DBName);
      $ConstantManager->SetConstant("dbUSER", $DBUser);
      $ConstantManager->SetConstant("dbPASSWORD", $DBPass);
      $ConstantManager->SetConstant("agAPPLICATION_PATH", $WorkingDirectory);
      $ConstantManager->SetConstant("agDOMAIN", $ApplicationPath);
      // Save the settings file
      if (!$ConstantManager->SaveConstantsToFile($ConstantsFile)) {
         $WarningCollector->Clear();
         $WarningCollector->Add("For some reason we couldn't save your global settings to the appg/settings.php file.");
      }
   }
   if ($WarningCollector->Count() == 0) {
      $CurrentStep = 3;
      $LanguageSelect = GetLanguageSelect($WorkingDirectory."languages/", $WarningCollector, $Language);
   }
   
} elseif ($PostBackAction == "User") {
   $CurrentStep = 3;
   // Validate user inputs
   if (strip_tags($Username) != $Username) $WarningCollector->Add("You really shouldn't have any html into your username.");
   if (strlen($Username) > 20) $WarningCollector->Add("Your username is too long");
   if ($Password != $ConfirmPassword) $WarningCollector->Add("The passwords you entered didn't match.");
   if (!eregi("(.+)@(.+)\.(.+)", $SupportEmail)) $WarningCollector->Add("The email address you entered doesn't appear to be valid.");
   if (strip_tags($ApplicationTitle) != $ApplicationTitle) $WarningCollector->Add("You can't have any html in your forum name.");
   if ($Username == "") $WarningCollector->Add("You must provide a username.");
   if ($Password == "") $WarningCollector->Add("You must provide a password.");
   if ($SupportName == "") $WarningCollector->Add("You must provide a support contact name.");
   if ($ApplicationTitle == "") $WarningCollector->Add("You must provide an application title.");
   
   // Open the database connection
   $Connection = false;
   if ($WarningCollector->Count() == 0) {
      $Connection = @mysql_connect($DBHost, $DBUser, $DBPass);
      if (!$Connection) {
         $WarningCollector->Add("We couldn't connect to the server you provided (".$DBHost."). Are you sure you entered the right server, username and password?");
      } elseif (!mysql_select_db($DBName, $Connection)) {
         $WarningCollector->Add("We connected to the server, but we couldn't access the \"".$DBName."\" database. Are you sure it exists and that the specified user has access to it?");
      }
   }
   
   // Create the administrative user
   if ($WarningCollector->Count() == 0 && $Connection) {
      $Username = FormatStringForDatabaseInput($Username);
      $Password = FormatStringForDatabaseInput($Password);
      
      $s = new SqlBuilder($Context);
      $s->SetMainTable("User", "u");
      $s->AddFieldNameValue("FirstName", "Administrative");
      $s->AddFieldNameValue("LastName", "User");
      $s->AddFieldNameValue("Email", FormatStringForDatabaseInput($SupportEmail));
      $s->AddFieldNameValue("Name", $Username);
      $s->AddFieldNameValue("Password", $Password, 1, "md5");
		$s->AddFieldNameValue("DateFirstVisit", MysqlDateTime());
		$s->AddFieldNameValue("DateLastActive", MysqlDateTime());
		$s->AddFieldNameValue("CountVisit", 0);
		$s->AddFieldNameValue("CountDiscussions", 0);
		$s->AddFieldNameValue("CountComments", 0);
		$s->AddFieldNameValue("RoleID", 6);
		$s->AddFieldNameValue("StyleID", 1);
		$s->AddFieldNameValue("UtilizeEmail", 0);
		$s->AddFieldNameValue("RemoteIP", GetRemoteIp(1));
		if (!@mysql_query($s->GetInsert(), $Connection)) {
         $WarningCollector->Add("Something bad happened when we were trying to create your administrative user account. Mysql said: ".mysql_error($Connection));
      } else {
         // Now insert the role history assignment
			$NewUserID = mysql_insert_id($Connection);
			$s->Clear();
			$s->SetMainTable("UserRoleHistory", "h");
			$s->AddFieldNameValue("UserID", $NewUserID);
			$s->AddFieldNameValue("RoleID", 6);
			$s->AddFieldNameValue("Date", MysqlDateTime());
			$s->AddFieldNameValue("AdminUserID", $NewUserID);
			$s->AddFieldNameValue("Notes", "Initial administrative account created");
			$s->AddFieldNameValue("RemoteIp", GetRemoteIp(1));
         // Fail silently on this one
         @mysql_query($s->GetInsert(), $Connection);
      }
   }
   
   // Close the database connection
   @mysql_close($Connection);
   
   // Save the application constants
   if ($WarningCollector->Count() == 0) {
      $ConstantsFile = $WorkingDirectory."appg/settings.php";
      $ConstantManager = new ConstantManager($Context);
      $ConstantManager->DefineConstantsFromFile($ConstantsFile);
      // Set the constants to their new values
      $ConstantManager->SetConstant("agSUPPORT_EMAIL", $SupportEmail);
      $ConstantManager->SetConstant("agSUPPORT_NAME", $SupportName);
      $ConstantManager->SetConstant("agAPPLICATION_TITLE", $ApplicationTitle);
      $ConstantManager->SetConstant("agBANNER_TITLE", $ApplicationTitle);
      $ConstantManager->SetConstant("agCOOKIE_DOMAIN", $CookieDomain);
      // Save the settings file
      if (!$ConstantManager->SaveConstantsToFile($ConstantsFile)) {
         $WarningCollector->Clear();
         $WarningCollector->Add("For some reason we couldn't save your global settings to the appg/settings.php file.");
      }
   }
   
   // Save the language assignment
   if ($WarningCollector->Count() == 0) {
      // Open the language file for editing
      $LanguageFile = $WorkingDirectory."appg/language.php";
		$LanguageFileContents = "<?php
/*
DO NOT EDIT THIS FILE
This file is managed by Vanilla. It is completely erased
and rebuilt when the language is defined.
*/
include($this->Context->Configuration["LANGUAGES_PATH"].\"".$Language.".php\");
?>";
   	$FileHandle = @fopen($LanguageFile, "wb");
		if (!$FileHandle) {
			$WarningCollector->Add("We encountered an error while we were attempting to open the appg/language.php file.");
		} else {
			if (!@fwrite($FileHandle, $LanguageFileContents)) $WarningCollector->Add("We encountered an error while we were attempting to write to the appg/language.php file.");
   		@fclose($FileHandle);      
		}
   }
   if ($WarningCollector->Count() == 0) {
      $CurrentStep = 4;
   } else {
      $LanguageSelect = GetLanguageSelect($WorkingDirectory."languages/", $WarningCollector, $Language);
   }
} 
   
// Write the page

echo("<".chr(63)."xml version=\"1.0\" encoding=\"utf-8\"".chr(63).">");
?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-ca\">
   <head>
      <title>Vanilla Installer</title>
      <script type="text/javascript" src="./js/global.js"></script>
      <style type="text/css">
         body {
            background: #F5F5F5;
            margin: 0px;
            padding: 0px;
         }
         body, div {
            font-family: Verdana, Trebuchet MS, Arial;
            font-size: 12px;
            line-height: 170%;
         }
         h1 {
            font-family: Trebuchet MS, Arial, Tahoma, Verdana;
            font-size: 18px;
         }
         a, a:link, a:visited {
            text-decoration: underline;
            color: #97D330;
         }
         a:hover {
            text-decoration: underline;
            color: #000;
         }
         .Warnings {
            padding: 8px;
            background: #E3FEB3;
            border-top: 1px solid #97D330;
            border-bottom: 1px solid #97D330;
         }
         .Warnings,
         .Warnings div {
            color: #5D8F06;
            font-size: 11px;
         }
         .Warnings strong {
            display: block;
            font-size: 12px;
            padding-bottom: 4px;
         }
         .Banner {
            padding: 20px;
         }
         .Body {
            background: #fff;
            border-top: 2px solid #97D330;
            border-bottom: 2px solid #97D330;
            padding: 26px;
         }
         .Contents {
            max-width: 600px;
         }
         ul ul {
            margin-bottom: 10px;
         }
         .Form {
            background: #eee;
            padding: 20px;
            padding-top: 15px;
            padding-bottom: 15px;
         }
         dl {
            margin: 0px;
            padding: 0px;
         }
         dt {
            padding-top: 5px;
            position: absolute;
         }
         dd {
            padding-left: 170px;
            padding-top: 5px;
            padding-bottom: 5px;
         }
         .Form input {
            width: 200px;
         }
         .Button a, .Button a:link, .Button a:visited, .Button a:hover {
            font-size: 16px;
            text-decoration: underline;
            font-family: Trebuchet MS, Arial, Tahoma, Verdana;
            font-weight: bold;
         }
         .Button a, .Button a:link, .Button a:visited {
            color: orange;
         }
         .Button a:hover {
            color: #97D330;
         }
         
         .Foot {
            padding: 10px;
            padding-left: 20px;
            color: #ddd;
            font-size: 11px;
         }
         .Foot a, .Foot a:link, .Foot a:visited {
            color: #ccc;
            text-decoration: none;
         }
         .Foot a:hover {
            color: #555;
            text-decoration: none;         
         }
      </style>
   </head>
   <body>
      <div class="Banner">
         <img src="./images/vanilla_installer.gif" height="53" width="346" border="0" alt="Lussumo Vanilla" />
      </div>
      <div class="Body">
         <div class="Contents">
         <?php
         if ($CurrentStep == 1) {
            echo("<h1>Vanilla Installation Wizard (Step 1 of 3)</h1>");
            if ($WarningCollector->Count() > 0) {
               echo("<div class=\"Warnings\">
                  <strong>Oops. We came across some problems while checking your permissions...</strong>
                  ".$WarningCollector->GetMessages()."
               </div>
               <p>Let's try this again...</p>");
            }
            echo("<p>Before we can do much of anything, we need to make sure that you've got your directory &amp; file permissions set up properly.</p>
               <p>Vanilla is going to need read AND write access to the following files:</p>
               <ul>
                  <li>".$WorkingDirectory."appg/settings.php</li>
                  <li>".$WorkingDirectory."appg/extensions.php</li>
                  <li>".$WorkingDirectory."appg/language.php</li>
                  <li>".$WorkingDirectory."database.sql</li>
               </ul>
               <p>Vanilla is also going to need read access to the following folder:</p>
               <ul>
                  <li>".$WorkingDirectory."languages/</li>
               </ul>
               <p>And finally, the filebrowser and thumbnailer will need read AND write access to the following folder:</p>
               <ul>
                  <li>".$WorkingDirectory."images/</li>
               </ul>
               <p>If you are running a *nix server and you have command line access, you can achieve these permissions by navigating to the Vanilla folder. Once you are sitting in the same folder as the installer.php file, run the following commands:</p>
               <blockquote>
                  <code>
                     chmod 666 appg/settings.php
                     <br />chmod 666 appg/language.php
                     <br />chmod 666 appg/extensions.php
                     <br />chmod 666 database.sql
                     <br />chmod 757 images/
                  </code>
               </blockquote>
               <p>Once you've got your permissions set up properly...</p>
               <div class=\"Button\"><a href=\"installer.php?PostBackAction=Permissions\">Click here to check your permissions and proceed to the next step</a></div>");
         } elseif ($CurrentStep == 2) {
            echo("<h1>Vanilla Installation Wizard (Step 2 of 3)</h1>");
            if ($WarningCollector->Count() > 0) {
               echo("<div class=\"Warnings\">
                  <strong>Oops. We came across some problems while setting up Vanilla...</strong>
                  ".$WarningCollector->GetMessages()."
               </div>
               <p>Let's try this again...</p>");
            }
            echo("<p>Below you can provide the connection parameters for the mysql server where you want to install Vanilla. If you haven't done it yet, now would be a good time to create the database where you want Vanilla installed.</p>
            <form name=\"frmDatabase\" method=\"post\" action=\"installer.php\">
            <input type=\"hidden\" name=\"PostBackAction\" value=\"Database\" />
            <div class=\"Form\">
               <dl>
                  <dt>MySQL Server</dt>
                  <dd><input type=\"text\" name=\"DBHost\" value=\"".FormatStringForDisplay($DBHost, 1)."\" /></dd>
                  <dt>MySQL Database Name</dt>
                  <dd><input type=\"text\" name=\"DBName\" value=\"".FormatStringForDisplay($DBName, 1)."\" /></dd>
                  <dt>MySQL User</dt>
                  <dd><input type=\"text\" name=\"DBUser\" value=\"".FormatStringForDisplay($DBUser, 1)."\" /></dd>
                  <dt>MySQL Password</dt>
                  <dd><input type=\"password\" name=\"DBPass\" value=\"".FormatStringForDisplay($DBPass, 1)."\" /></dd>
               </dl>
            </div>
            </form>
            <div class=\"Button\"><a href=\"javascript:document.frmDatabase.submit();\">Click here to create Vanilla's database tables and proceed to the next step</a></div>");
         } elseif ($CurrentStep == 3) {
            if ($PostBackAction != "User") $CookieDomain = ForceString(@$_SERVER['HTTP_HOST'], "");
            echo("<h1>Vanilla Installation Wizard (Step 3 of 3)</h1>");
            if ($WarningCollector->Count() > 0) {
               echo("<div class=\"Warnings\">
                  <strong>Oops. We came across some problems while setting up Vanilla...</strong>
                  ".$WarningCollector->GetMessages()."
               </div>");
            }
            echo("<p>Now let's set up your administrative account for Vanilla.</p>
            <form name=\"frmUser\" method=\"post\" action=\"installer.php\">
            <input type=\"hidden\" name=\"PostBackAction\" value=\"User\" />
            <input type=\"hidden\" name=\"DBHost\" value=\"".FormatStringForDisplay($DBHost)."\" />
            <input type=\"hidden\" name=\"DBName\" value=\"".FormatStringForDisplay($DBName)."\" />
            <input type=\"hidden\" name=\"DBUser\" value=\"".FormatStringForDisplay($DBUser)."\" />
            <input type=\"hidden\" name=\"DBPass\" value=\"".FormatStringForDisplay($DBPass)."\" />
            <div class=\"Form\">
               <dl>
                  <dt>Username</dt>
                  <dd><input type=\"text\" name=\"Username\" value=\"".FormatStringForDisplay($Username, 1)."\" /></dd>
                  <dt>Password</dt>
                  <dd><input type=\"password\" name=\"Password\" value=\"".FormatStringForDisplay($Password, 1)."\" /></dd>
                  <dt>Confirm Password</dt>
                  <dd><input type=\"password\" name=\"ConfirmPassword\" value=\"".FormatStringForDisplay($ConfirmPassword, 1)."\" /></dd>
               </dl>
            </div>
            <p>Up next we've got to set up the support contact information for your forum. This is what people will see when support emails go out from the system for things like password retrieval and role changes.</p>
            <div class=\"Form\">
               <dl>
                  <dt>Support Contact Name</dt>
                  <dd><input type=\"text\" name=\"SupportName\" value=\"".FormatStringForDisplay($SupportName, 1)."\" /></dd>
                  <dt>Support Email Address</dt>
                  <dd><input type=\"text\" name=\"SupportEmail\" value=\"".FormatStringForDisplay($SupportEmail, 1)."\" /></dd>
               </dl>
            </div>
            <p>What do you want to call your forum?</p>
            <div class=\"Form\">
               <dl>
                  <dt>Forum Name</dt>
                  <dd><input type=\"text\" name=\"ApplicationTitle\" value=\"".FormatStringForDisplay($ApplicationTitle, 1)."\" /></dd>
               </dl>
            </div>
            <p>When members use the \"remember me\" feature of the sign in form, we assign a cookie to their browser. That cookie is normally associated with your domain name, but you may want to associate it with something else like the sub-folder in which Vanilla resides (if Vanilla is in a sub-folder). Specify your cookie domain here.</p>
            <div class=\"Form\">
               <dl>
                  <dt>Cookie Domain</dt>
                  <dd><input type=\"text\" name=\"CookieDomain\" value=\"".FormatStringForDisplay($CookieDomain, 1)."\" /></dd>
               </dl>
            </div>
            <p>Finally, select the language you want Vanilla to use. If you don't see your language here, you should ".GetEmail("support@lussumo.com", "contact us")." about setting up a Vanilla dictionary for your language!</p>
            <div class=\"Form\">
               <dl>
                  <dt>Language</dt>
                  <dd>".$LanguageSelect->Get()."</dd>
               </dl>
            </div>
            </form>
            <div class=\"Button\"><a href=\"javascript:document.frmUser.submit();\">Click here to complete the setup process!</a></div>");
         } else {
            echo("<h1>Vanilla Installation Wizard (Complete)</h1>
            <p><strong>That's it!</strong></p>
            <p>Vanilla is set up and ready to go, so what do you do next?</p>
            <p>Before you start inviting your friends in for discussions, there are a lot of other things you might want to set up. For example, in the settings tab, you can:</p>
            <ul>
               <li>
                  Turn some of the extensions on, like...
                  <ul>
                     <li>The category quick-jump that allows members to jump between categories from their control panel</li>
                     <li>The clipboard that allows members to quickly paste snippets of information into their comments</li>
                     <li>The Html Formatter that allows members to use a limited set of html when adding their comments to discussions</li>
                     <li>And lots more!</li>
                  </ul>
               </li>
               <li>
                  Fine-tune application settings like...
                  <ul>
                     <li>Change the number of discussions or comments per page</li>
                     <li>Allow the public to browse your forum without an account</li>
                     <li>Disable discussion categories and run your forum as one giant discussion container</li>
                     <li>Allow your members to change their usernames at will</li>
                     <li>Much, much more!</li>
                  </ul>
               </li>
               <li>Create new roles with various different permissions</li>
               <li>Create new categories, and even limit which roles get to access them</li>
               <li>Allow new users to be automatically granted access when they apply for membership</li>
            </ul>
            <p>And that's not all. There's also the latest version of the Lussumo Filebrowser and Thumbnailer sitting in your images folder.</p>
            <div class=\"Button\"><a href=\"people.php\">Go sign in and have some fun!</a></div>");       
         }
         ?>
         </div>
      </div>
      <div class="Foot">
         <a href="http://lussumo.com">Lussumo</a> <a href="http://getvanilla.com">Vanilla</a> & <a href="http://thefilebrowser.com">Filebrowser</a> Copyright &copy; 2001 - 2005
      </div>   
   </body>
</html>