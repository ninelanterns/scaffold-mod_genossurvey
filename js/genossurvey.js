
/*
 * AjaxObject is a hypothetical object that encapsulates the transaction
 *     request and callback logic.
 *
 * handleSuccess( ) provides success case logic
 * handleFailure( ) provides failure case logic
 * processResult( ) displays the results of the response from both the
 * success and failure handlers
 * call( ) calling this member starts the transaction request.
 */

var AjaxObject = {

	handleSuccess:function(o) {
		// This member handles the success response
		// and passes the response object o to AjaxObject's
		// processResult member.
                console.debug(o);
                var oResults = eval("(" + o.responseText + ")");
		this.processResult(oResults);
	},

	handleFailure:function(o) {
            console.debug('Error:');
            console.debug(o);
	},

	processResult:function(oResults) {
            if (oResults.length == 0){
                return;
            }

            var count = 0;

            for (var i in oResults){
                count++;
            }

            document.write(oResults);
	},

	startRequest:function(url) {
            console.debug('Connecting to '+url);
            url = "/mod/genossurvey/pix/icon.gif";
	    YAHOO.util.Connect.asyncRequest('GET', url, callback, '');
/*
            var req = new XMLHttpRequest();
            req.open('POST', url, true);
            req.send();
            console.debug(req);
*/
/*
            if(req.status == 0)
                dump(req.responseText);
            }
*/
        }
};

/*
 * Define the callback object for success and failure
 * handlers as well as object scope.
 */
var callback = {
	success:AjaxObject.handleSuccess,
	failure:AjaxObject.handleFailure,
	scope: AjaxObject,
        cache: false
};
