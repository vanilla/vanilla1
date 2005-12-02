<?php
/*
Extension Name: Legends
Extension Url: http://lussumo.com/docs/
Description: Adds legends to the panel for the discussion, search, and category pages.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
*/

/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/
if (in_array($Context->SelfUrl, array("index.php", "categories.php")) && $Context->Session->UserID > 0 && $Context->Session->User->Preference("ShowAppendices")) {
	$Panel->AddString("<h2>".$Context->GetDefinition("Legend")."</h2>
		<ul class=\"LinkedList Legend\">
			<li class=\"Legend NewComments\">".$Context->GetDefinition("NewComments")."</li>
			<li class=\"Legend NoNewComments\">".$Context->GetDefinition("NoNewComments")."</li>
		</ul>");
} elseif ($Context->SelfUrl == "categories.php" && $Context->Session->UserID > 0 && $Context->Session->User->Preference("ShowAppendices")) {
	$Panel->AddString("<h2>".$Context->GetDefinition("Legend")."</h2>
		<ul class=\"LinkedList Legend\">
			<li class=\"Legend UnblockedCategory\">".$Context->GetDefinition("UnblockedCategory")."</li>
			<li class=\"Legend BlockedCategory\">".$Context->GetDefinition("BlockedCategory")."</li>
   	</ul>");
} elseif ($Configuration["ENABLE_WHISPERS"] && $Context->SelfUrl == "comments.php" && $Context->Session->UserID > 0 && $Context->Session->User->Preference("ShowAppendices")) {
	$Panel->AddString("<h2>".$Context->GetDefinition("Legend")."</h2>
      <ul class=\"LinkedList Legend\">
         <li class=\"Legend WhisperFrom\">".$Context->GetDefinition("YouWhispered")."</li>
         <li class=\"Legend WhisperTo\">".$Context->GetDefinition("WhisperedToYou")."</li>
      </ul>");
}

?>