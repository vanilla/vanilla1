<?php
// Note: This file is included from the library/Utility.Control.Head.php class.

$HeadString = "<".chr(63)."xml version=\"1.0\" encoding=\"utf-8\"".chr(63).">
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<base href=\"".$this->Context->Configuration["BASE_URL"]."\" />
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-ca\">
   <head>
      <title>".$this->Context->Configuration["APPLICATION_TITLE"]." - ".$this->Context->PageTitle."</title>
      <link rel=\"shortcut icon\" href=\"/favicon.ico\" />";
      if (is_array($this->StyleSheets)) {
         $StyleSheetCount = count($this->StyleSheets);
         $i = 0;
         for ($i = 0; $i < $StyleSheetCount; $i++) {
            $HeadString .= "\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->StyleSheets[$i]["Sheet"]."\"".($this->StyleSheets[$i]["Media"] == ""?"":" media=\"".$this->StyleSheets[$i]["Media"]."\"")." />";
         }
      }
      if (is_array($this->Scripts)) {
         $ScriptCount = count($this->Scripts);
         $i = 0;
         for ($i = 0; $i < $ScriptCount; $i++) {
            $HeadString .= "\r\n<script type=\"text/javascript\" src=\"".$this->Scripts[$i]."\"></script>";
         }
      }
      if (is_array($this->Strings)) {
         $StringCount = count($this->Strings);
         $i = 0;
         for ($i = 0; $i < $StringCount; $i++) {
            $HeadString .= $this->Strings[$i];
         }
      }
echo($HeadString . "</head>
   <body".$this->Context->BodyAttributes.">");
?>