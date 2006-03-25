<?php
// REPORT ALL ERRORS
error_reporting(E_ALL);

// INCLUDE NECESSARY CLASSES & FUNCTIONS
define("LIBRARY", "../GlobalLibrary/");
include(LIBRARY."Utility.Functions.php");
include(LIBRARY."Input.Select.class.php");
include(LIBRARY."Utility.SqlBuilder.class.php");
include(LIBRARY."Utility.MessageCollector.class.php");
include(LIBRARY."Utility.ErrorManager.class.php");
include(LIBRARY."Utility.Constant.class.php");

function GetLanguageSelect($FolderName, &$WarningCollector, $SelectedLanguage) {
   $Select = false;
   $FolderHandle = @opendir($FolderName);
   if (!$FolderHandle) {
      $WarningCollector->Add("We had a problem opening the languages folder");
   } else {
      $Languages = array();
      $Key = 0;
      
      // Loop through each file
      while (false !== ($Item = readdir($FolderHandle))) {
         $RecordItem = true;
         if ($Item == "." || $Item == ".." || is_dir($FolderName.$Item)) {
            // do nothing
         } else {
            // Retrieve languages names
            $FileParts = explode(".", $Item);
            $Languages[] = $FileParts[0];
            if ($FileParts[0] == $SelectedLanguage) $Key = count($Languages);
         }
      }
      $Select = new Select();
      $Select->Name = "Language";
      for ($i = 0; $i < count($Languages); $i++) {
         $Select->AddOption($Languages[$i], $Languages[$i]);
      }
      $Select->SelectedIndex = $Key;
   }
   return $Select;
}

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

$PostBackAction = ForceIncomingString("PostBackAction", "");
$DBHost = ForceIncomingString("DBHost", "");
$DBName = ForceIncomingString("DBName", "");
$DBUser = ForceIncomingString("DBUser", "");
$DBPass = ForceIncomingString("DBPass", "");
$Username = ForceIncomingString("Username", "");
$Password = ForceIncomingString("Password", "");
$ConfirmPassword = ForceIncomingString("ConfirmPassword", "");
$SupportEmail = ForceIncomingString("SupportEmail", "");
$SupportName = ForceIncomingString("SupportName", "");
$ApplicationTitle = ForceIncomingString("ApplicationTitle", "Vanilla");
$CookieDomain = ForceIncomingString("CookieDomain", "");
// Make the banner title the same as the application title
$Language = ForceIncomingString("Language", "English");
$ApplicationPath = ForceString(@$_SERVER['HTTP_HOST'], "").dirname(ForceString(@$_SERVER["PHP_SELF"], ""));
$WorkingDirectory = getcwd()."/";
$CurrentStep = 1;

// Step 1. Set up read/write permissions
if ($PostBackAction == "Permissions") {
   
   // First get the read/write permissions on application files
   $Files = array();
   $Files[] = "appg/settings.php";
   $Files[] = "appg/language.php";
   $Files[] = "appg/extensions.php";
   $Files[] = "database.sql";
   for ($i = 0; $i < count($Files); $i++) {
      // Read the file first
      $AbsoluteFile = $WorkingDirectory.$Files[$i];
      $Lines = @file($AbsoluteFile);
      if (!$Lines) $WarningCollector->Add("We couldn't read the \"".$AbsoluteFile."\" file.");
      // Open the file for reading and writing
      $FileHandle = @fopen($AbsoluteFile, "wb");
      if (!$FileHandle) {
         $WarningCollector->Add("We couldn't open the \"".$AbsoluteFile."\" file.");
      } else {
         $FileContents = implode("", $Lines);
         if (!@fwrite($FileHandle, $FileContents)) $WarningCollector->Add("We couldn't write to the \"".$AbsoluteFile."\" file.");
         @fclose($FileHandle);
      }
   }
   
   // Now attempt to create a file in the images directory
   $AbsoluteFile = $WorkingDirectory."images/phpinfo.php";
   $FileHandle = @fopen($AbsoluteFile, "wb");
   if (!$FileHandle) {
      $WarningCollector->Add("We couldn't create our test file \"".$AbsoluteFile."\" in the /images directory.");
   } else {
      $FileContents = "<?php phpinfo(); ?>";
      if (!@fwrite($FileHandle, $FileContents)) $WarningCollector->Add("We couldn't write our test file \"".$AbsoluteFile."\" to the /images directory.");
      @fclose($FileHandle);
   }
   
   if ($WarningCollector->Count() == 0) $CurrentStep = 2;
} elseif ($PostBackAction == "Database") {
   $CurrentStep = 2;
   // Test the database params provided by the user
   $Connection = @mysql_connect($DBHost, $DBUser, $DBPass);
   if (!$Connection) {
      $WarningCollector->Add("We couldn't connect to the server you provided (".$DBHost."). Are you sure you entered the right server, username and password?");
   } elseif (!mysql_select_db($DBName, $Connection)) {
      $WarningCollector->Add("We connected to the server, but we couldn't access the \"".$DBName."\" database. Are you sure it exists and that the specified user has access to it?");
   }
   
   // If the database connection worked, attempt to set up the database
   if ($WarningCollector->Count() == 0 && $Connection) {
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
                     if (eregi("Table 'LUM_([a-zA-Z]+)' already exists", mysql_error($Connection))) {
                        $WarningCollector->Add("It looks like you're trying to overwrite an existing installation of the Vanilla database. Are you sure you want to do this? If so, you'll need to go and manually remove the existing tables yourself.");
                     } else {
                        $WarningCollector->Add("An error occurred while we were attempting to create the database tables. Mysql reported the following error: ".mysql_error($Connection));
                     }
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
               if (eregi("Table 'LUM_([a-zA-Z]+)' already exists", mysql_error($Connection))) {
                  $WarningCollector->Add("It looks like you're trying to overwrite an existing installation of the Vanilla database. Are you sure you want to do this? If so, you'll need to go and manually remove the existing tables yourself.");
               } else {
                  $WarningCollector->Add("An error occurred while we were attempting to create the database tables. Mysql reported the following error: ".mysql_error($Connection));
               }
               $i = count($SqlLines)+1;
            }
         }
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