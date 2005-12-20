<?php
// Note: This file is included from the library/Vanilla.Control.Account.php class.

echo("<div class=\"Account\">");

   $this->CallDelegate("PreUsernameRender");
   
   if ($this->User->DisplayIcon != "") {
      echo("<h1 class=\"AccountWithIcon\"><span class=\"AccountIcon\" style=\"background-image:url('".$this->User->DisplayIcon."')\">&nbsp;</span>");
   } else {
      echo("<h1>");
   }
      echo($this->User->Name
   ."</h1>
   <small>".$this->User->Role."</small>
   <div class=\"AccountBody\">");
      if ($this->User->RoleDescription != "") echo("<blockquote>".$this->User->RoleDescription."</blockquote>");
      if ($this->User->Picture != "" && $this->User->Permission("PERMISSION_HTML_ALLOWED")) echo("<div class=\"Picture\">".GetImage($this->User->Picture,"","","Picture","")."</div>");
      echo("<dl>");
         if ($this->Context->Configuration["USE_REAL_NAMES"]) {
            echo("<dt>".$this->Context->GetDefinition("RealName")."</dt>
            <dd>".(($this->User->ShowName || $this->Context->Session->User->Permission("PERMISSION_EDIT_USERS")) ? ReturnNonEmpty($this->User->FullName) : "n/a")."</dd>");
         }
         echo("<dt>".$this->Context->GetDefinition("Email")."</dt>
         <dd>".(($this->Context->Session->UserID > 0 && ($this->User->UtilizeEmail || $this->Context->Session->User->Permission("PERMISSION_EDIT_USERS"))) ? GetEmail($this->User->Email) : "n/a")."</dd>
         <dt>".$this->Context->GetDefinition("AccountCreated")."</dt>
         <dd>".TimeDiff($this->Context, $this->User->DateFirstVisit, mktime())."</dd>
         <dt>".$this->Context->GetDefinition("LastActive")."</dt>
         <dd>".TimeDiff($this->Context, $this->User->DateLastActive, mktime())."</dd>
         <dt>".$this->Context->GetDefinition("VisitCount")."</dt>
         <dd>".$this->User->CountVisit."</dd>
         <dt>".$this->Context->GetDefinition("DiscussionsStarted")."</dt>
         <dd>".$this->User->CountDiscussions."</dd>
         <dt>".$this->Context->GetDefinition("CommentsAdded")."</dt>
         <dd>".$this->User->CountComments."</dd>");
         
         $this->CallDelegate("PostBasicPropertiesRender");
         
         if ($this->Context->Session->User->Permission("PERMISSION_IP_ADDRESSES_VISIBLE")) {
            echo("
            <dt>".$this->Context->GetDefinition("LastKnownIp")."</dt>
            <dd>".$this->User->RemoteIp."</dd>
            ");
         }
         
         if (count($this->User->Attributes) > 0) {
            $AttributeCount = count($this->User->Attributes);
            for ($i = 0; $i < $AttributeCount; $i++) {
               echo("
                  <dt>".$this->User->Attributes[$i]["Label"]."</dt>
                  <dd>".FormatHyperlink($this->User->Attributes[$i]["Value"])."</dd>
               ");
            }
         }
         
         $this->CallDelegate("PostAttributesRender");
         
      echo("</dl>
   </div>
</div>");

?>