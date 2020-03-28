/**
 * Se gli headers non sono specifiacti, usa quelli di default
 *
 * @method 
 * @param 
 * @return 
 */
    
class ClientsTable{
	constructor(headers=null){
		this.table = document.createElement('TABLE');
		this.thead = this.table.createTHead();
		this.tbody = this.table.createTBody();
		
		// table metadata
		this.table.border = 1;

		// header building
		this.headers = headers == null ? [
						"Nome", 
					   	"Cognome",
					   	"Telefono",
					   	"Indirizzo",
					   	"Email",
					   	"Elimina"
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

	addClient(clientId, clientData){
		// client row creation
		var clientRow = this.tbody.insertRow(-1);
		clientRow.id = clientId;

		// define the orered data list
		var clientDataList = [
						 	clientData.client_name,
						 	clientData.client_surname,
						 	clientData.client_tel,
						 	clientData.client_address,
						 	clientData.client_mail
						];

		// inserting client data
		for (let elem of clientDataList){
			var cell = clientRow.insertCell(-1);
			cell.innerHTML = elem;
		}
	}
}


function loadClients(){
	var clientsRequest = new AjaxRequest("POST", "php/get_clients.php", arrangeOrders);
	clientsRequest.sendRequest();
}

function arrangeOrders(response){
	// element id where insert the orders table
	var parent_id = 'clients';
	var table_id = parent_id + '_table';

	//parse response
	var rx = JSON.parse(response);
	if(rx.length > 0){
		// clients table object
		var clientsTab = new ClientsTable();

		//insert rows
		for(let i = 0; i < rx.length; i++){
			//create a new row for the table
			clientsTab.addClient(rx[i].client_id, rx[i]);
		}

		// insert table in the page
		document.getElementById(parent_id).appendChild(clientsTab.table);
	}
}