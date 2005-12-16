<?php
// Note: This file is included from the library/Vanilla.Control.CategoryList.php class.

$CategoryList = "<div class=\"Title\">".$this->Context->PageTitle."</div>";
$Category = $this->Context->ObjectFactory->NewObject($this->Context, "Category");
$FirstRow = 1;
while ($Row = $this->Context->Database->GetRow($this->Data)) {
   $Category->Clear();
   $Category->GetPropertiesFromDataSet($Row);
   $Category->FormatPropertiesForDisplay();
   $CategoryList .= "<dl class=\"Category".($Category->Blocked?" BlockedCategory":" UnblockedCategory").($FirstRow?" FirstCategory":"")." Category_".$Category->CategoryID."\">
      <dt class=\"DataItemLabel CategoryNameLabel\">".$this->Context->GetDefinition("Category")."</dt>
      <dd class=\"DataItem CategoryName\"><a href=\"".GetUrl($this->Context->Configuration, "index.php", "", "CategoryID", $Category->CategoryID)."\">".$Category->Name."</a></dd>
      <dt class=\"ExtendedMetaItemLabel CategoryInformationLabel CategoryDescriptionLabel\">Description</dt>
      <dd class=\"ExtendedMetaItem CategoryInformation CategoryDescription\">".$Category->Description."</dd>
      <dt class=\"MetaItemLabel CategoryInformationLabel DiscussionCountLabel\">".$this->Context->GetDefinition("Discussions")."</dt>
      <dd class=\"MetaItem CategoryInformation DiscussionCount\">".$Category->DiscussionCount."</dd>";
   if ($this->Context->Session->UserID > 0) {
      $CategoryList .= "
         <dt class=\"MetaItemLabel CategoryInformationLabel CategoryMonitorLabel\">".$this->Context->GetDefinition("Options")."</dt>
         <dd class=\"MetaItem CategoryInformation CategoryMonitor\">";
         if ($Category->Blocked) {
            $CategoryList .= "<a href=\"Javascript:ToggleCategoryBlock(".$Category->CategoryID.", 0);\">".$this->Context->GetDefinition("UnblockCategory")."</a>";
         } else {
            $CategoryList .= "<a href=\"Javascript:ToggleCategoryBlock(".$Category->CategoryID.", 1);\">".$this->Context->GetDefinition("BlockCategory")."</a>";
         }
         $CategoryList .= "</dd>
      ";
   }
   $CategoryList .= "</dl>\n";
   $FirstRow = 0;
}
echo($CategoryList);
?>