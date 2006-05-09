var Request;

function CreateRequestPOST(url, params, func)
{
	Request = false;
	if(window.XMLHttpRequest)
	{
		Request = new XMLHttpRequest();
		if(Request.overrideMimeType) Request.overrideMimeType('text/xml');
	}
	else if(window.ActiveXObject)
	{
		try
		{
			Request = new ActiveXObject('Msxml2.XMLHTTP');
		}
		catch(error)
		{
			try
			{
				Request = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch(error) {}
		}
	}
	
	if(!Request) return false;
	
	Request.onreadystatechange = func;
	Request.open('POST', url, true);
	Request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	Request.setRequestHeader('Content-length', params.length);
	Request.setRequestHeader('Connection', 'close');
	Request.send(params);
	
	return true;
}

function ShowPreviewInternal()
{
	if(Request.readyState == 4)
	{
		if(Request.status == 200)
		{
			document.getElementById('PreviewPost').innerHTML = Request.responseText;
			var frm = GetPostForm();
			if (frm) frm.btnPreview.value = 'Refresh Preview';
		}
	}
}

function GetPostForm() {
	var frm = document.getElementById('frmPostDiscussion');
	if (!frm) frm = document.getElementById('frmPostComment');
	return frm;
}

function ShowPreview(file)
{
	var text, type, i, f;
	var frm = GetPostForm();
	if (frm) {	
		text = (encodeURIComponent) ? encodeURIComponent(document[FormName].Body.value) : 
			escape(document[FormName].Body.value.replace(/\+/g, '%2B'));
		if(!frm.FormatType.length) type = frm.FormatType.value;
		else
		{
			for(i = f = 0; i < frm.FormatType.length; i++)
			{
				if(frm.FormatType[i].checked)
				{
					f = 1;
					break;
				}
			}
			if(!f) i = 0;
			type = escape(frm.FormatType[i].value);
		}
		
		if(!CreateRequestPOST(file, 'Data='+text+'&Type='+type, ShowPreviewInternal))
			document.getElementById('PreviewPost').innerHTML = '[An error occured when attempting to connect to the server]';
		
		document.getElementById('PrePreviewPost').style.display = 'block';
	}
}