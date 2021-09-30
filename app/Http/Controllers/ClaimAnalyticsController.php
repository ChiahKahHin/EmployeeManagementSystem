<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin'])->only(['claimAnalytics', 'overallClaimAnalytics', 'claimApprovedAndRejectedAnalytics']);
        $this->middleware(['employee:hrmanager,manager'])->only(['claimAnalytics2', 'overallClaimAnalytics2', 'claimApprovedAndRejectedAnalytics2']);
        //$this->middleware(['employee:employee'])->only(['claimAnalytics3', 'overallClaimAnalytics3', 'claimApprovedAndRejectedAnalytics3']);
    }

    public function claimAnalytics()
    {
        $overallClaimYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $overallClaimYears)){
                array_push($overallClaimYears, $claimRequest->updated_at->year);
            }
        }
        rsort($overallClaimYears);

        $departments = ClaimRequest::with('getEmployee.getDepartment')->select('claimEmployee', 'claimStatus')->distinct()->get();

        $claimApprovedAndRejectedYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->whereIn('claimStatus', [1, 2])->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $claimApprovedAndRejectedYears)){
                array_push($claimApprovedAndRejectedYears, $claimRequest->updated_at->year);
            }
        }
        rsort($claimApprovedAndRejectedYears);

        $claimTypeApprovedYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->where('claimStatus', 2)->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $claimTypeApprovedYears)){
                array_push($claimTypeApprovedYears, $claimRequest->updated_at->year);
            }
        }
        rsort($claimTypeApprovedYears);

        $claimTypes = ClaimRequest::with('getClaimType')->select('claimType', 'claimStatus')->where('claimStatus', 2)->distinct()->get();

        return view('claimAnalytics', ['overallClaimYears' => $overallClaimYears, 
                                       'claimApprovedAndRejectedYears' => $claimApprovedAndRejectedYears, 
                                       'departments' => $departments,
                                       'claimTypeApprovedYears' => $claimTypeApprovedYears,
                                       'claimTypes' => $claimTypes
                                      ]);
    }

    public function overallClaimAnalytics($year, $department)
    {
        $claimStatus = array();
        $claimLabel = array();
        $claimNumber = array();

        if($department == "null"){
            $statuses = ClaimRequest::select('claimStatus')->distinct()->where('updated_at', 'like', ''.$year.'%')->orderBy('claimStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($claimStatus, $status->claimStatus);
                array_push($claimLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($claimStatus); $i++) { 
                $claims = ClaimRequest::where('claimStatus', $claimStatus[$i])->where('updated_at', 'like', ''.$year.'%')->count();
                array_push($claimNumber, $claims);
            }
        }
        else{
            $statuses = ClaimRequest::select('claim_requests.claimStatus')->distinct()
                             ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                             ->join('employee', function ($join) use ($department){
                                $join->on('claim_requests.claimEmployee', 'employee.id')
                                     ->where('employee.department', $department);
                             })
                             ->orderBy('claim_requests.claimStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($claimStatus, $status->claimStatus);
                array_push($claimLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($claimStatus); $i++) { 
                $claims = ClaimRequest::where('claim_requests.claimStatus', $claimStatus[$i])
                              ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                              ->join('employee', function ($join) use ($department){
                                $join->on('claim_requests.claimEmployee', 'employee.id')
                                     ->where('employee.department', $department);
                             })
                              ->count();
                array_push($claimNumber, $claims);
            }
        }

        return [array_values($claimLabel), array_values($claimNumber)];
    }

    public function claimApprovedAndRejectedAnalytics($year, $department)
    {
        $claimApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $claimRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($department == "null"){
            $approvedClaims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 2)->get();
            foreach ($approvedClaims as $approvedClaim) {
                $month = date('m', strtotime($approvedClaim->updated_at));
                $claimApprovedArrays[$month] = $claimApprovedArrays[$month] + 1;
            }
    
            $rejectedClaims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 1)->get();
            foreach ($rejectedClaims as $rejectedClaim) {
                $month = date('m', strtotime($rejectedClaim->updated_at));
                $claimRejectedArrays[$month] = $claimRejectedArrays[$month] + 1;
            }
        }
        else{
            $approvedClaims = ClaimRequest::select('claim_requests.updated_at as claimUpdatedAt')
                                   ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                                   ->where('claim_requests.claimStatus', 2)
                                   ->join('employee', function ($join) use ($department) {
                                        $join->on('claim_requests.claimEmployee', 'employee.id')
                                            ->where('employee.department', $department);
                                    })->get();
            foreach ($approvedClaims as $approvedClaim) {
                $updated_at = new Carbon($approvedClaim->claimUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $claimApprovedArrays[$month] = $claimApprovedArrays[$month] + 1;
            }
    
            $rejectedClaims = ClaimRequest::select('claim_requests.updated_at as claimUpdatedAt')
                                    ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                                    ->where('claim_requests.claimStatus', 1)
                                    ->join('employee', function ($join) use ($department) {
                                        $join->on('claim_requests.claimEmployee', 'employee.id')
                                            ->select('employee.departmet')
                                            ->where('employee.department', $department);
                                    })->get();
            foreach ($rejectedClaims as $rejectedClaim) {
                $updated_at = new Carbon($rejectedClaim->claimUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $claimRejectedArrays[$month] = $claimRejectedArrays[$month] + 1;
            }
        }

        return [array_values($claimApprovedArrays), array_values($claimRejectedArrays)];
    }

    public function claimTypeApprovedAnalytics($year, $department, $claimType)
    {
        $claimTypeArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($department == "null" && $claimType == "null"){
            $claims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 2)->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        elseif($claimType == "null"){
            $claims = ClaimRequest::select('claim_requests.claimAmount', 'claim_requests.updated_at as claimUpdatedAt')
                                   ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                                   ->where('claim_requests.claimStatus', 2)
                                   ->join('employee', function ($join) use ($department) {
                                        $join->on('claim_requests.claimEmployee', 'employee.id')
                                            ->where('employee.department', $department);
                                    })->get();
            foreach ($claims as $claim) {
                $updated_at = new Carbon($claim->claimUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        elseif($department == "null"){
            $claims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimType', $claimType)->where('claimStatus', 2)->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        else{
            $claims = ClaimRequest::select('claim_requests.claimAmount', 'claim_requests.updated_at as claimUpdatedAt')
                                   ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                                   ->where('claim_requests.claimStatus', 2)
                                   ->where('claim_requests.claimType', $claimType)
                                   ->join('employee', function ($join) use ($department) {
                                        $join->on('claim_requests.claimEmployee', 'employee.id')
                                            ->where('employee.department', $department);
                                    })->get();
            foreach ($claims as $claim) {
                $updated_at = new Carbon($claim->claimUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }

        return array_values($claimTypeArrays);
    }

    public function claimAnalytics2()
    {
        $overallClaimYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->where('claimManager', Auth::id())->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $overallClaimYears)){
                array_push($overallClaimYears, $claimRequest->updated_at->year);
            }
        }
        rsort($overallClaimYears);

        $personInCharges = ClaimRequest::with('getEmployee.getEmployeeInfo')->select('claimEmployee', 'claimStatus')->where('claimManager', Auth::id())->distinct()->get();

        $claimApprovedAndRejectedYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->whereIn('claimStatus', [1, 2])->where('claimManager', Auth::id())->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $claimApprovedAndRejectedYears)){
                array_push($claimApprovedAndRejectedYears, $claimRequest->updated_at->year);
            }
        }
        rsort($claimApprovedAndRejectedYears);

        $claimTypeApprovedYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->where('claimStatus', 2)->where('claimManager', Auth::id())->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $claimTypeApprovedYears)){
                array_push($claimTypeApprovedYears, $claimRequest->updated_at->year);
            }
        }
        rsort($claimTypeApprovedYears);

        $claimTypes = ClaimRequest::with('getClaimType')->select('claimType', 'claimStatus')->where('claimManager', Auth::id())->where('claimStatus', 2)->distinct()->get();

        return view('claimAnalytics2', ['overallClaimYears' => $overallClaimYears, 
                                        'claimApprovedAndRejectedYears' => $claimApprovedAndRejectedYears, 
                                        'personInCharges' => $personInCharges,
                                        'claimTypeApprovedYears' => $claimTypeApprovedYears,
                                        'claimTypes' => $claimTypes
                                        ]);
    }

    public function overallClaimAnalytics2($year, $personInCharge)
    {
        $claimStatus = array();
        $claimLabel = array();
        $claimNumber = array();

        if($personInCharge == "null"){
            $statuses = ClaimRequest::select('claimStatus')->distinct()->where('updated_at', 'like', ''.$year.'%')->where('claimManager', Auth::id())->orderBy('claimStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($claimStatus, $status->claimStatus);
                array_push($claimLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($claimStatus); $i++) { 
                $claims = ClaimRequest::where('claimStatus', $claimStatus[$i])->where('updated_at', 'like', ''.$year.'%')->where('claimManager', Auth::id())->count();
                array_push($claimNumber, $claims);
            }
        }
        else{
            $statuses = ClaimRequest::select('claimStatus')->distinct()
                             ->where('updated_at', 'like', ''.$year.'%')
                             ->where('claimManager', Auth::id())
                             ->where('claimEmployee', $personInCharge)
                             ->orderBy('claimStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($claimStatus, $status->claimStatus);
                array_push($claimLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($claimStatus); $i++) { 
                $claims = ClaimRequest::where('claim_requests.claimStatus', $claimStatus[$i])
                              ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                              ->where('claimManager', Auth::id())
                              ->where('claimEmployee', $personInCharge)
                              ->count();
                array_push($claimNumber, $claims);
            }
        }

        return [array_values($claimLabel), array_values($claimNumber)];
    }

    public function claimApprovedAndRejectedAnalytics2($year, $personInCharge)
    {
        $claimApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $claimRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($personInCharge == "null"){
            $approvedClaims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 2)->where('claimManager', Auth::id())->get();
            foreach ($approvedClaims as $approvedClaim) {
                $month = date('m', strtotime($approvedClaim->updated_at));
                $claimApprovedArrays[$month] = $claimApprovedArrays[$month] + 1;
            }
    
            $rejectedClaims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 1)->where('claimManager', Auth::id())->get();
            foreach ($rejectedClaims as $rejectedClaim) {
                $month = date('m', strtotime($rejectedClaim->updated_at));
                $claimRejectedArrays[$month] = $claimRejectedArrays[$month] + 1;
            }
        }
        else{
            $approvedClaims = ClaimRequest::select('updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('claimStatus', 2)
                                   ->where('claimManager', Auth::id())
                                   ->where('claimEmployee', $personInCharge)
                                   ->get();
            foreach ($approvedClaims as $approvedClaim) {
                $month = date('m', strtotime($approvedClaim->updated_at));
                $claimApprovedArrays[$month] = $claimApprovedArrays[$month] + 1;
            }
    
            $rejectedClaims = ClaimRequest::select('updated_at')
                                    ->where('updated_at', 'like', ''.$year.'%')
                                    ->where('claimStatus', 1)
                                    ->where('claimManager', Auth::id())
                                    ->where('claimEmployee', $personInCharge)
                                    ->get();
            foreach ($rejectedClaims as $rejectedClaim) {
                $month = date('m', strtotime($rejectedClaim->updated_at));
                $claimRejectedArrays[$month] = $claimRejectedArrays[$month] + 1;
            }
        }

        return [array_values($claimApprovedArrays), array_values($claimRejectedArrays)];
    }

    public function claimTypeApprovedAnalytics2($year, $personInCharge, $claimType)
    {
        $claimTypeArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($personInCharge == "null" && $claimType == "null"){
            $claims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 2)->where('claimManager', Auth::id())->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        elseif($claimType == "null"){
            $claims = ClaimRequest::select('claimAmount', 'updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('claimStatus', 2)
                                   ->where('claimManager', Auth::id())
                                   ->where('claimEmployee', $personInCharge)
                                   ->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        elseif($personInCharge == "null"){
            $claims = ClaimRequest::select('claimAmount', 'updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('claimStatus', 2)
                                   ->where('claimManager', Auth::id())
                                   ->where('claimType', $claimType)
                                   ->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        else{
            $claims = ClaimRequest::select('claimAmount', 'updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('claimStatus', 2)
                                   ->where('claimManager', Auth::id())
                                   ->where('claimType', $claimType)
                                   ->where('claimEmployee', $personInCharge)
                                   ->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }

        return array_values($claimTypeArrays);
    }

    public function claimAnalytics3()
    {
        $overallClaimYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->where('claimEmployee', Auth::id())->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $overallClaimYears)){
                array_push($overallClaimYears, $claimRequest->updated_at->year);
            }
        }
        rsort($overallClaimYears);

        $claimApprovedAndRejectedYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->whereIn('claimStatus', [1, 2])->where('claimEmployee', Auth::id())->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $claimApprovedAndRejectedYears)){
                array_push($claimApprovedAndRejectedYears, $claimRequest->updated_at->year);
            }
        }
        rsort($claimApprovedAndRejectedYears);

        $claimTypeApprovedYears = array();
        $claimRequests = ClaimRequest::select('updated_at')->where('claimStatus', 2)->where('claimEmployee', Auth::id())->get();
        foreach ($claimRequests as $claimRequest) {
            if(!in_array($claimRequest->updated_at->year, $claimTypeApprovedYears)){
                array_push($claimTypeApprovedYears, $claimRequest->updated_at->year);
            }
        }
        rsort($claimTypeApprovedYears);

        $claimTypes = ClaimRequest::with('getClaimType')->select('claimType', 'claimStatus')->where('claimEmployee', Auth::id())->where('claimStatus', 2)->distinct()->get();

        return view('claimAnalytics3', ['overallClaimYears' => $overallClaimYears, 
                                        'claimApprovedAndRejectedYears' => $claimApprovedAndRejectedYears,
                                        'claimTypeApprovedYears' => $claimTypeApprovedYears,
                                        'claimTypes' => $claimTypes
                                        ]);
    }

    public function overallClaimAnalytics3($year)
    {
        $claimStatus = array();
        $claimLabel = array();
        $claimNumber = array();

        $statuses = ClaimRequest::select('claimStatus')->distinct()->where('updated_at', 'like', ''.$year.'%')->where('claimEmployee', Auth::id())->orderBy('claimStatus', 'ASC')->get();
        foreach ($statuses as $status) {
            array_push($claimStatus, $status->claimStatus);
            array_push($claimLabel, $status->getStatus());
        }
        
        for ($i=0; $i < count($claimStatus); $i++) { 
            $claims = ClaimRequest::where('claimStatus', $claimStatus[$i])->where('updated_at', 'like', ''.$year.'%')->where('claimEmployee', Auth::id())->count();
            array_push($claimNumber, $claims);
        }

        return [array_values($claimLabel), array_values($claimNumber)];
    }

    public function claimApprovedAndRejectedAnalytics3($year)
    {
        $claimApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $claimRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        $approvedClaims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 2)->where('claimEmployee', Auth::id())->get();
        foreach ($approvedClaims as $approvedClaim) {
            $month = date('m', strtotime($approvedClaim->updated_at));
            $claimApprovedArrays[$month] = $claimApprovedArrays[$month] + 1;
        }

        $rejectedClaims = ClaimRequest::where('updated_at', 'like', ''.$year.'%')->where('claimStatus', 1)->where('claimEmployee', Auth::id())->get();
        foreach ($rejectedClaims as $rejectedClaim) {
            $month = date('m', strtotime($rejectedClaim->updated_at));
            $claimRejectedArrays[$month] = $claimRejectedArrays[$month] + 1;
        }

        return [array_values($claimApprovedArrays), array_values($claimRejectedArrays)];
    }

    public function claimTypeApprovedAnalytics3($year, $claimType)
    {
        $claimTypeArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        if($claimType == "null"){
            $claims = ClaimRequest::select('claimAmount', 'updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('claimStatus', 2)
                                   ->where('claimEmployee', Auth::id())
                                   ->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }
        else{
            $claims = ClaimRequest::select('claimAmount', 'updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('claimStatus', 2)
                                   ->where('claimEmployee', Auth::id())
                                   ->where('claimType', $claimType)
                                   ->get();
            foreach ($claims as $claim) {
                $month = date('m', strtotime($claim->updated_at));
                $claimTypeArrays[$month] = $claimTypeArrays[$month] + $claim->claimAmount;
            }
        }        

        return array_values($claimTypeArrays);
    }
}
