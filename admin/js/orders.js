/**
 * Wrapper function to call orders loaders functions
 *
 * @return nothig
 */
    
function loadOrders(){
	// ajax request for today orders
	var today = new Date();
	var today_compact = new Date(today.getTime() - (today.getTimezoneOffset() * 60000)).toISOString().slice(0, 10); // format yyyy-mm-dd
	sendRequest("get_orders.php", "date=" + today_compact, todayOrders);

	// ajax request for today orders
	var tomorrow = new Date(today);
	tomorrow.setDate(today.getDate() + 1);
	var tomorrow_compact = new Date(tomorrow.getTime() - (tomorrow.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);
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

class OrdersTable{
	constructor(tableId){
		this.table = document.createElement('TABLE');
		this.thead = this.table.createTHead();
		this.tbody = this.table.createTBody();
		
		// table metadata
		this.table.id = tableId; //used to add rows in the body
		this.table.border = 1;

		// header building
		this.headers = [
						"Nome cliente", 
					   	"Indirizzo cliente",
					   	"Commerciante",
					   	"Dettagli ordine",
					   	"Approfondisci" //button
					  ];
		var hRow = this.thead.insertRow(0);
		for (let h of this.headers){
			// insert the new cell at the end
			var cell = document.createElement('TH');
			cell.innerHTML = h;
			hRow.appendChild(cell);
		}

		this.colsNumber = this.headers.length;

	}

	addRow(rowId, rowData){
		var newRow = this.tbody.insertRow(-1);

		/* This id locally indetifies ONE specific order. 
		   It's used to query the server.*/
		newRow.id = rowId;

		/* Show row index
		var cell = newRow.insertCell(-1);
		cell.innerHTML = newRow.rowIndex;
		*/

		for (var elem of rowData){
			var cell = newRow.insertCell(-1);
			cell.innerHTML = elem;
		}

		// button: "more info"
		var btn = document.createElement('BUTTON');
			
			//btn.id = rowId;
			
			btn.innerHTML = "Più info";

			var thisTable = this; //orders table object
			var thisRow = newRow; //row object
			var data = "<table border=1><tr><td>hh</td><td>lkjlj</td><td>lkjlkj</td><td>lkjjljk</td></tr></table>sdasda asdad asdas dad asa das das das das das da da da dad <br> aadsasda as dasdadada as adad asda das da ada sd asd adad asd as a das dsa d asda d s"; //data to be added
			// add onclick event
			btn.onclick = function() {
				OrdersTable.showDetails(thisTable, thisRow, data, this);
			};

		var btnCell = newRow.insertCell(-1);
		btnCell.appendChild(btn);

	}


	static showDetails(ordTable, uppeRow, completeInfo, thisButton){

		// insert a row below the one of interest (at position rowNum)
		var detailsRow = ordTable.table.insertRow(uppeRow.rowIndex + 1);

		//insert a single td
		var uniqueCell = detailsRow.insertCell(0);
		uniqueCell.colSpan = ordTable.colsNumber;

		// insert details
		uniqueCell.innerHTML = completeInfo;
		
		// update button info
		var onClickShowEvent = thisButton.onclick;
		thisButton.innerHTML = "Meno info";
		thisButton.onclick = function(){ OrdersTable.hideDetails(ordTable, detailsRow, thisButton, onClickShowEvent)};
	}

	static hideDetails(ordTable, detailsRow, thisButton, onClickShowEvent){
		ordTable.table.deleteRow(detailsRow.rowIndex);

		// reset button info
		thisButton.innerHTML = "Più info";
		thisButton.onclick = onClickShowEvent;
	}

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
	var table_id = parent_id + '_table';

	//parse response
	var rx = JSON.parse(response);
	if(rx.length > 0){
		// orders table object
		var ordersTab = new OrdersTable(table_id);

		//insert rows
		for(let i = 0; i < rx.length; i++){
			//create a new row for the table
			ordersTab.addRow(rx[i].order_number,
							[
							rx[i].client_name, 
						   	rx[i].client_address,
						   	rx[i].retailer_name,
						   	rx[i].order_details
						    ]
						   );
		}


		// insert table in the page
		document.getElementById(parent_id).appendChild(ordersTab.table);
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
		var col = document.createElement(col_tag);
		col.innerHTML = element;
		tr.appendChild(col);
	}

	return tr;
}

function moreInfo(id){
	var table = document.getElementById('today_orders_table');
	row = table.insertRow(id+1);
	row.innerHTML = "asdadasd";

	/*
	var div = document.createElement('TR');
	var p = document.createElement('p');
	p.innerHTML = "Button " + id + " pressed dfkhsdkfhsdkfhskjafhskhfshfksdfhkshf weihf sifhsdk fskhfdsfhskjhf ";

	div.appendChild(p);


	//append to the table row
	var row = document.getElementById(id);
	row.appendChild(div);
	*/
}