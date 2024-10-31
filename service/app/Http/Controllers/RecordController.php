<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Tables\RecordsTable;
use App\Record;

class RecordController extends TableController
{
    protected function getModelClass()
	{
		return "\\App\\Record";
	}
	
	public function index(Request $request)
	{
		$table = new RecordsTable($request);
		return $table->getRecords($request);
	}
	
	public function destroy(Request $request, $id)
	{
		$record = Record::find($id);
		
		if(!unlink($record->filename))
			Log::warning("Failed to delete record file");
		
		return Record::destroy($id);
	}
	
	public function destroyAll(Request $request)
	{
		$dir = env("RECORDS_DIR", null);
		
		if($dir)
		{
			$files = glob("$dir/*");
			
			foreach($files as $file)
				if(is_file($file))
					unlink($file);
		}
		else
			Log::warning("Failed to clear cache directory");
		
		Record::truncate();
		
		return ["success" => true];
	}
}
