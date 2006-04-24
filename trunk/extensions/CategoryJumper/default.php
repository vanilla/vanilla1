<?php
/*
Extension Name: Category Jumper
Extension Url: http://lussumo.com/docs/
Description: A simple plugin that places a category dropdown in the control panel on the discussion page allowing for fast jumping between categories
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/

Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Vanilla.
Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code for Vanilla is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com


You should cut & paste these language definitions into your conf/your_language.php file
(replace "your_language" with your chosen language, of course):
*/
$Context->Dictionary['AllUnblockedCategories'] = 'All unblocked categories';

function GetCategoryJumper(&$Context) {
   $CategoryManager = $Context->ObjectFactory->NewContextObject($Context, 'CategoryManager');
   $CategoryData = $CategoryManager->GetCategories(0, 1);
   if (!$CategoryData) {
      return '';      
   } else {
      $Select = $Context->ObjectFactory->NewObject($Context, 'Select');
      $Select->Name = 'CategoryID';
      $Select->SelectedValue = ForceIncomingInt('CategoryID', 0);
      if ($Context->Configuration['URL_BUILDING_METHOD'] == 'mod_rewrite') {
         $Select->Attributes = "onchange=\"document.location='".$Context->Configuration['WEB_ROOT']."'+(this.options[this.selectedIndex].value > 0 ? this.options[this.selectedIndex].value+'/' : 'discussions/');\"";
      } else {
         $Select->Attributes = "onchange=\"document.location='".$Context->Configuration['WEB_ROOT']."'+(this.options[this.selectedIndex].value > 0 ? '?CategoryID='+this.options[this.selectedIndex].value : '');\"";
      }
      $Select->Attributes .= " id='CategoryJumper'";
      
      $Select->AddOption(0, $Context->GetDefinition('AllUnblockedCategories'));         
      $LastBlocked = -1;
      $cat = $Context->ObjectFactory->NewObject($Context, 'Category');
      while ($Row = $Context->Database->GetRow($CategoryData)) {
         $cat->Clear();
         $cat->GetPropertiesFromDataSet($Row);
         if ($cat->Blocked != $LastBlocked && $LastBlocked != -1) {
            $Select->AddOption('-1', '---', " disabled=\"true\"");
         }
         $Select->AddOption($cat->CategoryID, $cat->Name);
         $LastBlocked = $cat->Blocked;
      }         
      return '<h2>'.$Context->GetDefinition('Categories').'</h2>'
         .$Select->Get();
   }
}

if (in_array($Context->SelfUrl, array('index.php','comments.php')) && $Configuration['USE_CATEGORIES']) {
   $Panel->AddString(GetCategoryJumper($Context), 5);
   $Head->AddStyleSheet('extensions/CategoryJumper/style.css');
}
?>