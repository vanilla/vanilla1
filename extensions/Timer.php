<?php
/*
Extension Name: Timer
Extension Url: http://lussumo.com/docs/
Description: A simple plugin that drops a timer on every page of Vanilla to display how long a page took to load
Version: 1.0
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

if (in_array($Context->SelfUrl, array("index.php", "categories.php", "comments.php", "search.php", "post.php", "account.php", "settings.php"))) {
   class Timer extends Control {
      var $Context;
      var $Start;
      var $Stop;
      function Timer(&$Context) {
         $this->Start = $this->DetailedTimestamp();
         $this->Context = &$Context;
      }
      function Render() {
         $this->Stop = $this->DetailedTimestamp();
         $ExecutionTime = ForceFloat((float)$this->Stop - (float)$this->Start, 0, 4);
         echo("<div class=\"Timer\">The page was delivered in ".FormatPlural($ExecutionTime, $ExecutionTime." seconds", $ExecutionTime." seconds")."</div>");
      }
      function DetailedTimestamp($MicroTime = "") {
         if ($MicroTime == "") $MicroTime = microtime();
         list($usec, $sec) = explode(" ", $MicroTime);
         return ((float)$usec + (float)$sec);
      } 
   }
   $Timer = $Context->ObjectFactory->NewContextObject($Context, "Timer");
   $Page->AddControl("Page_Unload", $Timer);
}
?>