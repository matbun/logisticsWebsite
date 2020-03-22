class RetailersTable{
	constructor(headers=null){
		this.table = document.createElement('TABLE');
		this.thead = this.table.createTHead();
		this.tbody = this.table.createTBody();
		
		// table metadata
		this.table.border = 1;

		// header building
		this.headers = headers == null ? [
						"Nome", 
					   	"Proprietario",
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

	addRetailer(retailerId, retailerData){
		// retailer row creation
		var retailerRow = this.tbody.insertRow(-1);
		retailerRow.id = retailerId;

		// define the orered data list
		var retailerDataList = [
						 	retailerData.retailer_name,
						 	retailerData.retailer_owner,
						 	retailerData.retailer_tel,
						 	retailerData.retailer_address,
						 	retailerData.retailer_mail
						];

		// inserting retailer data
		for (let elem of retailerDataList){
			var cell = retailerRow.insertCell(-1);
			cell.innerHTML = elem;
		}
	}
}