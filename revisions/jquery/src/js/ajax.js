
function DataManager() {
	// Properties
	var self = this;

	this.RequestCompleteEvent = self.RequestCompleteEvent = function(r){};
	this.RequestFailedEvent = self.RequestFailedEvent = function(r){};
	// Param is a property where you can store information which you may require
	// once the datahandler is complete. For example, the translation of the
	// word "complete" for alerting that something is finished. It can be
	// referenced from within the RequestCompleteEvent or RequestFailedEvent
	// events with this.Param.
	this.Param = self.Param = null;

	this.complete = function(Request, Status) {
		jQuery.extend(this, self);
		if (Status === 'success') {
			self.RequestCompleteEvent.call(this, Request);
		} else {
			self.RequestFailedEvent.call(this, Request);
		}
	}

	this.LoadData = function(DataSource) {
		jQuery.ajax({
			'complete': self.complete,
			'global': false,
			'url': DataSource
		});
	}
}

function HandleFailure(Request) {
	alert("Failed: ("+Request.status+") "+Request.statusText);
}