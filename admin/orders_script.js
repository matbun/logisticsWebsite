/**
 * Wrapper function to call orders loaders functions
 *
 *@return nothig
 */
    
function loadOrders(){
	window.alert("i");
	// ajax request for today orders
	var today = new Date();
	var today_compact = today.toISOString().slice(0, 10); // format yyyy-mm-dd
	sendRequest("get_orders.php", "date=" + today_compact, todayOrders);

	// ajax request for today orders
	var tomorrow = new Date(today);
	tomorrow.setDate(today.getDate() + 1);
	var tomorrow_compact = tomorrow.toISOString().slice(0, 10);
	sendRequest("get_orders.php", "date=" + tomorrow_compact, tomorrowOrders);

	//debug
	window.alert("today:" + today_compact + "\ntomorrow: " + tomorrow_compact);
}

/**
 * Send an ajax request to the server
 *
 * @param url is the url of the server side script to execute
 * @param data is the data passed to the script
 * @èaram responseConsumer is the function called to elaborate the response
 * @return nothing
 *
 
function sendRequest(url, data, responseConsumer){
	// ajax connection object
	var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	loginReq.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			responseConsumer(this.responseText);
		}
	}

	// prepare request
	request.open("POST", url, true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	// send POST request
	loginReq.send(data);
}

/**
 * Elaborates today orders and organizes them in a table
 *
 * @param response is the json data in the response from the server
 * @return nothing
 *
function todayOrders(response){
	rx = JSON.parse(response);
}
*/