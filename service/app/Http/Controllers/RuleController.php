<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tables\RulesTable;
use App\Rule;
use App\Rules\ValidRegex;

class RuleController extends TableController
{
	protected function getModelClass()
	{
		return "\\App\\Rule";
	}
	
	public function index(Request $request)
	{
		$table = new RulesTable($request);
		return $table->getRecords($request);
	}
	
	private function validateRegex(Request $request)
	{
		if($request->input("regex") != 1)
			return;
		
		$request->validate([
			"pattern" => new ValidRegex
		]);
	}
	
	public function store(Request $request)
	{
		$this->validateRegex($request);
		
		return parent::store($request);
	}
	
	public function update(Request $request, $id)
	{
		$this->validateRegex($request);
		
		return parent::update($request, $id);
	}
}
