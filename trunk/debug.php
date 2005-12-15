<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Uses cookies to turn debugging information on and off
*/
include("appg/settings.php");
include($Configuration["APPLICATION_PATH"]."appg/headers.php");
include($Configuration["LIBRARY_PATH"]."Utility.Functions.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Database.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.SqlBuilder.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.MessageCollector.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.ErrorManager.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.ObjectFactory.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.StringManipulator.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Context.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Page.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Writer.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Delegation.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Control.php");
include($Configuration["LIBRARY_PATH"]."Vanilla.Functions.php");
include($Configuration["LIBRARY_PATH"].$Configuration["AUTHENTICATION_MODULE"]);
include($Configuration["LIBRARY_PATH"]."People.Class.Session.php");
include($Configuration["LIBRARY_PATH"]."People.Class.User.php");

$Context = new Context($Configuration);

// Start the session management
$Context->StartSession();

include($Configuration["APPLICATION_PATH"]."conf/language.php");

$Mode = ForceIncomingCookieString("Mode", MODE_RELEASE);

$PageAction = ForceIncomingString("PageAction", "");
if ($PageAction == "ToggleDebug") {
	if ($Mode == MODE_DEBUG) {
		$Mode = MODE_RELEASE;
	} else {
		$Mode = MODE_DEBUG;
	}
	setcookie("Mode", $Mode, time()+31104000,"/");
} 

//////////////////////
// Display the page //
//////////////////////

echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-ca\">
<head>
<title>".$Configuration["APPLICATION_TITLE"]." ".$Context->GetDefinition("Debug")."</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"".$Context->StyleUrl."css/utility.css\" />
</head>
<body>
	<div class=\"PageContainer\">
		<h1>".$Context->GetDefinition("DebugTitle")."</h1>
		<h2>".$Context->GetDefinition("DebugDescription")."</h2>");
		if ($PageAction == "ToggleDebug") {
			echo("<p>processing...</p>
			<script language=\"javascript\" type=\"text/javascript\">
			//<![CDATA[	
				setTimeout(\"document.location='debug.php';\",600);
			//]]>
			</script>");
		} else {
			echo("<p>".$Context->GetDefinition("CurrentApplicationMode")." <strong>".$Mode."</strong></p>
				<p><a href=\"debug.php?PageAction=ToggleDebug\">".$Context->GetDefinition("SwitchApplicationMode")."</a></p>
				<p><a href=\"./\">".$Context->GetDefinition("BackToApplication")."</a>");
		}
	echo("</div>
</body>
</html>");
?>
