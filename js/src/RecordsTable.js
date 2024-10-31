import Table from "./Table";

export default class RecordsTable extends Table
{
	constructor(element)
	{
		super(element);
		
		$("#rest-cache-admin #refresh-table").on("click", (event) => this.onRefreshTable(event));
		$("#rest-cache-admin #clear-cache").on("click", (event) => this.onClearCache(event));
	}
	
	onRefreshTable(event)
	{
		$("#rest-cache-admin #records .dataTable").DataTable().ajax.reload();
	}
	
	onClearCache(event)
	{
		var self = this;
		
		$.ajax(this.url, {
			method: "DELETE",
			success: function(response, status, xhr) {
				self.onActionComplete(response);
			}
		});
	}
	
	onActionComplete(event)
	{
		this.$element.DataTable().ajax.reload();
	}
}
