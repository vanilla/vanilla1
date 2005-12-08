<?php
class Clipping {
   var $ClippingID;
   var $UserID;
   var $Label;
   var $Contents;
   
   function Clipping() {
      $this->Clear();
   }
   
   function Clear() {
      $this->ClippingID = 0;
      $this->UserID = 0;
      $this->Label = "";
      $this->Contents = "";
   }
   
   function FormatContentsForJavascript() {
      $this->Contents = str_replace("\"","\\\"",$this->Contents);
      $this->Contents = str_replace("\r\n","{newline}",$this->Contents);
      $this->Contents = str_replace("\t","{tab}",$this->Contents);
   }
   
   function FormatPropertiesForDatabaseInput() {
      $this->Label = FormatStringForDatabaseInput($this->Label);
      $this->Contents = FormatStringForDatabaseInput($this->Contents);
      $this->Contents = eregi_replace("&lt;textarea&gt;", "<textarea>", $this->Contents);
      $this->Contents = eregi_replace("&lt;//textarea&gt;", "</textarea>", $this->Contents);
   }
   
   function FormatPropertiesForDisplay($IncludeContents = "0") {
      $IncludeContents = ForceBool($IncludeContents, 0);
      $this->Label = FormatStringForDisplay($this->Label);
      if ($IncludeContents) {
         $this->Contents = htmlspecialchars($this->Contents);
      }
   }
   
   function GetPropertiesFromDataSet($DataSet) {
      $this->ClippingID = ForceInt(@$DataSet["ClippingID"],0);
      $this->UserID = ForceInt(@$DataSet["UserID"],0);
      $this->Label = ForceString(@$DataSet["Label"],"");
      $this->Contents = ForceString(@$DataSet["Contents"],"");
   }
   
   function GetPropertiesFromForm() {
      $this->ClippingID = ForceIncomingInt("ClippingID", 0);
      $this->Label = ForceIncomingString("Label", "");
      $this->Contents = ForceIncomingString("Contents", "");
   }
}
?>