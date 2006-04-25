function HidePanel(AjaxUrl) {
   var Panel = document.getElementById("Panel");
   var Body = document.getElementById("Body");
   var HidePanel = document.getElementById("HidePanel");
   var HiddenPanel = document.getElementById("HiddenPanel");
   
   Panel.style.display = "none";
   Body.style.marginLeft = "0";
   Body.style.borderLeft = "0";
   
   HidePanel.style.display = "none";
   HiddenPanel.style.display = "inline";
   
   var Parameters = "Type=HidePanel&Switch=1";
   var dm = new DataManager();
	dm.RequestCompleteEvent = PanelComplete;
	dm.RequestFailedEvent = HandleFailure;
	dm.LoadData(AjaxUrl+"?"+Parameters);
}

function PanelComplete(Request) {
	// Don't do anything
}

function RevealPanel(AjaxUrl) {
   var Panel = document.getElementById("Panel");
   var Body = document.getElementById("Body");
   var HidePanel = document.getElementById("HidePanel");
   var HiddenPanel = document.getElementById("HiddenPanel");
   
   Panel.style.display = "inline";
   Body.style.marginLeft = "216px";
   Body.style.borderLeft = "1px solid #ddd";
   
   HidePanel.style.display = "inline";
   HiddenPanel.style.display = "none";
   
   var Parameters = "Type=HidePanel&Switch=0";
   var dm = new DataManager();
	dm.RequestCompleteEvent = PanelComplete;
	dm.RequestFailedEvent = HandleFailure;
	dm.LoadData(AjaxUrl+"?"+Parameters);
}