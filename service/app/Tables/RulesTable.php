<?php

namespace App\Tables;

use Illuminate\Support\Facades\DB;
use PerryRylance\DataTable;

class RulesTable extends DataTable
{
	const ID_PLACEHOLDER = '__3d29cb658e258833084dbbe854d44efc';
	
	public function __construct($request=null)
	{
		DataTable::__construct($request, [
			"autoInitialize" => false
		]);
	}
	
	protected function getColumns()
	{
		$id_placeholder = RulesTable::ID_PLACEHOLDER;
		
		$columns = [
			'id'				=> [
				'display'		=> false,
				'sql'			=> 'id'
			],	
			'pattern'			=> [
				'caption'		=> 'Pattern',
				'type'			=> 'text',
				'sql'			=> 'pattern'
			],	
			'regex'				=> [
				'caption'		=> 'Regex',
				'type'			=> 'int',
				'sql'			=> 'regex'
			],	
			'behaviour'			=> [
				'caption'		=> 'Behaviour',
				'type'			=> 'enum',
				'sql'			=> 'behaviour'
			],
			'priority'			=> [
				'caption'		=> 'Priority',
				'type'			=> 'int',
				'sql'			=> 'priority'
			],	
			'actions'			=> [
				'caption'		=> 'Actions',
				'type'			=> 'text',
				'searchable'	=> false,
				'sql'			=> DB::raw('
					REPLACE(CONCAT(\'
							<div class="rest-cache-action-buttons">
								<button class="button button-secondary"
									title="Edit"
									data-action="edit">
									<i class="fas fa-pen-square"></i>
								</button>
								<button class="button button-secondary"
									title="Update"
									data-action="update">
									<i class="fas fa-save"></i>
								</button>
								<button class="button button-secondary"
									title="Remove" 
									data-action="delete">
									<i class="fas fa-trash-alt"></i>
								</button>
								<button class="button button-secondary"
									title="Cancel"
									data-action="cancel">
									<i class="fas fa-ban"></i>
								</button>
							</div>
						\'),
						"' . $id_placeholder . '",
						id
					) AS actions
				')
			]
		];
		
		return $columns;
	}
	
	public function getTableName()
	{
		return "rest_cache_rules";
	}
	
	public function getRoute()
	{
		// TODO: Move this to a parent class, in the very least make a prefix function
		// TODO: Remove plugins_url - use Laravel instead
		$prefix = plugins_url('', public_path()) . '/public/api';
		return "$prefix/rules";
	}
}
