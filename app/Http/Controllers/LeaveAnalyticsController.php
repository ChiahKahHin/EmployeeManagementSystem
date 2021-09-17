<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin'])->only(['leaveAnalytics', 'overallLeaveAnalytics', 'leaveApprovedAndRejectedAnalytics']);
        $this->middleware(['employee:hrmanager,manager'])->only(['leaveAnalytics2', 'overallLeaveAnalytics2', 'leaveApprovedAndRejectedAnalytics2']);
        //$this->middleware(['employee:employee'])->only(['leaveAnalytics3', 'overallLeaveAnalytics3', 'leaveApprovedAndRejectedAnalytics3']);
    }

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

    public function leaveAnalytics2(){
        $overallLeaveYears = array();
        $leaveRequests = LeaveRequest::select('updated_at')->where('managerID', Auth::id())->get();
        foreach ($leaveRequests as $leaveRequest) {
            if(!in_array($leaveRequest->updated_at->year, $overallLeaveYears)){
                array_push($overallLeaveYears, $leaveRequest->updated_at->year);
            }
        }
        rsort($overallLeaveYears);

        $personInCharges = LeaveRequest::with('getEmployee')->select('employeeID', 'leaveStatus')->where('managerID', Auth::id())->distinct()->get();

        $leaveApprovedAndRejectedYears = array();
        $leaveRequests = LeaveRequest::select('updated_at')->whereIn('leaveStatus', [1, 2])->where('managerID', Auth::id())->get();
        foreach ($leaveRequests as $leaveRequest) {
            if(!in_array($leaveRequest->updated_at->year, $leaveApprovedAndRejectedYears)){
                array_push($leaveApprovedAndRejectedYears, $leaveRequest->updated_at->year);
            }
        }
        rsort($leaveApprovedAndRejectedYears);

        return view('leaveAnalytics2', ['overallLeaveYears' => $overallLeaveYears, 'leaveApprovedAndRejectedYears' => $leaveApprovedAndRejectedYears, 'personInCharges' =>$personInCharges]);
    }

    public function overallLeaveAnalytics2($year, $personInCharge)
    {
        $leaveStatus = array();
        $leaveLabel = array();
        $leaveNumber = array();

        if($personInCharge == "null"){
            $statuses = LeaveRequest::select('leaveStatus')->distinct()->where('updated_at', 'like', ''.$year.'%')->where('managerID', Auth::id())->orderBy('leaveStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($leaveStatus, $status->leaveStatus);
                array_push($leaveLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($leaveStatus); $i++) { 
                $leaves = LeaveRequest::where('leaveStatus', $leaveStatus[$i])->where('updated_at', 'like', ''.$year.'%')->where('managerID', Auth::id())->count();
                array_push($leaveNumber, $leaves);
            }
        }
        else{
            $statuses = LeaveRequest::select('leaveStatus')->distinct()
                             ->where('updated_at', 'like', ''.$year.'%')
                             ->orderBy('leaveStatus', 'ASC')
                             ->where('managerID', Auth::id())
                             ->where('employeeID', $personInCharge)
                             ->get();
            foreach ($statuses as $status) {
                array_push($leaveStatus, $status->leaveStatus);
                array_push($leaveLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($leaveStatus); $i++) { 
                $leaves = LeaveRequest::where('leaveStatus', $leaveStatus[$i])
                              ->where('updated_at', 'like', ''.$year.'%')
                              ->where('managerID', Auth::id())
                              ->where('employeeID', $personInCharge)
                              ->count();
                array_push($leaveNumber, $leaves);
            }
        }

        return [array_values($leaveLabel), array_values($leaveNumber)];
    }

    public function leaveApprovedAndRejectedAnalytics2($year, $personInCharge)
    {
        $leaveApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $leaveRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($personInCharge == "null"){
            $approvedLeaves = LeaveRequest::where('updated_at', 'like', ''.$year.'%')->where('managerID', Auth::id())->where('leaveStatus', 2)->get();
            foreach ($approvedLeaves as $approvedLeave) {
                $month = date('m', strtotime($approvedLeave->updated_at));
                $leaveApprovedArrays[$month] = $leaveApprovedArrays[$month] + 1;
            }
    
            $rejectedLeaves = LeaveRequest::where('updated_at', 'like', ''.$year.'%')->where('managerID', Auth::id())->where('leaveStatus', 1)->get();
            foreach ($rejectedLeaves as $rejectedLeave) {
                $month = date('m', strtotime($rejectedLeave->updated_at));
                $leaveRejectedArrays[$month] = $leaveRejectedArrays[$month] + 1;
            }
        }
        else{
            $approvedLeaves = LeaveRequest::select('updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('leaveStatus', 2)
                                   ->where('managerID', Auth::id())
                                   ->where('employeeID', $personInCharge)
                                   ->get();
            foreach ($approvedLeaves as $approvedLeave) {
                $month = date('m', strtotime($approvedLeave->updated_at));
                $leaveApprovedArrays[$month] = $leaveApprovedArrays[$month] + 1;
            }
    
            $rejectedLeaves = LeaveRequest::select('updated_at')
                                    ->where('updated_at', 'like', ''.$year.'%')
                                    ->where('leaveStatus', 1)
                                    ->where('managerID', Auth::id())
                                    ->where('employeeID', $personInCharge)
                                    ->get();
            foreach ($rejectedLeaves as $rejectedLeave) {
                $month = date('m', strtotime($rejectedLeave->updated_at));
                $leaveRejectedArrays[$month] = $leaveRejectedArrays[$month] + 1;
            }
        }

        return [array_values($leaveApprovedArrays), array_values($leaveRejectedArrays)];
    }

    public function leaveAnalytics3(){
        $overallLeaveYears = array();
        $leaveRequests = LeaveRequest::select('updated_at')->where('employeeID', Auth::id())->get();
        foreach ($leaveRequests as $leaveRequest) {
            if(!in_array($leaveRequest->updated_at->year, $overallLeaveYears)){
                array_push($overallLeaveYears, $leaveRequest->updated_at->year);
            }
        }
        rsort($overallLeaveYears);

        $leaveApprovedAndRejectedYears = array();
        $leaveRequests = LeaveRequest::select('updated_at')->whereIn('leaveStatus', [1, 2])->where('employeeID', Auth::id())->get();
        foreach ($leaveRequests as $leaveRequest) {
            if(!in_array($leaveRequest->updated_at->year, $leaveApprovedAndRejectedYears)){
                array_push($leaveApprovedAndRejectedYears, $leaveRequest->updated_at->year);
            }
        }
        rsort($leaveApprovedAndRejectedYears);

        return view('leaveAnalytics3', ['overallLeaveYears' => $overallLeaveYears, 'leaveApprovedAndRejectedYears' => $leaveApprovedAndRejectedYears]);
    }

    public function overallLeaveAnalytics3($year)
    {
        $leaveStatus = array();
        $leaveLabel = array();
        $leaveNumber = array();

        $statuses = LeaveRequest::select('leaveStatus')->distinct()->where('updated_at', 'like', ''.$year.'%')->where('employeeID', Auth::id())->orderBy('leaveStatus', 'ASC')->get();
        foreach ($statuses as $status) {
            array_push($leaveStatus, $status->leaveStatus);
            array_push($leaveLabel, $status->getStatus());
        }
        
        for ($i=0; $i < count($leaveStatus); $i++) { 
            $leaves = LeaveRequest::where('leaveStatus', $leaveStatus[$i])->where('updated_at', 'like', ''.$year.'%')->where('employeeID', Auth::id())->count();
            array_push($leaveNumber, $leaves);
        }

        return [array_values($leaveLabel), array_values($leaveNumber)];
    }

    public function leaveApprovedAndRejectedAnalytics3($year)
    {
        $leaveApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $leaveRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        $approvedLeaves = LeaveRequest::where('updated_at', 'like', ''.$year.'%')->where('employeeID', Auth::id())->where('leaveStatus', 2)->get();
        foreach ($approvedLeaves as $approvedLeave) {
            $month = date('m', strtotime($approvedLeave->updated_at));
            $leaveApprovedArrays[$month] = $leaveApprovedArrays[$month] + 1;
        }

        $rejectedLeaves = LeaveRequest::where('updated_at', 'like', ''.$year.'%')->where('employeeID', Auth::id())->where('leaveStatus', 1)->get();
        foreach ($rejectedLeaves as $rejectedLeave) {
            $month = date('m', strtotime($rejectedLeave->updated_at));
            $leaveRejectedArrays[$month] = $leaveRejectedArrays[$month] + 1;
        }

        return [array_values($leaveApprovedArrays), array_values($leaveRejectedArrays)];
    }
}
