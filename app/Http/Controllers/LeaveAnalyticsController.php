<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveAnalyticsController extends Controller
{
    public function leaveAnalytics(){
        $overallLeaveYears = array();
        $leaveRequests = LeaveRequest::select('updated_at')->get();
        foreach ($leaveRequests as $leaveRequest) {
            if(!in_array($leaveRequest->updated_at->year, $overallLeaveYears)){
                array_push($overallLeaveYears, $leaveRequest->updated_at->year);
            }
        }
        rsort($overallLeaveYears);

        $departments = LeaveRequest::with('getEmployee.getDepartment')->select('employeeID', 'leaveStatus')->distinct()->get();

        $leaveApprovedAndRejectedYears = array();
        $leaveRequests = LeaveRequest::select('updated_at')->whereIn('leaveStatus', [1, 2])->get();
        foreach ($leaveRequests as $leaveRequest) {
            if(!in_array($leaveRequest->updated_at->year, $leaveApprovedAndRejectedYears)){
                array_push($leaveApprovedAndRejectedYears, $leaveRequest->updated_at->year);
            }
        }
        rsort($leaveApprovedAndRejectedYears);

        return view('leaveAnalytics', ['overallLeaveYears' => $overallLeaveYears, 'leaveApprovedAndRejectedYears' => $leaveApprovedAndRejectedYears, 'departments' =>$departments]);
    }

    public function overallLeaveAnalytics($year, $department)
    {
        $leaveStatus = array();
        $leaveLabel = array();
        $leaveNumber = array();

        if($department == "null"){
            $statuses = LeaveRequest::select('leaveStatus')->distinct()->where('updated_at', 'like', ''.$year.'%')->orderBy('leaveStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($leaveStatus, $status->leaveStatus);
                array_push($leaveLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($leaveStatus); $i++) { 
                $leaves = LeaveRequest::where('leaveStatus', $leaveStatus[$i])->where('updated_at', 'like', ''.$year.'%')->count();
                array_push($leaveNumber, $leaves);
            }
        }
        else{
            $statuses = LeaveRequest::select('leave_requests.leaveStatus')->distinct()
                             ->where('leave_requests.updated_at', 'like', ''.$year.'%')
                             ->join('users', function ($join) use ($department){
                                $join->on('leave_requests.employeeID', 'users.id')
                                     ->where('users.department', $department);
                             })
                             ->orderBy('leave_requests.leaveStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($leaveStatus, $status->leaveStatus);
                array_push($leaveLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($leaveStatus); $i++) { 
                $leaves = LeaveRequest::where('leave_requests.leaveStatus', $leaveStatus[$i])
                              ->where('leave_requests.updated_at', 'like', ''.$year.'%')
                              ->join('users', function ($join) use ($department){
                                $join->on('leave_requests.employeeID', 'users.id')
                                     ->where('users.department', $department);
                             })
                              ->count();
                array_push($leaveNumber, $leaves);
            }
        }

        return [array_values($leaveLabel), array_values($leaveNumber)];
    }

    public function leaveApprovedAndRejectedAnalytics($year, $department)
    {
        $leaveApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $leaveRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($department == "null"){
            $approvedLeaves = LeaveRequest::where('updated_at', 'like', ''.$year.'%')->where('leaveStatus', 2)->get();
            foreach ($approvedLeaves as $approvedLeave) {
                $month = date('m', strtotime($approvedLeave->updated_at));
                $leaveApprovedArrays[$month] = $leaveApprovedArrays[$month] + 1;
            }
    
            $rejectedLeaves = LeaveRequest::where('updated_at', 'like', ''.$year.'%')->where('leaveStatus', 1)->get();
            foreach ($rejectedLeaves as $rejectedLeave) {
                $month = date('m', strtotime($rejectedLeave->updated_at));
                $leaveRejectedArrays[$month] = $leaveRejectedArrays[$month] + 1;
            }
        }
        else{
            $approvedLeaves = LeaveRequest::select('leave_requests.updated_at as leaveUpdatedAt')
                                   ->where('leave_requests.updated_at', 'like', ''.$year.'%')
                                   ->where('leave_requests.leaveStatus', 2)
                                   ->join('users', function ($join) use ($department) {
                                        $join->on('leave_requests.employeeID', 'users.id')
                                            ->where('users.department', $department);
                                    })->get();
            foreach ($approvedLeaves as $approvedLeave) {
                $updated_at = new Carbon($approvedLeave->leaveUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $leaveApprovedArrays[$month] = $leaveApprovedArrays[$month] + 1;
            }
    
            $rejectedLeaves = LeaveRequest::select('leave_requests.updated_at as leaveUpdatedAt')
                                    ->where('leave_requests.updated_at', 'like', ''.$year.'%')
                                    ->where('leave_requests.leaveStatus', 1)
                                    ->join('users', function ($join) use ($department) {
                                        $join->on('leave_requests.employeeID', 'users.id')
                                            ->select('users.departmet')
                                            ->where('users.department', $department);
                                    })->get();
            foreach ($rejectedLeaves as $rejectedLeave) {
                $updated_at = new Carbon($rejectedLeave->leaveUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $leaveRejectedArrays[$month] = $leaveRejectedArrays[$month] + 1;
            }
        }

        return [array_values($leaveApprovedArrays), array_values($leaveRejectedArrays)];
    }
}
