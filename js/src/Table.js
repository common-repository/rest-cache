import DataTable from "@perry-rylance/data-table";

export default class Table extends DataTable
{
	constructor(element)
	{
		super(element);
		
		this.$element = $(element);
		
		this.url = this.$element.attr("data-route");
		
		this.$element.on("click", "[data-action='edit']", event => this.onEdit(event));
		this.$element.on("click", "[data-action='update']", event => this.onUpdate(event));
		this.$element.on("click", "[data-action='delete']", event => this.onDelete(event));
	}
	
	getIDFromEvent(event)
	{
		let $tr = $(event.currentTarget).closest("tr");
		let id = $tr.attr("data-id");
		
		return id;
	}
	
	getControlFromField(field)
	{
		let $input = $("<input/>");
		$input.attr("name", field);
		return $input;
	}
	
	setItemEditable(id)
	{
		let self = this;
		let $tr = this.$element.find("tr[data-id='" + id + "']");
		
		$tr.children("td").each(function(index, td) {
			
			let field = $(td).attr("data-field");
			
			switch(field)
			{
				case "id":
				case "actions":
					return true;
					break;
			}

			let $input = self.getControlFromField(field);
			
			switch($input.attr("type"))
			{
				case "checkbox":
					$input.prop("checked", $(td).text() == 1);
					break;
				
				default:
					$input.val($(td).text());
					break;
			}
			
			$(td).empty();
			$(td).append($input);
			
		});
		
		$tr.addClass("rest-cache-editing");
	}
	
	onEdit(event)
	{
		let id = this.getIDFromEvent(event);
		this.setItemEditable(id);
	}
	
	onUpdate(event)
	{
		let self	= this;
		let id		= this.getIDFromEvent(event);
		let data	= {};
		let $tr		= $(event.currentTarget).closest("tr");
		
		$tr.find(":input").each(function(index, el) {
			
			var name = $(el).attr("name");
			
			if(!name)
				return;
			
			switch($(el).attr("type"))
			{
				case "checkbox":
				case "radio":
					data[name] = $(el).prop("checked") ? 1 : 0;
					break;
				
				default:
					data[name] = $(el).val();
					break;
			}
			
		});
		
		$.ajax(this.url + "/" + id, {
			method: "PUT",
			data: data,
			success: function(response, status, xhr) {
				self.onActionComplete(response);
			},
			error: function(xhr, status, error) {
				if(xhr.status == 422)
				{
					var json = JSON.parse(xhr.responseText);
					self.onError(xhr.status, json);
				}
			}
		});
	}
	
	onDelete(event)
	{
		let self	= this;
		let id		= this.getIDFromEvent(event);
		
		$.ajax(this.url + "/" + id, {
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
	
	onError(code, json)
	{
		for(var key in json.errors)
		{
			alert(json.errors[key]);
		}
	}
}
