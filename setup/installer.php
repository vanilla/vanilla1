<?php
// REPORT ALL ERRORS
error_reporting(E_ALL);
// DO NOT ALLOW PHP_SESS_ID TO BE PASSED IN THE QUERYSTRING
ini_set('session.use_only_cookies', 1);
// Track errors so explicit error messages can be reported should errors be encountered
ini_set('track_errors', 1);
// Define constant for magic_quotes
define('MAGIC_QUOTES_ON', get_magic_quotes_gpc());

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
$WorkingDirectory = str_replace('\\', '/', getcwd()).'/';
$CurrentStep = 1;
$AllowNext = 0;

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
      // Make sure there are no conflicting tables in the database
      $TableData = @mysql_query('show tables', $Connection);
      if (!$TableData) {
         $WarningCollector->Add("We had some problems identifying the tables already in your database: ". mysql_error($Connection));
      } else {
         $TableConflicts = array();
         while ($Row = mysql_fetch_array($TableData)) {
            if (array_key_exists($Row["Tables_in_".$DBName], $DatabaseTables)) {
               $TableConflicts[] = $Row["Tables_in_".$DBName];
            }
         }
         if (count($TableConflicts) == count($DatabaseTables)) {
            $WarningCollector->Add("It appears as though you've already got Vanilla installed. If you are attempting to upgrade Vanilla, you should be using the upgrade script. If you are just trying to reconfigure your installation, you can skip the database setup by clicking \"next\" below.");
            $AllowNext = 1;
         } elseif (count($TableConflicts) > 0) {
            $WarningCollector->Add("There appear to be some tables already in your database that conflict with the tables Vanilla would need to insert. Those tables are: ".implode(',', $TableConflicts).".");
         } else {
            // Go ahead and install the database tables
            // Open the database file & retrieve sql
            $SqlLines = @file($WorkingDirectory."database.sql");
            if (!$SqlLines) {
               $WarningCollector->Add("We couldn't open the \"".$WorkingDirectory."database.sql\" file.");
            } else {
               $CurrentQuery = "";
               $CurrentLine = "";
               for ($i = 0; $i < count($SqlLines); $i++) {
                  $CurrentLine = trim($SqlLines[$i]);
                  if ($CurrentLine == "") {
                     if ($CurrentQuery != "") {
                        if (!@mysql_query($CurrentQuery, $Connection)) {
                           $WarningCollector->Add("An error occurred while we were attempting to create the database tables. Mysql reported the following error: ".mysql_error($Connection));
                           $i = count($SqlLines)+1;
                        }
                        $CurrentQuery = "";
                     }
                  } else {
                     $CurrentQuery .= $CurrentLine;
                  }
               }
               // Make sure to catch the last query
               if ($CurrentQuery != "") {
                  if (!@mysql_query($CurrentQuery, $Connection)) {
                     $WarningCollector->Add("An error occurred while we were attempting to create the database tables. Mysql reported the following error: ".mysql_error($Connection));
                     $i = count($SqlLines)+1;
                  }
               }
            }
         }      
      }
      // Close the database connection
      @mysql_close($Connection);
   }
   
   // If the database was created successfully, save all parameters to the conf/database.php file
   if ($WarningCollector->Count() == 0) {
      // Save database settings
      $DBFile = $WorkingDirectory . 'conf/database.php';
      $DBManager = new ConfigurationManager($Context);
      $DBManager->DefineSetting("DATABASE_HOST", $DBHost, 1);
      $DBManager->DefineSetting("DATABASE_NAME", $DBName, 1);
      $DBManager->DefineSetting("DATABASE_USER", $DBUser, 1);
      $DBManager->DefineSetting("DATABASE_PASSWORD", $DBPass, 1);
      if (!$DBManager->SaveSettingsToFile($DBFile)) {
         $WarningCollector->Clear();
         $WarningCollector->Add("For some reason we couldn't save your database settings to the '.$DBFile.' file.");
      }
      
      // Save general settings
      $SettingsFile = $WorkingDirectory . 'conf/settings.php';
      $SettingsManager = new ConfigurationManager($Context);
      $SettingsManager->DefineSetting("APPLICATION_PATH", $WorkingDirectory, 1);
      $SettingsManager->DefineSetting("BASE_URL", $ApplicationPath, 1);
      if (!$SettingsManager->SaveSettingsToFile($SettingsFile)) {
         $WarningCollector->Clear();
         $WarningCollector->Add("For some reason we couldn't save your general settings to the '.$SettingsFile.' file.");
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
		$s->AddFieldNameValue("RoleID", 4);
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
			$s->AddFieldNameValue("RoleID", 4);
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
      $SettingsFile = $WorkingDirectory . 'conf/settings.php';
      $SettingsManager = new ConfigurationManager($Context);
      $SettingsManager->DefineSetting("SUPPORT_EMAIL", $SupportEmail, 1);
      $SettingsManager->DefineSetting("SUPPORT_NAME", $SupportName, 1);
      $SettingsManager->DefineSetting("APPLICATION_TITLE", $ApplicationTitle, 1);
      $SettingsManager->DefineSetting("BANNER_TITLE", $ApplicationTitle, 1);
      $SettingsManager->DefineSetting("COOKIE_DOMAIN", $CookieDomain, 1);
      $SettingsManager->DefineSetting("LANGUAGE", 'English', 1);
      if (!$SettingsManager->SaveSettingsToFile($SettingsFile)) {
         $WarningCollector->Clear();
         $WarningCollector->Add("For some reason we couldn't save your general settings to the '.$SettingsFile.' file.");
      }
   }
   
   if ($WarningCollector->Count() == 0) $CurrentStep = 4;
} 
   
