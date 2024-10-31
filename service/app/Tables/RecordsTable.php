<?php

namespace App\Tables;

use PerryRylance\DataTable;

class RecordsTable extends DataTable
{
	const ID_PLACEHOLDER = '__3d29cb658e258833084dbbe854d44efc';
	
	public function __construct($request=null)
	{
		DataTable::__construct($request, [
			"autoInitialize" => false
		]);
	}
	
	public function getColumns()
	{
		$id_placeholder = RecordsTable::ID_PLACEHOLDER;
		
		return [
			'id'				=> [
				'display'		=> false,
				'sql'			=> 'id'
			],	
			'uri'				=> [
				'caption'		=> 'URI',
				'type'			=> 'text',
				'sql'			=> 'uri'
			],	
			'filename'			=> [
				'caption'		=> 'File',
				'type'			=> 'text',
				'sql'			=> 'filename'
			],	
			'size'				=> [
				'caption'		=> 'Size',
				'type'			=> 'int',
				'sql'			=> 'size'
			],	
			'hits'				=> [
				'caption'		=> 'Hits',
				'type'			=> 'int',
				'sql'			=> 'hits'
			],	
			'created'			=> [
				'caption'		=> 'Created',
				'type'			=> 'datetime',
				'sql'			=> 'created'
			],	
			'expires'			=> [
				'caption'		=> 'Expires',
				'type'			=> 'datetime',
				'sql'			=> 'expires'
			],	
			'actions'			=> [
				'caption'		=> 'Actions',
				'type'			=> 'text',
				'searchable'	=> false,
				'sql'			=> 
					'\'<div class="rest-cache-action-buttons">
						<button class="button button-secondary"
							title="Clear" 
							data-action="delete">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>\' AS `actions`'
			]
		];
	}
	
	public function getTableName()
	{
		return "rest_cache_records";
	}
	
	public function getRoute()
	{
		// TODO: Move this to a parent class, in the very least make a prefix function
		// TODO: Remove plugins_url - use Laravel instead
		$prefix = plugins_url('', public_path()) . '/public/api';
		return "$prefix/records";
	}
}
