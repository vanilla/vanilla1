<?php
/*
Extension Name: Preview Post
Extension Url: mailto:sirnot@gmail.com
Description: Allows users to view a preview of what their post will look like.
Version: 1.2
Author: SirNot
Author Url: mailto:sirnot@gmail.com
*/

if(realpath($_SERVER['SCRIPT_FILENAME']) == realpath(__FILE__) && !defined('EXTENSION_IN_PREVIEW')) //a preview?
{
	define('EXTENSION_IN_PREVIEW', 		1); //so we don't reinclude ourselves
	
	$Text = isset($_POST['Data']) ? $_POST['Data'] : '';
	$Type = isset($_POST['Type']) ? $_POST['Type'] : '';
	
	if($Text != '' && $Type != '')
	{
		include('../appg/settings.php');
		include('../conf/settings.php');
		include('../appg/init_ajax.php');
		
		//TODO: either emulate or use the Comment class instead of passing 0
		if(get_magic_quotes_gpc()) $Text = stripslashes($Text);
		$Text = $Context->StringManipulator->Parse($Text, 0, $Type, FORMAT_STRING_FOR_DISPLAY);
		if($Type == 'Text') $Text = str_replace(array("\r", "\n", "\r\n"), '<br>', $Text);
		
		echo($Text);
	}
}
else if(in_array($Context->SelfUrl, array('post.php', 'comments.php')))
{
	$Context->Dictionary['PostPreview'] = 'Post Preview';
	
	$Head->AddScript('./extensions/PreviewPost/preview.js');
	$Head->AddStyleSheet('./extensions/PreviewPost/preview.css');
	
	$Context->AddToDelegate('DiscussionForm', 'DiscussionForm_PostSubmitRender', 'PostForm_PreviewPostButton');
	$Context->AddToDelegate('DiscussionForm', 'DiscussionForm_PostButtonsRender', 'PostForm_PreviewPostField');
	$Context->AddToDelegate('DiscussionForm', 'CommentForm_PostSubmitRender', 'PostForm_PreviewPostButton');
	$Context->AddToDelegate('DiscussionForm', 'CommentForm_PostButtonsRender', 'PostForm_PreviewPostField');
	
	function PostForm_PreviewPostButton(&$DiscussionForm)
	{
		global $Context;
		
		echo(
			'<input name="btnPreview" value="Preview" class="Button SubmitButton" type="button" onclick="ShowPreview(\'frmPost'.
			($Context->SelfUrl == 'comments.php' ? 'Comment' : 'Discussion').'\', \'./extensions/'.basename(__FILE__).'\')" />'
		);
	}
	
	function PostForm_PreviewPostField(&$DiscussionForm)
	{
		global $Context;
		
		echo('
			<fieldset id="PrePreviewPost">
				<legend>'.$Context->GetDefinition('PostPreview').'</legend>
				<div class="CommentBody" id="PreviewPost"></div>
			</fieldset>');
	}
}

?>