// Write the page
?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-ca\">
   <head>
      <title>Vanilla 1 Installer</title>
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
      <h1>
         Vanilla 1 Installer
      </h1>
      <?php
      if ($CurrentStep == 1) {
         echo  '<h2>Vanilla Installation Wizard (Step 1 of 3)</h2>';
         if ($WarningCollector->Count() > 0) {
            echo "<div class=\"Warnings\">
               <strong>Oops. We came across some problems while checking your permissions...</strong>
               ".$WarningCollector->GetMessages()."
            </div>
            <p>Let's try this again...</p>";
         }
         echo "<p>Before we can do much of anything, we need to make sure that you've got your directory &amp; file permissions set up properly.</p>
            <p>Vanilla is going to need read AND write access to the following files:</p>
            <ul>
               <li>".$WorkingDirectory."conf/database.php</li>
               <li>".$WorkingDirectory."conf/settings.php</li>
               <li>".$WorkingDirectory."conf/extensions.php</li>
               <li>".$WorkingDirectory."conf/language.php</li>
            </ul>
            <p>Vanilla is also going to need read access to the following folder:</p>
            <ul>
               <li>".$WorkingDirectory."languages/</li>
               <li>".$WorkingDirectory."setup/</li>
            </ul>
            <p>If you are running a Linux/Unix/Mac server and you have command line access, you can achieve these permissions by navigating to the Vanilla folder. Once you are sitting in the same root folder of Vanilla:</p>
            <blockquote>
               <code>
                  chmod 666 appg/settings.php
                  <br />chmod 666 appg/language.php
                  <br />chmod 666 appg/extensions.php
                  <br />chmod 666 database.sql
               </code>
            </blockquote>
            <p>Once you've got your permissions set up properly...</p>
            <div class=\"Button\"><a href=\"installer.php?PostBackAction=Permissions\">Click here to check your permissions and proceed to the next step</a></div>";
      } elseif ($CurrentStep == 2) {
            echo "<h1>Vanilla Installation Wizard (Step 2 of 3)</h1>";
            if ($WarningCollector->Count() > 0) {
               echo "<div class=\"Warnings\">
                  <strong>Oops. We came across some problems while setting up Vanilla...</strong>
                  ".$WarningCollector->GetMessages()."
               </div>
               <p>Let's try this again...</p>";
            }
            echo "<p>Below you can provide the connection parameters for the mysql server where you want to install Vanilla. If you haven't done it yet, now would be a good time to create the database where you want Vanilla installed.</p>
            <fieldset>
               <form id=\"frmDatabase\" method=\"post\" action=\"installer.php\">
               <input type=\"hidden\" name=\"PostBackAction\" value=\"Database\" />
                  <ul>
                     <li>
                        <label for=\"tDBHost\">MySQL Server</label>
                        <input type=\"text\" id=\"tDBHost\" name=\"DBHost\" value=\"".FormatStringForDisplay($DBHost, 1)."\" />
                     </li>
                     <li>
                        <label for=\"tDBName\">MySQL Database Name</label>
                        <input type=\"text\" id=\"tDBName\" name=\"DBName\" value=\"".FormatStringForDisplay($DBName, 1)."\" />
                     </li>
                     <li>
                        <label for=\"tDBUser\">MySQL User</label>
                        <input type=\"text\" id=\"tDBUser\" name=\"DBUser\" value=\"".FormatStringForDisplay($DBUser, 1)."\" />
                     </li>
                     <li>
                        <label for=\"tDBPass\">MySQL Password</label>
                        <input type=\"password\" id=\"tDBPass\" name=\"DBPass\" value=\"".FormatStringForDisplay($DBPass, 1)."\" />
                     </li>
                  </ul>
                  <div class=\"Button\"><input type=\"submit\" value=\"Click here to create Vanilla's database tables and proceed to the next step\" /></div>
               </form>
            </fieldset>";
         } elseif ($CurrentStep == 3) {
            if ($PostBackAction != "User") $CookieDomain = ForceString(@$_SERVER['HTTP_HOST'], "");
            echo "<h1>Vanilla Installation Wizard (Step 3 of 3)</h1>";
            if ($WarningCollector->Count() > 0) {
               echo "<div class=\"Warnings\">
                  <strong>Oops. We came across some problems while setting up Vanilla...</strong>
                  ".$WarningCollector->GetMessages()."
               </div>";
            }
            echo "<p>Now let's set up your administrative account for Vanilla.</p>
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
            </form>
            <div class=\"Button\"><input type=\"submit\" value=\"Click here to complete the setup process!\" /></div>";
         } else {
            echo "<h1>Vanilla Installation Wizard (Complete)</h1>
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
            <div class=\"Button\"><a href=\"people.php\">Go sign in and have some fun!</a></div>";
         }
         ?>
         </div>
      </div>
      <div class="Foot">
         <a href="http://lussumo.com">Lussumo</a> <a href="http://getvanilla.com">Vanilla</a> Copyright &copy; 2001
      </div>   
   </body>
</html>