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
               echo("<li class=\"SortListItem MovableSortListItem\" id=\"item_".$r->RoleID."\">
                  <a class=\"SortRemove\" href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=RoleRemove&RoleID=".$r->RoleID)."\"><img src=\"".$this->Context->StyleUrl."images/btn.remove.gif\" height=\"15\" width=\"15\" border=\"0\" alt=\"".$this->Context->GetDefinition("Remove")."\" /></a>
                  <a class=\"SortEdit\" href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Role&RoleID=".$r->RoleID)."\">".$this->Context->GetDefinition("Edit")."</a>
                  ".$r->RoleName."
               </li>");
            }
         }
      echo("</ul>
      <script type=\"text/javascript\" language=\"javascript\">
      // <![CDATA[
         Sortable.create('SortRoles', {dropOnEmpty:true, tag:'li', constraint: 'vertical', ghosting: false, onUpdate: function() {new Ajax.Updater('LoadStatus', './ajax/sortroles.php', {onComplete: function(request) { new Effect.Highlight('SortRoles',{startcolor:'#ffff99'});}, parameters:Sortable.serialize('SortRoles', {tag:'li', name:'RoleID'}), evalScripts:true, asynchronous:true})}});
      // ]]>
      </script>
      <div class=\"FormLink\"><a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Role")."\">".$this->Context->GetDefinition("CreateANewRole")."</a></div>
   </div>
</div>");
?>