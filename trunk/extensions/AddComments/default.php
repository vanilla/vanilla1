<?php
/*
Extension Name: Add Comments
Extension Url: http://lussumo.com/docs/
Description: Adds the "Add Comments" form to the page for unauthenticated users, along with a username and password input, allowing users who have not yet signed in to do so and post a message at the same time.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/

Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Vanilla.
Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code for Vanilla is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/

if (in_array($Context->SelfUrl, array('comments.php', 'post.php'))) {
   
   // 1. Make sure that the form gets displayed if necessary
      $Context->AddToDelegate('CommentGrid',
            'Constructor',
            'CommentGrid_ShowCommentForm');
         
      // Attach to the Constructor Delegate of the CommentGrid control
      function CommentGrid_ShowCommentForm(&$CommentGrid) {
         if ($CommentGrid->ShowForm == 0
            && $CommentGrid->Context->Session->UserID == 0
            && ($CommentGrid->pl->PageCount == 1 || $CommentGrid->pl->PageCount == $CommentGrid->CurrentPage)
            && ((!$CommentGrid->Discussion->Closed && $CommentGrid->Discussion->Active))
            && $CommentGrid->CommentData ) $CommentGrid->ShowForm = 1;			
      }
      
   // 2. Add the username & password inputs to the comment form
      if ($Context->Session->UserID <= 0) {
         $Context->AddToDelegate('DiscussionForm',
            'CommentForm_PreWhisperInputRender',
            'DiscussionForm_AddCredentialInputs');
         
         function DiscussionForm_AddCredentialInputs(&$DiscussionForm) {
            echo '<table border="0" cellpadding="0" cellspacing="0">
               <tr>
                  <td class="CredentialsLabel LabelUsername">'.$DiscussionForm->Context->GetDefinition('Username').'</td>
                  <td class="CredentialsLabel LabelPassword">'.$DiscussionForm->Context->GetDefinition('Password').'</td>
               </tr>
               <tr>
                  <td class="CredentialsInput InputUsername"><input type="text" name="Username" value="'.FormatStringForDisplay(ForceIncomingString('Username', '')).'" /></td>
                  <td class="CredentialsInput InputPassword"><input type="password" name="Password" value="'.FormatStringForDisplay(ForceIncomingString('Password', '')).'" /></td>
               </tr>
            </table>';
         }
      }
      
   // 3. Make sure that the inputs are styled properly
      $Head->AddStyleSheet('extensions/AddComments/style.css');
      
   // 4. If the form has been posted back with the username and password,
   // make sure to validate the user before the comment is saved
      if ($Context->Session->UserID <= 0) {
         $Context->AddToDelegate('DiscussionForm',
            'PostLoadData',
            'DiscussionForm_SignInUser');
            
         function DiscussionForm_SignInUser(&$DiscussionForm) {
            if ($DiscussionForm->PostBackAction == 'SaveComment') {
               $Username = ForceIncomingString('Username', '');
               $Password = ForceIncomingString('Password', '');
               $UserManager = $DiscussionForm->Context->ObjectFactory->NewContextObject($DiscussionForm->Context, 'UserManager');
               if (!$UserManager->ValidateUserCredentials($Username, $Password, 0)) {
                  $DiscussionForm->PostBackAction = 'SaveCommentFailed';
                  $DiscussionForm->Context->Session->UserID = -1;
                  
                  $DiscussionForm->Comment->Clear();
                  $DiscussionForm->Comment->GetPropertiesFromForm();
                  $DiscussionForm->Comment->DiscussionID = $DiscussionForm->DiscussionID;
                  $dm = $DiscussionForm->DelegateParameters['DiscussionManager'];
                  $DiscussionForm->Discussion = $dm->GetDiscussionById($DiscussionForm->Comment->DiscussionID);
                  $DiscussionForm->Comment->FormatPropertiesForDisplay(1);
               }
            }
         }
      }
}

?>