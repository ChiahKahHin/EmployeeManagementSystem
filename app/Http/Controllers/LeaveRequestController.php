<?php

namespace App\Http\Controllers;

use App\Mail\LeaveRequestMail;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PublicHoliday;
use App\Models\User;
use App\Models\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function leaveCalendar()
    {
        $publicHolidays = PublicHoliday::all();

        if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
            $leaveRequests = LeaveRequest::all()->where('leaveStatus', 2);
        }
        else{
            $leaveRequests = LeaveRequest::all()->where('leaveStatus', 2)->where('employeeID', Auth::id());
        }

		return view('leaveCalendar', ['publicHolidays' => $publicHolidays, 'leaveRequests' => $leaveRequests]);
    }

    public function applyLeaveForm()
    {
        $leaveTypes = LeaveType::all()
                      ->whereIn('gender', ['All', Auth::user()->gender]);
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
            'leaveEndDate' => 'required|date',
            'leavePeriod' => 'required',
            'leaveDescription' => 'required|max:255',
        ]);

        $leaveDiff = date_diff(date_create($request->leaveEndDate), date_create($request->leaveStartDate));
        $leaveDuration = $leaveDiff->format("%a") + 1;

        //Check conflict with other leave request (Pending/Approved)
        $conflictLeaves = LeaveRequest::all()
                          ->whereIn('leaveStatus', [0,2])
                          ->where('employeeID', Auth::user()->id);

        if (count($conflictLeaves) > 0) {
            foreach ($conflictLeaves as $conflictLeave) {
                $currentYear = Carbon::now()->year;
                $conflictLeaveYear = Carbon::createFromFormat('Y-m-d', $conflictLeave->leaveStartDate)->year;
                if ($currentYear == $conflictLeaveYear) {
                    $dateDuration = date_diff(date_create($conflictLeave->leaveEndDate), date_create($conflictLeave->leaveStartDate));
                
                    $conflictLeaveArray = array();
                    $count = $dateDuration->format("%a") + 1;
                    for ($i=0; $i < $count; $i++) {
                        array_push($conflictLeaveArray, date("Y-m-d", strtotime("+$i days", strtotime($conflictLeave->leaveStartDate))));
                    }
            
                    $appliedLeaveArray = array();
                    for ($i=0; $i < $leaveDuration; $i++) { 
                        array_push($appliedLeaveArray, date("Y-m-d", strtotime("+$i days", strtotime($request->leaveStartDate))));
                    }
            
                    if (count(array_intersect($appliedLeaveArray, $conflictLeaveArray)) >= 1) {
                        $array = array_intersect($appliedLeaveArray, $conflictLeaveArray);
                        return redirect()->route('applyLeave')->with('error', 'Conflict leave date found, please try again')
                                                              ->with('error1', 'Conflict Date: ' . reset($array));
                    }
                }
            }
        }

        //Check conflict with public holiday
        $numberOfPublicHolidays = 0;
        $publicHolidays = PublicHoliday::distinct()->get('date');
        for ($i=0; $i < $leaveDuration; $i++) {
            foreach ($publicHolidays as $publicHoliday) {
                if($publicHoliday->date == date("Y-m-d", strtotime("+$i days", strtotime($request->leaveStartDate)))){
                    $numberOfPublicHolidays++ ;
                    $publicHolidayDate = $publicHoliday->date;
                }
            }
        }

        $leaveDurations = $leaveDuration - $numberOfPublicHolidays;

        if ($leaveDurations <= 0) {
            return redirect()->route('applyLeave')->with('error', 'Please do not apply leave on Public Holiday ')
                                                  ->with('error1', "Public Holiday: ". $publicHolidayDate);
        }

        //Check conflict with non-working day
        $numberOfNonWorkingDays = 0;
        $nonWorkingDays = WorkingDay::all()->where('status', 0);
        for ($i=0; $i < $leaveDuration; $i++) {
            foreach ($nonWorkingDays as $nonWorkingDay) {
                if($nonWorkingDay->workingDay == date("l", strtotime("+$i days", strtotime($request->leaveStartDate)))){
                    $numberOfNonWorkingDays++;
                }
            }
        }

        $leaveDurations = $leaveDuration - $numberOfPublicHolidays - $numberOfNonWorkingDays;

        if ($leaveDurations <= 0) {
            return redirect()->route('applyLeave')->with('error', 'Please do not apply leave on non-working day');
        }

        $leaveRequest = new LeaveRequest();
        $leaveRequest->leaveType = $request->leaveType;
        $leaveRequest->employeeID = Auth::user()->id;
        $leaveRequest->manager = Auth::user()->reportingManager;
        $leaveRequest->leaveStartDate = $request->leaveStartDate;
        $leaveRequest->leaveEndDate = $request->leaveEndDate;
        if($request->leavePeriod != "Full Day"){
            $leaveRequest->leaveDuration = 0.5;
            $leaveDurations = 0.5;
        }
        else{
            $leaveRequest->leaveDuration = $leaveDurations;
        }
        $leaveRequest->leavePeriod = $request->leavePeriod;
        $leaveRequest->leaveDescription = $request->leaveDescription;
        if ($request->leaveEndDate < date('Y-m-d')) {
            $leaveRequest->leaveReplacement = 1;
        } else {
            $leaveRequest->leaveReplacement = 0;
        }
        
        $leaveRequest->leaveStatus = 0;
        $leaveRequest->save();

        Mail::to($leaveRequest->getManager->email)->send(new LeaveRequestMail($leaveRequest));

        return redirect()->route('applyLeave')->with('message', 'Leave applied successfully!')
                                              ->with('message1', $leaveDurations);
    }

    public function manageLeave()
    {
        if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
            $leaveRequests = LeaveRequest::orderBy('leaveStatus', 'ASC')
                                           ->orderBy('leaveStartDate', 'ASC')
                                           ->get();
        }
        elseif(Auth::user()->isManager()){
            $leaveRequests = LeaveRequest::orderBy('leaveStatus', 'ASC')
                                           ->orderBy('leaveStartDate', 'ASC')
                                           ->where('employeeID', Auth::user()->id)
                                           ->orWhere('manager', Auth::user()->id)
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
        $managers = User::orderBy('role', 'DESC')->whereIn('role', [1,2])->get();

        return view('viewLeave', ['leaveRequest' => $leaveRequest, 'managers' => $managers]);
    }

    public function approveLeaveRequest($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->leaveStatus = 2;
        $leaveRequest->save();
        
        Mail::to($leaveRequest->getEmployee->email)->send(new LeaveRequestMail($leaveRequest));

        return redirect()->route('viewLeave', ['id' => $id]);
    }

    public function rejectLeaveRequest($id, $reason)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->leaveStatus = 1;
        $leaveRequest->leaveRejectedReason = $reason;
        $leaveRequest->save();

        Mail::to($leaveRequest->getEmployee->email)->send(new LeaveRequestMail($leaveRequest, $reason));
        
        return redirect()->route('viewLeave', ['id' => $id]);
    }

    public function deleteLeave($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->delete();

        return redirect()->route('manageLeave');
    }
    
    public function changeLeaveManager(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->manager = $request->manager;
        $leaveRequest->save();

        $emails = array();
        array_push($emails, $leaveRequest->getManager->email);
        array_push($emails, $leaveRequest->getEmployee->email);

        Mail::to($emails)->send(new LeaveRequestMail($leaveRequest, null, true));

        return redirect()->route('manageLeave')->with('message', 'Leave approval manager delegate successfully!');
    }

    public function cancelLeaveRequest($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->leaveStatus = 3;
        $leaveRequest->save();

        Mail::to($leaveRequest->getManager->email)->send(new LeaveRequestMail($leaveRequest));
        
        return redirect()->route('viewLeave', ['id' => $id]);
    }
}
