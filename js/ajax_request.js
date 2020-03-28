class AjaxRequest{
	/**
	 * 
	 *
	 * @method constructor
	 * @param type: GET or POST
	 * @param url: page where to send the request
	 * @param callbackFunct: call back function on the response
	 * @return nothing
	 */
	constructor(type, url, callbackFunct){
		this.request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		this.request.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				callbackFunct(this.responseText);
			}
		};

		// prepare request
		this.request.open(type, url, true);
		this.request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	}
	
	/**
	 * Send the request 
	 *
	 * @method 
	 * @param dataString: data in dtring format: var1=VALUE1&...&varn=VALUEn
	 * @return 
	 */
	sendRequest(dataString){
		this.request.send(dataString);
	}
}