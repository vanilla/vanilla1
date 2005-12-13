<?php
// Note: This file is included from the controls/Common.Controls.php file
// in the Menu class.

echo("<div class=\"SiteContainer\">
   <a name=\"pgtop\"></a>
   <div id=\"LoadStatus\" style=\"display: none;\">Loading...</div>
   <div class=\"Session\">");
   if ($this->Context->Session->UserID > 0) {
      echo($this->Context->GetDefinition("SignedInAs")." ".$this->Context->Session->User->Name." (<a href=\"".$this->Context->Configuration["SIGNOUT_URL"]."\">".$this->Context->GetDefinition("SignOut")."</a>)");
   } else {
      echo($this->Context->GetDefinition("NotSignedIn")." (<a href=\"".$this->Context->Configuration["SIGNIN_URL"]."\">".$this->Context->GetDefinition("SignIn")."</a>)");
   }
   echo("</div>
   <div class=\"Head\">
      <div class=\"Logo\">".$this->Context->Configuration["BANNER_TITLE"]."</div>
      <div id=\"MenuForum\">");
         $TabCount = count($this->Tabs);
         $i = 0;
         while (list($Key, $Tab) = each($this->Tabs)) {
            echo("<a class=\"".$this->TabClass($this->CurrentTab, $Tab["Value"])." ".$Tab["CssClass"]."\" href=\"".$Tab["Url"]."\" ".$Tab["Attributes"].">".$Tab["Text"]."</a>");
            $i++;
            if ($i != $TabCount) echo("&nbsp;&nbsp;:&nbsp;&nbsp;");
         }
      echo("</div>
   </div>
   <div class=\"Body\">");
?>