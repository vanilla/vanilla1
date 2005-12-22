<?php
// Note: This file is included from the library/Vanilla.Control.DiscussionForm.php class.
echo("<div class=\"Title\">".$this->Title."</div>
<div class=\"DiscussionForm\">"
   .$this->Get_PostBackForm("frmPostDiscussion", "post", "post.php")
   .$this->Get_Warnings()
   ."<dl>");
   if ($this->Context->Configuration["USE_CATEGORIES"]) {
      $this->CallDelegate("DiscussionForm_PreCategoryRender");
      echo("<dt class=\"CategoryInputLabel\">".$this->Context->GetDefinition("SelectDiscussionCategory")."</dt>
      <dd class=\"CategoryInput\">".$cs->Get()."</dd>");
   } else {
      echo("<input type=\"hidden\" name=\"CategoryID\" value=\"".$cs->aOptions[0]["IdValue"]."\" />");
   }
   $this->CallDelegate("DiscussionForm_PreTopicRender");
   echo("<dt class=\"TopicInputLabel\">".$this->Context->GetDefinition(($Discussion->DiscussionID == 0?"EnterYourDiscussionTopic":"EditYourDiscussionTopic"))."</dt>
      <dd class=\"TopicInput\"><input type=\"text\" name=\"Name\" class=\"DiscussionBox\" maxlength=\"100\" value=\"".$Discussion->Name."\" /></dd>");

   if ($this->Context->Configuration["ENABLE_WHISPERS"] && $Discussion->DiscussionID == 0) {   
      echo("<dt class=\"WhisperInputLabel\">".$this->Context->GetDefinition("WhisperYourCommentsTo")."</dt>
      <dd class=\"WhisperInput\">
         <input autocomplete=\"off\" id=\"WhisperUsername\" name=\"WhisperUsername\" type=\"text\" value=\"".FormatStringForDisplay($Discussion->WhisperUsername, 0)."\" class=\"WhisperBox\" maxlength=\"20\" /><div class=\"Autocomplete\" id=\"WhisperUsername_Choices\"></div><script type=\"text/javascript\">new Ajax.Autocompleter('WhisperUsername', 'WhisperUsername_Choices', './ajax/getusers.php', {paramName: \"Search\"})</script>
      </dd>");
   }

   $this->CallDelegate("DiscussionForm_PreCommentRender");
   
   echo("<dt class=\"CommentInputLabel\">".$this->Context->GetDefinition(($Discussion->DiscussionID == 0?"EnterYourComments":"EditYourComments"))."
         <a id=\"CommentBoxController\" href=\"Javascript:ToggleCommentBox('".$this->Context->GetDefinition("SmallInput")."', '".$this->Context->GetDefinition("BigInput")."');\" onmouseover=\"window.status='';return true;\">".$this->Context->GetDefinition($this->Context->Session->User->Preference("ShowLargeCommentBox")?"SmallInput":"BigInput")."</a>
      </dt>
      <dd class=\"CommentInput\">
         <textarea name=\"Body\" class=\"".($this->Context->Session->User->Preference("ShowLargeCommentBox")?"LargeCommentBox":"SmallCommentBox")."\" id=\"CommentBox\">".$Discussion->Comment->Body."</textarea>"
         .$this->GetPostFormatting($Discussion->Comment->FormatType)
      ."</dd>
   </dl>");
   
   $this->CallDelegate("DiscussionForm_PreButtonsRender");
   
   echo("<div class=\"FormButtons DiscussionButtons\">
      <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition(($Discussion->DiscussionID > 0) ? "SaveYourChanges" : "StartYourDiscussion")."\" class=\"Button SubmitButton\" onclick=\"Wait(this, '".$this->Context->GetDefinition("Wait")."');\" />");
      $this->CallDelegate("DiscussionForm_PostSubmitRender");
      echo("<a href=\"".(!$this->IsPostBack?"javascript:history.back();":"./")."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
   </div>");
   
   $this->CallDelegate("DiscussionForm_PostButtonsRender");
   
   echo("</form>
</div>");
?>