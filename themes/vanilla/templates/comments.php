<?php
// Note: This file is included from the library/Vanilla.Control.CommentGrid.php class.

$CommentList = "";
if ($this->Context->WarningCollector->Count() > 0) {
   $CommentList .= "<div class=\"ErrorContainer\">
      <div class=\"ErrorTitle\">".$this->Context->GetDefinition("ErrorTitle")."</div>"
      .$this->Context->WarningCollector->GetMessages()
   ."</div>";
} else {
   $PageDetails = $this->pl->GetPageDetails($this->Context);
   $PageList = $this->pl->GetNumericList();
   
   // Format the discussion information
   $this->Discussion->ForceNameSpaces($this->Context->Configuration);

   $CommentList .= "<a class=\"PageJump Bottom\" href=\"#pgbottom\">".$this->Context->GetDefinition("BottomOfPage")."</a>"
      ."<div class=\"Title\">";
      if ($this->Context->Configuration["USE_CATEGORIES"]) $CommentList .= "<a href=\"./?CategoryID=".$this->Discussion->CategoryID."\">".$this->Discussion->Category."</a>: ";
      $CommentList .= DiscussionPrefix($this->Context->Configuration, $this->Discussion)." ";
      if ($this->Discussion->WhisperUserID > 0) {
         $CommentList .= $this->Discussion->WhisperUsername.": ";
      }
      $CommentList .= $this->Discussion->Name
      ."</div>"
      .$PageList
      ."<div class=\"PageDetails\">".$PageDetails."</div>";

   $Comment = $this->Context->ObjectFactory->NewContextObject($this->Context, "Comment");
   $RowNumber = 0;
   $CommentID = 0;
   
   // Define the current user's permissions and preferences
   // (small optimization so they don't have to be checked every loop):
   $PERMISSION_EDIT_COMMENTS = $this->Context->Session->User->Permission("PERMISSION_EDIT_COMMENTS");
   $PERMISSION_HIDE_COMMENTS = $this->Context->Session->User->Permission("PERMISSION_HIDE_COMMENTS");
   
   while ($Row = $this->Context->Database->GetRow($this->CommentData)) {
      $RowNumber++;			
      $Comment->Clear();
      $Comment->GetPropertiesFromDataSet($Row, $this->Context->Session->UserID);
      $ShowHtml = $Comment->FormatPropertiesForDisplay();
      $this->DelegateParameters["ShowHtml"] = &$ShowHtml;
      
      $CommentList .= "<a name=\"Comment_".$Comment->CommentID."\"></a>
         <a name=\"Item_".$RowNumber."\"></a>
         <div class=\"Comment ".$Comment->Status.($RowNumber==1?" FirstComment":"")."\" id=\"Comment_".$Comment->CommentID."\">";
         if ($Comment->Deleted) {
             $CommentList .= "<div class=\"ErrorContainer CommentHidden\">
               <div class=\"Error\">".$this->Context->GetDefinition("CommentHiddenOn")." ".date("F jS Y \a\\t g:ia", $Comment->DateDeleted)." ".$this->Context->GetDefinition("By")." ".$Comment->DeleteUsername.".</div>
            </div>";
         }
         $ShowIcon = 0;
         if ($Comment->AuthIcon != "") $ShowIcon = 1;
         $CommentList .= "<div class=\"CommentAuthor".($ShowIcon?" CommentAuthorWithIcon":"")."\">";
         if ($ShowIcon)  $CommentList .= "<span class=\"CommentIcon\" style=\"background-image:url('".$Comment->AuthIcon."')\"></span>";
         $CommentList .= "<a href=\"account.php?u=".$Comment->AuthUserID."\">".$Comment->AuthUsername."</a></div>";
         if ($Comment->WhisperUserID > 0) {
            $CommentList .= "<div class=\"CommentWhisper\">".$this->Context->GetDefinition("To")." ";
            if ($Comment->WhisperUserID == $this->Context->Session->UserID && $Comment->AuthUserID == $this->Context->Session->UserID) {
               $CommentList .= $this->Context->GetDefinition("Yourself");
            } elseif ($Comment->WhisperUserID == $this->Context->Session->UserID) {
               $CommentList .= $this->Context->GetDefinition("You");
            } else {
               $CommentList .= $Comment->WhisperUsername;
            }
            $CommentList .= "</div>";
         }
         $CommentList .= "<div class=\"CommentTime\">".TimeDiff($this->Context, $Comment->DateCreated);
         if ($Comment->DateEdited != "") $CommentList .= " <em>".$this->Context->GetDefinition("Edited")."</em>";
      $CommentList .= "</div>
      <div class=\"CommentOptions\">";
         $this->DelegateParameters["Comment"] = &$Comment;
         $this->DelegateParameters["CommentList"] = &$CommentList;
         $CommentList .= $this->CallDelegate("PreCommentOptionsRender");
         if ($this->Context->Session->UserID > 0) {
            if ($Comment->AuthUserID == $this->Context->Session->UserID || $PERMISSION_EDIT_COMMENTS) {
               if ((!$this->Discussion->Closed && $this->Discussion->Active) || $PERMISSION_EDIT_COMMENTS) $CommentList .= "<div class=\"CommentEdit\"><a href=\"post.php?CommentID=".$Comment->CommentID."\">".$this->Context->GetDefinition("edit")."</a></div>";
            }
            if ($PERMISSION_HIDE_COMMENTS) $CommentList .= "<div class=\"CommentHide\"><a href=\"javascript:ManageComment('".($Comment->Deleted?"0":"1")."', '".$this->Discussion->DiscussionID."', '".$Comment->CommentID."', '".$this->Context->GetDefinition("ShowConfirm")."', '".$this->Context->GetDefinition("HideConfirm")."');\">".$this->Context->GetDefinition($Comment->Deleted?"Show":"Hide")."</a></div>";
         }
         $this->DelegateParameters["CommentList"] = &$CommentList;
         $this->CallDelegate("PostCommentOptionsRender");
         $CommentList .= "</div>";
         if ($Comment->AuthRoleDesc != "") $CommentList .= "<div class=\"CommentNotice\">".$Comment->AuthRoleDesc."</div>";
         $CommentList .= "<div class=\"CommentBody\" id=\"CommentContents_".$Comment->CommentID."\">".$Comment->Body."</div>";
         if ($Comment->WhisperUserID > 0 && $Comment->WhisperUserID == $this->Context->Session->UserID) $CommentList .= "<div class=\"WhisperBack\"><a href=\"Javascript:WhisperBack('".$Comment->DiscussionID."', '".str_replace("'", "\'", $Comment->AuthUsername)."');\">".$this->Context->GetDefinition("WhisperBack")."</a></div>";
      $CommentList .= "</div>";
   }
   if (@$PageList && @$PageDetails) {
      $CommentList .= $PageList
      ."<div class=\"PageDetails\">".$PageDetails."</div>";
   }
   if ($this->ShowForm) {
      $CommentList .= "<a name=\"addcomments\"></a>";
      // <div class=\"Title AddCommentsTitle\">".$this->Context->GetDefinition("AddYourComments")."</div>";
   }
}
echo($CommentList);
?>