<?php
// Note: This file is included from the library/Vanilla.Control.Menu.php class.

echo("<div class=\"SiteContainer\">");
	$this->CallDelegate("PostSiteContainerRender");
	echo("<a name=\"pgtop\"></a>
   <div id=\"LoadStatus\" style=\"display: none;\">Loading...</div>
   <div class=\"Session\">");
   if ($this->Context->Session->UserID > 0) {
      echo($this->Context->GetDefinition("SignedInAs")." ".$this->Context->Session->User->Name." (<a href=\"leave.php\">".$this->Context->GetDefinition("SignOut")."</a>)");
   } else {
      echo($this->Context->GetDefinition("NotSignedIn")." (<a href=\"signin.php\">".$this->Context->GetDefinition("SignIn")."</a>)");
   }
   echo("</div>");
	$this->CallDelegate("PreHeadRender");	
   echo("<div class=\"Head\">
      <div class=\"Logo\">".$this->Context->Configuration["BANNER_TITLE"]."</div>
      <ul id=\"MenuForum\">");
      while (list($Key, $Tab) = each($this->Tabs)) {
   		echo("<li><a class=\"".$this->TabClass($this->CurrentTab, $Tab["Value"])." ".$Tab["CssClass"]."\" href=\"".$Tab["Url"]."\" ".$Tab["Attributes"].">".$Tab["Text"]."</a></li>");
      }
      echo("</ul>
   </div>");
	$this->CallDelegate("PreBodyRender");	
   echo("<div class=\"Body\">");
?>