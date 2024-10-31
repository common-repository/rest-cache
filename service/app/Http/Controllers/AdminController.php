<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PerryRylance\DOMDocument;
use PerryRylance\DOMDocument\Illuminate\Validation\Form;
use App\Tables\RecordsTable;
use App\Tables\RulesTable;

class AdminController extends Controller
{
	private $document;
	
	private function initDocument()
	{
		$this->document = new DOMDocument();
		$this->document->loadHTML( (string)View::make("pages.admin") );
		
		return $this->document;
	}
	
    public function view(Request $request)
	{
		global $restCachePlugin;
		
		$document	= $this->initDocument();
		
		foreach($restCachePlugin->settings as $name => $value)
			$document->find("[name='$name']")->val($value);
		
		$table		= $document->import(new RecordsTable($request));
		$document->querySelector("#records")->append($table);
		
		$table		= $document->import(new RulesTable($request));
		$document->querySelector("#rules")->append($table);
		
		return $document->html;
	}
	
	public function store(Request $request)
	{
		global $restCachePlugin;
		
		$document	= $this->initDocument();
		
		$form		= $this->document->querySelector("form");
		$rules		= Form::getValidationRules($form);
		
		$data		= $request->validate($rules);
		
		$restCachePlugin->settings->set($data);
		
		return redirect($request->fullUrl());
	}
}
