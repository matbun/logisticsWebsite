/**
 * Wrapper function to call orders loaders functions
 *
 *@return nothig
 */
    
function loadOrders(){
	// ajax request for today orders
	var today = new Date();
	var today_compact = today.toISOString().slice(0, 10); // format yyyy-mm-dd
	sendRequest("get_orders.php", "date=" + today_compact, todayOrders);

	// ajax request for today orders
	var tomorrow = new Date(today);
	tomorrow.setDate(today.getDate() + 1);
	var tomorrow_compact = tomorrow.toISOString().slice(0, 10);
	sendRequest("get_orders.php", "date=" + tomorrow_compact, tomorrowOrders);
}


/**
 * Send an ajax request to the server
 *
 * @param url is the url of the server side script to execute
 * @param data is the data passed to the script
 * @èaram responseConsumer is the function called to elaborate the response
 * @return nothing
 */
 
function sendRequest(url, dataString, responseConsumer){
	// ajax connection object
	var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	request.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			responseConsumer(this.responseText);
		}
	};

	// prepare request
	request.open("POST", url, true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	// send POST request
	request.send(dataString);
}


/**
 * Elaborates today orders and organizes them in a table
 *
 * @param response is the json data in the response from the server
 * @return nothing
 */
function todayOrders(response){
	// element id where insert the orders table
	var parent_id = 'today_orders';

	//parse response
	rx = JSON.parse(response);
	if(rx.length > 0){
		// create table header
		var table = document.createElement('TABLE');
		table.border = 1;
		document.getElementById(parent_id).appendChild(table);	

		//header row
		var headers = [
						"Nome cliente", 
					   	"indirizzo"
					  ];
		tr_header = tableRow(headers,true);
		table.appendChild(tr_header);


		// create table rows
		for(let i = 0; i < rx.length; i++){
			//create a new row for the table
			row = tableRow([
							rx[i].client_name, 
						   	rx[i].client_address
						   ]);
			table.appendChild(row);
		}
	}
}


/**
 * Elaborates tommorw orders and organizes them in a table
 *
 * @param response is the json data in the response from the server
 * @return nothing
 */

function tomorrowOrders(){

}


/**
 * Creates a new row element (for a table) which may be an header or a simple row.
 * In inserts the elements passed as param.
 *
 * @param elements are the elements to insert in the row
 * @param header is true if it's an header row
 * @return row element
 */
    
function tableRow(elements,header=false){
	// header or simple row?
	if(header)
		var col_tag = 'TH';
	else
		var col_tag = 'TD';

	// row
	var tr = document.createElement('TR');

	// header cols
	for (let element of elements){
		th = document.createElement(col_tag);
		th.innerHTML = element;
		tr.appendChild(th);
	}

	return tr;
}