<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin,hrmanager']);
    }

    public function addPositionForm()
    {
        return view('addPosition');
    }

    public function addPosition(Request $request)
    {
        $this->validate($request, [
            'positionName' => 'required|max:255|unique:positions,positionName'
        ]);

        $position = new Position();
        $position->positionName = $request->positionName;
        $position->save();

        return redirect()->route('addPosition')->with('message', 'Position added successfully!');
    }

    public function managePosition()
    {
        $positions = Position::all();

        return view('managePosition', ['positions' => $positions]);
    }

    public function editPositionForm($id)
    {
        $position = Position::find($id);

        return view('editPosition', ['position' => $position]);
    }

    public function editPosition(Request $request, $id)
    {
        $this->validate($request, [
            'positionName' => 'required|max:255|unique:positions,positionName, '.$id.''
        ]);

        $position = Position::find($id);
        $position->positionName = $request->positionName;
        $position->save();

        return redirect()->route('editPosition', ['id' => $id])->with('message', 'Position updated successfully!');
    }

    public function deletePosition($id)
    {
        $position = Position::find($id);
        $position->delete();

        return redirect()->route('managePosition');
    }
}
