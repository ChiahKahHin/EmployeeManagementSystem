<?php

namespace App\Http\Controllers;

use App\Models\PublicHoliday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['employee:admin,hrmanager']);
    }

    public function addPublicHolidayForm()
    {
        return view('addPublicHoliday');
    }

    public function addPublicHoliday(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:public_holidays,name',
            'date' => 'required|date'
        ]);

        $publicHoliday = new PublicHoliday();
        $publicHoliday->name = $request->name;
        $publicHoliday->date = $request->date;
        $publicHoliday->save();

        return redirect()->route('addPublicHoliday')->with('message', 'Public holiday added successfully!');
    }

    public function managePublicHoliday()
    {
        $publicHolidays = PublicHoliday::orderBy('date', 'DESC')->get();

        return view('managePublicHoliday', ['publicHolidays' => $publicHolidays]);
    }

    public function editPublicHolidayForm($id)
    {
        $publicHoliday = PublicHoliday::find($id);

        return view('editPublicHoliday', ['publicHoliday' => $publicHoliday]);
    }

    public function editPublicHoliday(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:public_holidays,name,'.$id.'',
            'date' => 'required|date'
        ]);

        $publicHoliday = PublicHoliday::find($id);
        $publicHoliday->name = $request->name;
        $publicHoliday->date = $request->date;
        $publicHoliday->save();

        return redirect()->route('editPublicHoliday', ['id' => $id])->with('message', 'Public holiday details updated successfully!');
    }

    public function deletePublicHoliday($id)
    {
        $publicHoliday = PublicHoliday::find($id);
        $publicHoliday->delete();

        return redirect()->route('managePublicHoliday');
    }
}
