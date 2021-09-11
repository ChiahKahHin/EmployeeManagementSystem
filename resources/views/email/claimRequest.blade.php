@component('mail::message')
@php
	if($claimRequest->claimDelegateManager == null){
		$managerName = $claimRequest->getManager->getFullName();
	}
	else{
		$managerName = $claimRequest->getDelegateManager->getFullName();
	}
@endphp
@if ($claimRequest->claimStatus == 0)
Dear {{ $managerName }},

A new claim request is waiting approval. 

@elseif($claimRequest->claimStatus == 1)
Dear {{ $claimRequest->getEmployee->getFullName() }},

Your claim request is rejected.

Reason of claim request rejected: {{ $reason }} 

@elseif($claimRequest->claimStatus == 2)
Dear {{ $claimRequest->getEmployee->getFullName() }},

Your claim request is approved.

@else
Dear {{ $managerName }},

This claim request is cancelled.
@endif

<u><b>Claim Request Details</b></u>

@component('mail::table')
| Claim Type | Claim Amount | Claim Employee | Claim Date | Claim Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $claimRequest->getClaimType->claimType }} | {{ $claimRequest->claimAmount }} | {{ $claimRequest->getEmployee->getFullName() }} | {{  date("d F Y", strtotime($claimRequest->claimDate)) }} | {{ $claimRequest->getStatus() }} {!! ($claimRequest->claimDelegateManager != null && ($claimRequest->claimStatus == 0 || $claimRequest->claimStatus == 3)) ? "<i>(Delegated)</i>" : null !!}|
@endcomponent

@component('mail::button', ['url' => url(Redirect::intended("/viewClaimRequest/$claimRequest->id")->getTargetUrl())])
View Claim Request Details
@endcomponent

@if ($claimRequest->claimStatus == 1)
Please apply for another claim request, if you wish to claim this benefit again.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
