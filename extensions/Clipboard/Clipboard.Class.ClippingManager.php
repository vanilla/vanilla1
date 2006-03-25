<?php
class ClippingManager {
   var $Name;				// The name of this class
   var $Context;			// The context object that contains all global objects (database, error manager, warning collector, session, etc)
   
   function ClippingManager(&$Context) {
      $this->Name = "ClippingManager";
      $this->Context = &$Context;
   }

   function GetClippingById($ClippingID) {
      $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
      $s->SetMainTable("Clipping", "c");
      $s->AddSelect(array("ClippingID", "UserID", "Label", "Contents"), "c");
      $s->AddWhere('c', "ClippingID", '', $ClippingID, "=");
      $s->AddWhere('c', "UserID", '', $this->Context->Session->UserID, "=");
      $ResultSet = $this->Context->Database->Select($s, $this->Name, "GetClippingById", "An error occurred while attempting to retrieve the requested Clipping.");
      $Clipping = false;
      while ($rows = $this->Context->Database->GetRow($ResultSet)) {
         $Clipping = $this->Context->ObjectFactory->NewObject($this->Context, "Clipping");
         $Clipping->GetPropertiesFromDataSet($rows);
      }
      return $Clipping;
   }
   
   function GetClippingsByUserID($UserID) {
      $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
      $s->SetMainTable("Clipping", "c");
      $s->AddSelect(array("ClippingID", "UserID", "Label", "Contents"), "c");
      $s->AddWhere('c', "UserID", '', $UserID, "=");
      return $this->Context->Database->Select($s, $this->Name, "GetClippingsByUserID", "An error occurred while attempting to retrieve Clipping items.");
   }
   
   function RemoveClipping($ClippingID) {
      $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
      $s->SetMainTable("Clipping", "c");
      $s->AddWhere('c', "ClippingID", '', $ClippingID, "=");
      $s->AddWhere('c', "UserID", '', $this->Context->Session->UserID, "=");
      $this->Context->Database->Delete($s, $this->Name, "RemoveClipping", "An error occurred while removing the Clipping item.");
      return true;
   }
   
   function SaveClipping($Clipping) {
      $Clipping->FormatPropertiesForDatabaseInput();
      if ($Clipping->Label == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrClippingLabelRequired"));
      if ($Clipping->Contents == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrClippingContents"));
      if ($this->Context->WarningCollector->Count() == 0) {
         $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
         $s->SetMainTable("Clipping", "c");
         $s->AddFieldNameValue("Label", $Clipping->Label);
         $s->AddFieldNameValue("Contents", $Clipping->Contents);
         if ($Clipping->ClippingID > 0) {
            $s->AddWhere('c', "ClippingID", '', $Clipping->ClippingID, "=");
            $s->AddWhere('c', "UserID", '', $this->Context->Session->UserID, "=");
            $this->Context->Database->Update($s, $this->Name, "SaveClipping", "An error occurred while saving your Clipping item.");
         } else {
            $s->AddFieldNameValue("UserID", $this->Context->Session->UserID);
            $this->Context->Database->Insert($s, $this->Name, "SaveClipping", "An error occurred while creating a new item for your Clipping.");
         }
      }
      return $this->Context->WarningCollector->Iif();
   }
}
?>
