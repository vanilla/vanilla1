<?php
// Note: This file is included from the library/Vanilla.Control.CategoryForm.php control.

echo("<div class=\"SettingsForm\">
   ".$this->Get_Warnings()."
   <h1>".$this->Context->GetDefinition("CategoryManagement")."</h1>
   <div class=\"Form\" id=\"Categories\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("CategoryReorderNotes")."</div>
      <ul class=\"SortList\" id=\"SortCategories\">");
         if ($this->CategoryData) {
            $c = $this->Context->ObjectFactory->NewObject($this->Context, "Category");
            while ($Row = $this->Context->Database->GetRow($this->CategoryData)) {
               $c->Clear();
               $c->GetPropertiesFromDataSet($Row);
               $c->FormatPropertiesForDisplay();
               echo("<li class=\"SortListItem".($this->Context->Session->User->Permission("PERMISSION_SORT_CATEGORIES") ? " MovableSortListItem":"")."\" id=\"item_".$c->CategoryID."\">");
                  if ($this->Context->Session->User->Permission("PERMISSION_REMOVE_CATEGORIES")) echo("<a class=\"SortRemove\" href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=CategoryRemove&amp;CategoryID=".$c->CategoryID)."\"><img src=\"".$this->Context->StyleUrl."images/btn.remove.gif\" height=\"15\" width=\"15\" border=\"0\" alt=\"".$this->Context->GetDefinition("Remove")."\" /></a>");
                  if ($this->Context->Session->User->Permission("PERMISSION_EDIT_CATEGORIES")) echo("<a class=\"SortEdit\" href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Category&amp;CategoryID=".$c->CategoryID)."\">".$this->Context->GetDefinition("Edit")."</a>");
                  echo($c->Name."
               </li>");
            }
         }
      echo("</ul>");
      if ($this->Context->Session->User->Permission("PERMISSION_SORT_CATEGORIES")) {
         echo("
         <script type=\"text/javascript\" language=\"javascript\">
         // <![CDATA[
            Sortable.create('SortCategories', {dropOnEmpty:true, tag:'li', constraint: 'vertical', ghosting: false, onUpdate: function() {new Ajax.Updater('LoadStatus', './ajax/sortcategories.php', {onComplete: function(request) { new Effect.Highlight('SortCategories',{startcolor:'#ffff99'});}, parameters:Sortable.serialize('SortCategories', {tag:'li', name:'CategoryID'}), evalScripts:true, asynchronous:true})}});
         // ]]>
         </script>");
      }
      echo("<div class=\"FormLink\"><a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Category")."\">".$this->Context->GetDefinition("CreateNewCategory")."</a></div>
   </div>
</div>");
?>