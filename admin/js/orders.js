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

/**
 * Class to manage a table to visualize orders.
 * Each order is summarized on a row
 * 
 * @class OrdersTable
 */
    
class OrdersTable{
	/**
	 * COnstructor class
	 *
	 * @method constructor
	 * @param tableId identifies the table in the web page.
	 */
	    
	constructor(tableId, headers=null){
		this.table = document.createElement('TABLE');
		this.thead = this.table.createTHead();
		this.tbody = this.table.createTBody();
		
		// table metadata
		this.table.id = tableId; 
		this.table.border = 1;

		// header building
		this.headers = headers==null? [
						"Nome cliente", 
					   	"Indirizzo cliente",
					   	"Commerciante",
					   	"Dettagli ordine",
					   	"Approfondisci" // button
					  ] : headers;
		var hRow = this.thead.insertRow(0);
		for (let h of this.headers){
			// insert the new cell at the end
			var cell = document.createElement('TH');
			cell.innerHTML = h;
			hRow.appendChild(cell);
		}

		this.colsNumber = this.headers.length;
	}

	/**
	 * Insert a new order in the table. It consists of multiple rows.
	 *
	 * @method addOrder
	 * @param orderId is the unique ID to identify and order in the server.
	 * @param orderData is the dataset of each order.
	 */
	    
	addOrder(orderId, orderData){
		// MAIN INFO: brief order summarization. According to headers
		var mainData = [
						orderData.client_name + " " + orderData.client_surname,
						orderData.client_address,
						orderData.retailer_name,
						orderData.order_details
					];

		// MAIN ROW creation (summarizes the order info)
		var mainRow = this.tbody.insertRow(-1);

		/* This id locally indetifies ONE specific order. 
		   It's used to query the server.*/
		mainRow.id = "main_" + orderId;

		// insert elements in the row
		for (var elem of mainData){
			var cell = mainRow.insertCell(-1);
			cell.innerHTML = elem;
		}

		// create button: "more info"
		var btn = document.createElement('BUTTON');			
			btn.innerHTML = "Più info";

			// add onclick event to show more info
			btn.onclick = function() {
				this.innerHTML = "Meno info";
				OrdersTable.showDetails(orderId, this);
			};

		var btnCell = mainRow.insertCell(-1);
		btnCell.appendChild(btn);


		// DETAILS ROW creation (contains all the details)
		var detailsRow = this.tbody.insertRow(-1);
		detailsRow.id = "details_" + orderId;
		detailsRow.style.display = "none";
		var detailsCell = detailsRow.insertCell(0);
		detailsCell.colSpan = this.colsNumber;

		// insert details data
		OrdersTable.detailsContent(detailsCell,orderData); //append data in this btnCell

	}

	/**
	 * Is a static method called by the onclick event to show the details row.
	 * The details row already exists and is showed setting its style.disply 
	 * property to its default value
	 *
	 * @method showDetails
	 * @param orderId to get the row by ID
	 * @param thisButton to modify oncllick event
	 * @return nothing
	 */
	static showDetails(orderId, thisButton){
		// show details row
		var detailsRow = document.getElementById("details_" + orderId);
		detailsRow.style.display = ""; // reset to default style
		
		// update button info
		var onClickShowEvent = thisButton.onclick;
		thisButton.onclick = function(){ OrdersTable.hideDetails(orderId, thisButton, onClickShowEvent)};
	}

	static hideDetails(orderId, thisButton, onClickShowEvent){
		// hide details row
		var detailsRow = document.getElementById("details_" + orderId);
		detailsRow.style.display = "none"; // hide

		// reset button info
		thisButton.innerHTML = "Più info";
		thisButton.onclick = onClickShowEvent;
	}

	static detailsContent(parent, orderData){
		// spacing
		parent.appendChild(document.createElement('BR'));

		// client details
		var clientHeader = document.createElement("H2");
		parent.appendChild(clientHeader);
		clientHeader.innerHTML = "Cliente";

		var clientTable = new ClientsTable();
		clientTable.addClient(0,orderData);
		parent.appendChild(clientTable.table);

		parent.appendChild(document.createElement('BR'));

		// retailer details
		var retailerHeader = document.createElement("H2");
		parent.appendChild(retailerHeader);
		retailerHeader.innerHTML = "Veditore";

		var retailerTable = new RetailersTable();
		retailerTable.addRetailer(0,orderData);
		parent.appendChild(retailerTable.table);

		parent.appendChild(document.createElement('BR'));

		// order details
		var orderHeader = document.createElement("H2");
		parent.appendChild(orderHeader);
		orderHeader.innerHTML = "Ordine";
		
		var orderDescr = "<b>Dettagli ordine: </b>" + orderData.order_details + "<br>" + 
						 "<b>Consegnato: </b>" + (orderData.order_delivered == true ? "Si" : "No")  + "<br>" +
						 "<b>Prezzo ordine: </b>" + (orderData.order_price == null ? "---" : orderData.order_price) + "€<br>" +
						 "<b>Costo servizio: </b>" + (orderData.service_cost == null ? "---" : orderData.service_cost) + "€<br>";
		var p = document.createElement('P');
		p.innerHTML = orderDescr;
		parent.appendChild(p);

		// spacing
		parent.appendChild(document.createElement('BR'));
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
			ordersTab.addOrder(rx[i].order_number, rx[i]);
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

function tomorrowOrders(response){
// element id where insert the orders table
	var parent_id = 'tomorrow_orders';
	var table_id = parent_id + '_table';

	//parse response
	var rx = JSON.parse(response);
	if(rx.length > 0){
		// orders table object
		var ordersTab = new OrdersTable(table_id);

		//insert rows
		for(let i = 0; i < rx.length; i++){
			//create a new row for the table
			ordersTab.addOrder(rx[i].order_number, rx[i]);
		}

		// insert table in the page
		document.getElementById(parent_id).appendChild(ordersTab.table);
	}
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