<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Report;
use App\Models\Message;
use App\Models\GeoRegion;
use Illuminate\Http\Request;

class InsertController extends Controller
{
    public function insert()
    {
        $reports = json_decode(file_get_contents(storage_path() . "/25610.json"), true);
        foreach ($reports as $report) {
            Report::create($report);
        }
        Report::wherem_id(25610)->get();
        foreach ($reports as $r) {
            $reg_id = Card::find(25610)->reg_number;
            $r->region = $reg_id;
            $region = Message::where('case_number',$r->case_number)->first()->region;
            $r->region = $region;
            $r->region_id = GeoRegion::where('name', $region)->count() == 1 ? GeoRegion::where('name', $region)->first()->id : 0;
            $r->save();
        }
    }
}
