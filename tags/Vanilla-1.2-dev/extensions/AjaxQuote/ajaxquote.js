// customize
var ajaxqoute_format = 'Html';  // 'Html' or 'BBCode' 
var ajaxqoute_errormessage = "Some Error occured while retriving qoute!\nIt's either server problem or comment doesn't exist anymore.";
//

var g_com_author, q_qoute, g_aq;

function ajaxquote(baseurl, com_id, com_author)
{
	if(!document.getElementById("CommentBox")) return true;
	
	g_com_author=com_author;
	var dm = new DataManager();
	
	if((g_aq=document.getElementById('AjaxQuote_' + com_id))){
	g_aq.className = 'HideProgress';	
	q_qoute = g_aq.innerHTML;
	g_aq.innerHTML = '&nbsp;';
	}
	
	if(document.getElementById("BBBar") && ajaxqoute_format == 'BBCode') document.getElementById("BBBar").style.display = '';
	if(document.getElementById("Radio_"+ajaxqoute_format)) document.getElementById("Radio_"+ajaxqoute_format).checked = true;
	
	dm.RequestCompleteEvent = _ajaxquote;
	dm.RequestFailedEvent = _ajaxquote_failure;
	dm.LoadData(baseurl + 'extensions/AjaxQuote/ajax.php?CommentID=' + com_id);

return false;
}

function _ajaxquote(request)
{
	var input = document.getElementById("CommentBox");
	
	if(g_aq){ 
  g_aq.className = '';	
	g_aq.innerHTML = q_qoute;
	}
	if(!request.responseText || request.responseText=='ERROR'){ _ajaxquote_failure(); return false;}
	
	if(ajaxqoute_format == 'BBCode')
	ajaxquote_insert('[quote][cite] '+g_com_author+':[/cite]'+ request.responseText+'[/quote]','');
	else
	ajaxquote_insert('<blockquote><cite> '+g_com_author+':</cite>'+ request.responseText+'</blockquote>','');	

return false;
}


function _ajaxquote_failure(){
document.getElementById("CommentBox").focus(); alert(ajaxqoute_errormessage);
return false;
}


function ajaxquote_insert(aTag, eTag) {
  var input = document.getElementById("CommentBox");
  input.focus();
  /* f