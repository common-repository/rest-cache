import RulesTable from "./RulesTable";
import RecordsTable from "./RecordsTable";

export default class Admin
{
	constructor()
	{
		this.rulesTable		= new RulesTable($("#rules table"));
		this.recordsTable	= new RecordsTable($("#records table"));
		
		$("#rest-cache-tabs").tabs();
	}
}

window.RESTCache = {
	admin: new Admin()
};