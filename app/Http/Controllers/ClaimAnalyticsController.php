<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaimAnalyticsController extends Controller
{
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

        return view('claimAnalytics', ['overallClaimYears' => $overallClaimYears, 'claimApprovedAndRejectedYears' => $claimApprovedAndRejectedYears, 'departments' =>$departments]);
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
                             ->join('users', function ($join) use ($department){
                                $join->on('claim_requests.claimEmployee', 'users.id')
                                     ->where('users.department', $department);
                             })
                             ->orderBy('claim_requests.claimStatus', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($claimStatus, $status->claimStatus);
                array_push($claimLabel, $status->getStatus());
            }
            
            for ($i=0; $i < count($claimStatus); $i++) { 
                $claims = ClaimRequest::where('claim_requests.claimStatus', $claimStatus[$i])
                              ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                              ->join('users', function ($join) use ($department){
                                $join->on('claim_requests.claimEmployee', 'users.id')
                                     ->where('users.department', $department);
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
                                   ->join('users', function ($join) use ($department) {
                                        $join->on('claim_requests.claimEmployee', 'users.id')
                                            ->where('users.department', $department);
                                    })->get();
            foreach ($approvedClaims as $approvedClaim) {
                $updated_at = new Carbon($approvedClaim->claimUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $claimApprovedArrays[$month] = $claimApprovedArrays[$month] + 1;
            }
    
            $rejectedClaims = ClaimRequest::select('claim_requests.updated_at as claimUpdatedAt')
                                    ->where('claim_requests.updated_at', 'like', ''.$year.'%')
                                    ->where('claim_requests.claimStatus', 1)
                                    ->join('users', function ($join) use ($department) {
                                        $join->on('claim_requests.claimEmployee', 'users.id')
                                            ->select('users.departmet')
                                            ->where('users.department', $department);
                                    })->get();
            foreach ($rejectedClaims as $rejectedClaim) {
                $updated_at = new Carbon($rejectedClaim->claimUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $claimRejectedArrays[$month] = $claimRejectedArrays[$month] + 1;
            }
        }

        return [array_values($claimApprovedArrays), array_values($claimRejectedArrays)];
    }
}
