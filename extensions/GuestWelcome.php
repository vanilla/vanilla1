<?php
/*
Extension Name: Guest Welcome Message
Extension Url: http://lussumo.com/docs/
Description: Adds a welcome message to the panel if the person viewing the forum doesn't have an active session.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/

Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Vanilla.
Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code for Vanilla is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com

You should cut & paste these language definitions into your
conf/your_language.php file (replace "your_language" with your chosen language,
of course):
*/
$Context->Dictionary["GuestWelcomeTitle"] = "Welcome, Guest";
$Context->Dictionary["GuestWelcomeBody"] = "<p>Did you know that there is a whole world of functionality you're not seeing? If you have an account, <a href=\"".$Configuration["SIGNIN_URL"]."\">sign in now</a>.</p>
   <p>If you don't have an account, <a href=\"".GetUrl($Configuration, "people.php", "", "", "", "", "PostBackAction=ApplyForm")."\">apply for one now</a>.</p>";

if (in_array($Context->SelfUrl, array("account.php", "categories.php", "comments.php", "index.php", "search.php")) && $Context->Session->UserID == 0) {
   $String = "<div class=\"PanelTitle\">".$Context->GetDefinition("GuestWelcomeTitle")."</div>
   <div class=\"PanelInformation\" id=\"GuestInfo\">".$Context->GetDefinition("GuestWelcomeBody")."</div>";
   $Panel->AddString($String, 10);   
}
?>