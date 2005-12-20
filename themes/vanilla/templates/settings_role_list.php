<?php
// Note: This file is included from the library/Vanilla.Control.RoleForm.php control.

echo("<div class=\"SettingsForm\">
   ".$this->Get_Warnings()."
   <h1>".$this->Context->GetDefinition("RoleManagement")."</h1>
   <div class=\"Form\" id=\"Roles\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("RoleReorderNotes")."</div>
      <ul class=\"SortList\" id=\"SortRoles\">");
         if ($this->RoleData) {
            $r = $this->Context->ObjectFactory->NewContextObject($this->Context, "Role");
            
            while ($Row = $this->Context->Database->GetRow($this->RoleData)) {
               $r->Clear();
               $r->GetPropertiesFromDataSet($Row);
               $r->FormatPropertiesForDisplay();
               echo("<li class=\"SortListItem".($this->Context->Session->User->Permission("PERMISSION_SORT_ROLES") ? " MovableSortListItem" : "")."\" id=\"item_".$r->RoleID."\">");
                  if ($this->Context->Session->User->Permission("PERMISSION_REMOVE_ROLES")) echo("<a class=\"SortRemove\" href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=RoleRemove&amp;RoleID=".$r->RoleID)."\"><img src=\"".$this->Context->StyleUrl."images/btn.remove.gif\" height=\"15\" width=\"15\" border=\"0\" alt=\"".$this->Context->GetDefinition("Remove")."\" /></a>");
                  if ($this->Context->Session->User->Permission("PERMISSION_EDIT_ROLES")) echo("<a class=\"SortEdit\" href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Role&amp;RoleID=".$r->RoleID)."\">".$this->Context->GetDefinition("Edit")."</a>");
                  echo($r->RoleName."
               </li>");
            }
         }
      echo("</ul>");
      if ($this->Context->Session->User->Permission("PERMISSION_SORT_ROLES")) {
         echo("<script type=\"text/javascript\" language=\"javascript\">
         // <![CDATA[
            Sortable.create('SortRoles', {dropOnEmpty:true, tag:'li', constraint: 'vertical', ghosting: false, onUpdate: function() {new Ajax.Updater('LoadStatus', './ajax/sortroles.php', {onComplete: function(request) { new Effect.Highlight('SortRoles',{startcolor:'#ffff99'});}, parameters:Sortable.serialize('SortRoles', {tag:'li', name:'RoleID'}), evalScripts:true, asynchronous:true})}});
         // ]]>
         </script>");
      }
      if ($this->Context->Session->User->Permission("PERMISSION_ADD_ROLES")) echo("<div class=\"FormLink\"><a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Role")."\">".$this->Context->GetDefinition("CreateANewRole")."</a></div>");
   echo("</div>
</div>");
?>