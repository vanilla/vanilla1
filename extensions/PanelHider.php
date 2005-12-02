<?php
/*
Extension Name: Panel Hider
Extension Url: http://lussumo.com/docs/
Description: Allows the control panel to be hidden. This functions correctly with the default Vanilla style. Compatible with Vanilla 0.9.3
Version: 1.2
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
*/

/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/
if (in_array($Context->SelfUrl, array("account.php", "categories.php", "comments.php", "index.php", "post.php", "search.php", "settings.php")) && $Context->Session->UserID > 0) {
   class PanelHider extends Control {
      
      function PanelHider(&$Context) {
         $this->Context = &$Context;
      }
      
      function Render() {
         echo("<div id=\"HiddenPanel\"><a href=\"javascript:RevealPanel();\">&nbsp;</a></div>
         <div id=\"HidePanel\"><a href=\"javascript:HidePanel();\">&nbsp;</a></div>");
      }
   }
   
   if (@$Head) {
      $Head->AddScript($Context->StyleUrl."js/panelhider.js");
      if ($Context->Session->User->Preference("HidePanel")) {
         $Head->AddStyleSheet($Context->StyleUrl."css/panelhider.hidden.css", "screen");
      } else {
         $Head->AddStyleSheet($Context->StyleUrl."css/panelhider.visible.css", "screen");
      }
      $Head->AddStyleSheet($Context->StyleUrl."panelhider.handheld.css", "handheld");      
      $PanelHider = $Context->ObjectFactory->NewContextObject($Context, "PanelHider");
      $Page->AddControl("Panel_Render", $PanelHider);
   }
}

?>