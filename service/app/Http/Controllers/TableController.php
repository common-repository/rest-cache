<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class TableController extends Controller
{
	abstract protected function getModelClass();
	
	public function store(Request $request)
	{
		$class	= $this->getModelClass();
		$model	= new $class();
		$model->save();
		
		return $model;
	}
	
	public function update(Request $request, $id)
	{
		$class	= $this->getModelClass();
		$model	= $class::find($id);
		
		foreach($request->input() as $key => $value)
			$model->{$key} = $value;
		
		$model->save();
		
		return $model;
	}
	
	public function destroy(Request $request, $id)
	{
		$class	= $this->getModelClass();
		$model	= $class::find($id);
		$model->delete();
		
		return true;
	}
}