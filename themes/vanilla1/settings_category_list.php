<?php
// Note: This file is included from the library/Vanilla.Control.CategoryForm.php control.

echo '<div id="Form" class="Account CategoryList">
   <fieldset>
      <legend>'.$this->Context->GetDefinition('CategoryManagement').'</legend>'
      .$this->Get_Warnings()
      .'<form method="get" action="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'">
      <input type="hidden" name="PostBackAction" value="Category" />
      <p>'.$this->Context->GetDefinition('CategoryReorderNotes').'</p>
      <ul class="SortList" id="SortCategories">';
         if ($this->CategoryData) {
            $c = $this->Context->ObjectFactory->NewObject($this->Context, 'Category');
            while ($Row = $this->Context->Database->GetRow($this->CategoryData)) {
               $c->Clear();
               $c->GetPropertiesFromDataSet($Row);
               $c->FormatPropertiesForDisplay();
               echo '<li class="SortListItem'.($this->Context->Session->User->Permission('PERMISSION_SORT_CATEGORIES') ? ' MovableSortListItem':'').'" id="item_'.$c->CategoryID.'">';
                  if ($this->Context->Session->User->Permission('PERMISSION_REMOVE_CATEGORIES')) echo '<a class="SortRemove" href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl, '', '', '', '', 'PostBackAction=CategoryRemove&amp;CategoryID='.$c->CategoryID).'">&nbsp;</a>';
                  if ($this->Context->Session->User->Permission('PERMISSION_EDIT_CATEGORIES')) echo '<a class="SortEdit" href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl, '', '', '', '', 'PostBackAction=Category&amp;CategoryID='.$c->CategoryID).'">'.$this->Context->GetDefinition('Edit').'</a>';
                  echo$c->Name.'
               </li>';
            }
         }
      echo '</ul>
      <div id="SortResult" style="display: none;"></div>';
      
      if ($this->Context->Session->User->Permission('PERMISSION_SORT_CATEGORIES')) {
         echo("
         <script type=\"text/javascript\" language=\"javascript\">
         // <![CDATA[
            Sortable.create('SortCategories', {dropOnEmpty:true, tag:'li', constraint: 'vertical', ghosting: false, onUpdate: function() {new Ajax.Updater('SortResult', './ajax/sortcategories.php', {onComplete: function(request) { new Effect.Highlight('SortCategories',{startcolor:'#ffff99'});}, parameters:Sortable.serialize('SortCategories', {tag:'li', name:'CategoryID'}), evalScripts:true, asynchronous:true})}});
         // ]]>
         </script>");
      }
      echo '<div class="Submit">
         <input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('CreateNewCategory').'" class="Button SubmitButton NewCategoryButton" />
         <a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
      </div>
   </form>
   </fieldset>
</div>';
?>