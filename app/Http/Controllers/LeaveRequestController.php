<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function leaveCalendar()
    {
        $publicHolidays = PublicHoliday::all();

		return view('leaveCalendar', ['publicHolidays' => $publicHolidays]);
    }

    public function applyLeaveForm()
    {
        $leaveTypes = LeaveType::all()
                      ->whereIn('gender', ['All', Str::ucfirst(Auth::user()->gender)]);
        $approvedLeaves = LeaveRequest::all()
                          ->where('employeeID', Auth::user()->id)
                          ->whereIn('leaveStatus', [0, 2]);
        
        return view('applyLeave', ['leaveTypes' => $leaveTypes, 'approvedLeaves' => $approvedLeaves]);
    }

    public function applyLeave(Request $request)
    {
        $this->validate($request, [
            'leaveType' => 'required',
            'leaveStartDate' => 'required|date',
            'leaveDuration' => 'required|min:1',
            'leaveDescription' => 'required|max:255',
        ]);

        $conflictLeaves = LeaveRequest::all()
                          ->whereIn('leaveStatus', [0,2])
                          ->where('employeeID', Auth::user()->id);
        
        if (count($conflictLeaves) > 0) {
            foreach ($conflictLeaves as $conflictLeave) {
                $dateDuration = date_diff(date_create($conflictLeave->leaveEndDate), date_create($conflictLeave->leaveStartDate));
            }
    
            $conflictLeaveArray = array();
            $count = $dateDuration->format("%a") + 1;
            for ($i=0; $i < $count; $i++) {
                foreach ($conflictLeaves as $conflictLeave) {
                    array_push($conflictLeaveArray, date("Y-m-d", strtotime("+$i days", strtotime($conflictLeave->leaveStartDate))));
                }
            }
    
            $appliedLeaveArray = array();
            for ($i=0; $i < $request->leaveDuration; $i++) { 
                array_push($appliedLeaveArray, date("Y-m-d", strtotime("+$i days", strtotime($request->leaveStartDate))));
            }
    
            if (count(array_intersect($appliedLeaveArray, $conflictLeaveArray)) >= 1) {
                return redirect()->route('applyLeave')->with('error', 'Conflict leave date found, please try again');
            }
        }

        $numberOfPublicHolidays = 0;
        $publicHolidays = PublicHoliday::all();
        for ($i=0; $i < $request->leaveDuration; $i++) {
            foreach ($publicHolidays as $publicHoliday) {
                if($publicHoliday->date == date("Y-m-d", strtotime("+$i days", strtotime($request->leaveStartDate)))){
                    $numberOfPublicHolidays++ ;
                }
            }
        }
        
        $duration = $request->leaveDuration - 1;

        $leaveRequest = new LeaveRequest();
        $leaveRequest->leaveType = $request->leaveType;
        $leaveRequest->employeeID = Auth::user()->id;
        $leaveRequest->leaveStartDate = $request->leaveStartDate;
        $leaveRequest->leaveEndDate = date("Y-m-d", strtotime("+$duration days", strtotime($request->leaveStartDate)));
        $leaveRequest->leaveDuration = $request->leaveDuration - $numberOfPublicHolidays;
        $leaveRequest->leaveDescription = $request->leaveDescription;
        $leaveRequest->leaveStatus = 0;
        $leaveRequest->save();

        //$hrEmails = $leaveRequest->getHrManagerEmail();

        //Mail::to($hrEmails)->send(new ClaimRequestMail($claimRequest));

        return redirect()->route('applyLeave')->with('message', 'Leave applied successfully!');
    }

    public function manageLeave()
    {
        if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
            $leaveRequests = LeaveRequest::orderBy('leaveStatus', 'ASC')
                                           ->orderBy('leaveStartDate', 'ASC')
                                           ->get();
        }
        else{
            $leaveRequests = LeaveRequest::orderBy('leaveStatus', 'ASC')
                                           ->orderBy('leaveStartDate', 'ASC')
                                           ->where('employeeID', Auth::user()->id)
                                           ->get();
        }
        return view('manageLeave', ['leaveRequests' => $leaveRequests]);
    }

    public function viewLeave($id)
    {
        $leaveRequest = LeaveRequest::find($id);

        return view('viewLeave', ['leaveRequest' => $leaveRequest]);
    }

    public function approveLeaveRequest($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->leaveStatus = 2;
        $leaveRequest->save();

        return redirect()->route('viewLeave', ['id' => $id]);
    }

    public function rejectLeaveRequest($id, $reason)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->leaveStatus = 1;
        $leaveRequest->save();
        
        return redirect()->route('viewLeave', ['id' => $id]);
    }

    public function deleteLeave($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->delete();

        return redirect()->route('manageLeave');
    }
}
