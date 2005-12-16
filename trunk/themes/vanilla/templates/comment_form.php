<?php
// Note: This file is included from the library/Vanilla.Control.DiscussionForm.php class.
echo("<div class=\"Title CommentInputTitle\">".$this->Title."</div>
<div class=\"CommentForm\">"
   .$this->Get_PostBackForm("frmPostComment", "post", "post.php")
   .$this->Get_Warnings()
   ."<dl>");
   
      if ($this->Context->Configuration["ENABLE_WHISPERS"]) {   
         echo("<dt class=\"WhisperInputLabel\">".$this->Context->GetDefinition("WhisperYourCommentsTo")."</dt>
         <dd class=\"WhisperInput\">
            <input autocomplete=\"off\" id=\"WhisperUsername\" name=\"WhisperUsername\" type=\"text\" value=\"".FormatStringForDisplay($Comment->WhisperUsername, 0)."\" class=\"WhisperBox\" maxlength=\"20\" /><div class=\"Autocomplete\" id=\"WhisperUsername_Choices\"></div><script type=\"text/javascript\">new Ajax.Autocompleter('WhisperUsername', 'WhisperUsername_Choices', './ajax/getusers.php', {paramName: \"Search\"})</script>
         </dd>");
      }
   
      $this->CallDelegate("CommentForm_PreCommentsInputRender");
      
      echo("<dt class=\"CommentInputLabel\">
         ".$this->Context->GetDefinition("EnterYourComments")."
         <a id=\"CommentBoxController\" href=\"Javascript:ToggleCommentBox('".$this->Context->GetDefinition("SmallInput")."', '".$this->Context->GetDefinition("BigInput")."');\">".($this->Context->Session->User->Preference("ShowLargeCommentBox")?$this->Context->GetDefinition("SmallInput"):$this->Context->GetDefinition("BigInput"))."</a>
      </dt>
      <dd class=\"CommentInput\">
         <textarea name=\"Body\" class=\"".($this->Context->Session->User->Preference("ShowLargeCommentBox")?"LargeCommentBox":"SmallCommentBox")."\" id=\"CommentBox\">".$Comment->Body."</textarea>"
         .$this->GetPostFormatting($Comment->FormatType)
      ."</dd>");
      
      $this->CallDelegate("CommentForm_PostCommentsInputRender");
      
   echo("</dl>");
   
   $this->CallDelegate("CommentForm_PreButtonsRender");
   
   echo("<div class=\"FormButtons CommentButtons\">"
      ."<input type=\"button\" name=\"btnSave\" value=\"".($Comment->CommentID > 0?$this->Context->GetDefinition("SaveYourChanges"):$this->Context->GetDefinition("AddYourComments"))."\" class=\"Button SubmitButton\" onclick=\"SubmitForm('frmPostComment', this, '".$this->Context->GetDefinition("Wait")."');\" />");
      $this->CallDelegate("CommentForm_PostSubmitRender");
      if ($this->PostBackAction == "SaveComment" || ($this->PostBackAction == "" && $Comment->CommentID > 0)) {
         if ($this->Comment->DiscussionID > 0) {
            echo("<a href=\"".GetUrl($this->Context->Configuration, "comments.php", "", "DiscussionID", $this->Comment->DiscussionID)."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>");
         } else {
            echo("<a href=\"".GetUrl($this->Context->Configuration, "index.php")."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>");
         }
      }
   echo("</div>");
   
   $this->CallDelegate("CommentForm_PostButtonsRender");
   
   echo("</form>			
</div>");
?>