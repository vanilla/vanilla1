var Request, FormName;

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
			document[FormName].btnPreview.value = 'Refresh Preview';
		}
	}
}

function ShowPreview(F, file)
{
	var text, type, i, f;
	FormName = F;
	
	text = escape(document[FormName].Body.value);
	if(!document[FormName].FormatType.length) type = document[FormName].FormatType.value;
	else
	{
		for(i = f = 0; i < document[FormName].FormatType.length; i++)
		{
			if(document[FormName].FormatType[i].checked)
			{
				f = 1;
				break;
			}
		}
		if(!f) i = 0;
		type = escape(document[FormName].FormatType[i].value);
	}
	
	if(!CreateRequestPOST(file, 'Data='+text+'&Type='+type, ShowPreviewInternal))
		document.getElementById('PreviewPost').innerHTML = '[An error occured when attempting to connect to the server]';
	
	document.getElementById('PrePreviewPost').style.display = 'block';
}