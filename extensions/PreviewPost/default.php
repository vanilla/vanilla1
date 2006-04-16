<?php
/*
Extension Name: Preview Post
Extension Url: mailto:sirnot@gmail.com
Description: Allows users to view a preview of what their post will look like.
Version: 1.2
Author: SirNot
Author Url: N/A

You should cut & paste these language definitions into your
conf/your_language.php file (replace "your_language" with your chosen language,
of course):
*/
$Context->Dictionary['PostPreview'] = 'Preview';
$Context->Dictionary['PreviewPost'] = 'Preview Post';
/*
// Moved to ajax file in PreviewPost folder by mosullivan on Jan 12, 2006
 
$PreviewSelf = substr(str_replace("\\", '/', __FILE__), strlen(__FILE__)-strlen($_SERVER['PHP_SELF']));
if(!strcasecmp($PreviewSelf, $_SERVER['PHP_SELF']) && !defined('EXTENSION_IN_PREVIEW')) //a preview?
{
	define('EXTENSION_IN_PREVIEW', 		1); //so we don't reinclude ourselves
	
	$Text = isset($_POST['Data']) ? $_POST['Data'] : '';
	$Type = isset($_POST['Type']) ? $_POST['Type'] : '';
	
	if($Text != '' && $Type != '')
	{
		include('../appg/settings.php');
		include('../conf/settings.php');
		include('../appg/init_ajax.php');
		
		class EmulatedUserInfoClass
		{
			var $Context, $AuthUserID, $AuthUsername;
			
			function EmulatedUserInfoClass(&$Context)
			{
				$this->Context = &$Context;
				$this->AuthUserID = $this->Context->Session->User->UserID;
				$this->AuthUsername = $this->Context->Session->User->Name;
			}
		}
		
		$EmulatedUserInfo = new EmulatedUserInfoClass($Context);
		
		if(get_magic_quotes_gpc()) $Text = stripslashes($Text);
		$Text = $Context->StringManipulator->Parse($Text, $EmulatedUserInfo, $Type, FORMAT_STRING_FOR_DISPLAY);
		if($Type == 'Text') $Text = str_replace(array("\r\n", "\r", "\n"), '<br />', $Text);
		
		echo($Text);
	}
}
else
*/
if(in_array($Context->SelfUrl, array('post.php', 'comments.php')))
{
	//print_r($Context);
	$Head->AddScript('extensions/PreviewPost/preview.js');
	$Head->AddStyleSheet('extensions/PreviewPost/preview.css');
	
	$Context->AddToDelegate('DiscussionForm', 'DiscussionForm_PostSubmitRender', 'DiscussionForm_PreviewPostButton');
	$Context->AddToDelegate('DiscussionForm', 'DiscussionForm_PostButtonsRender', 'PostForm_PreviewPostField');
	$Context->AddToDelegate('DiscussionForm', 'CommentForm_PostSubmitRender', 'CommentForm_PreviewPostButton');
	$Context->AddToDelegate('DiscussionForm', 'CommentForm_PostButtonsRender', 'PostForm_PreviewPostField');
	
	function DiscussionForm_PreviewPostButton(&$DiscussionForm)
	{
		echo(
			'<input name="btnPreview" value="'.$DiscussionForm->Context->GetDefinition("PreviewPost").'" class="Button SubmitButton PreviewButton" type="button" onclick="ShowPreview(\''.$this->Context->Configuration['BASE_URL'].'extensions/PreviewPost/ajax.php\')" />'
		);
	}
	
	function CommentForm_PreviewPostButton(&$CommentForm)
	{
		echo(
			'<input name="btnPreview" value="'.$CommentForm->Context->GetDefinition("PreviewPost").'" class="Button SubmitButton PreviewButton" type="button" onclick="ShowPreview(\''.$this->Context->Configuration['BASE_URL'].'extensions/PreviewPost/ajax.php\')" />'
		);
	}
	
	function PostForm_PreviewPostField(&$Form)
	{
		global $Context;
		
		echo('
			<fieldset id="PrePreviewPost">
				<legend>'.$Context->GetDefinition('PostPreview').'</legend>
				<div class="CommentBody Comment" id="PreviewPost"></div>
			</fieldset>');
	}
}

?>