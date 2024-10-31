<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\InvalidRegexException;

class Rule extends Model
{
	protected $table		= "rest_cache_rules";
	public $timestamps		= false;
	
	public function __construct(array $attributes = [])
	{
		$this->saved(function() {
			Rule::onRulesFileInvalidated();
		});
		
		$this->deleted(function() {
			Rule::onRulesFileInvalidated();
		});
	}
	
	public static function onRulesFileInvalidated()
	{
		$rules	= Rule::all()->sortBy("priority");
		$file	= public_path() . '/rules.json';
		$json	= json_encode($rules);
		
		file_put_contents($file, $json);
	}
	
	public function save(array $options = [])
	{
		if($this->regex == 1 && @preg_match("@{$this->pattern}@", null) === false)
			throw new InvalidRegexException("Invalid regular expression");
		
		return parent::save($options);
	}
}