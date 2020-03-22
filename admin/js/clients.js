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
					   	"Email" 
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