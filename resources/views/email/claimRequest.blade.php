@component('mail::message')
@if ($claimRequest->claimStatus == 0)
Dear Human Resource Manager,

A new claim request is waiting approval. 

@elseif($claimRequest->claimStatus == 1)
Dear {{ ucwords($claimRequest->getEmployee->firstname) }} {{ ucwords($claimRequest->getEmployee->lastname) }},

Your claim request is rejected.

Reason of claim request rejected: {{ $reason }} 

@else
Dear {{ ucwords($claimRequest->getEmployee->firstname) }} {{ ucwords($claimRequest->getEmployee->lastname) }},

Your claim request is approved.
@endif

<u><b>Claim Request Details</b></u>

@component('mail::table')
@switch($claimRequest->claimStatus)
	@case(0)
		| Claim Type | Claim Amount | Claim Employee | Claim Date | Claim Status |
		|:-----:|:-----------:|:--------:|:--------:|:------:|
		| {{ $claimRequest->getClaimType->claimType }} | {{ $claimRequest->claimAmount }} | {{ ucwords($claimRequest->getEmployee->firstname) }} {{ ucwords($claimRequest->getEmployee->lastname) }} | {{  date("d F Y", strtotime($claimRequest->claimDate)) }} | {{ $claimRequest->getStatus() }} |
		@break
	@default
		| Claim Type | Claim Amount | Claim Date | Claim Status |
		|:-----:|:-----------:|:--------:|:------:|
		| {{ $claimRequest->getClaimType->claimType }} | {{ $claimRequest->claimAmount }} | {{  date("d F Y", strtotime($claimRequest->claimDate)) }} | {{ $claimRequest->getStatus() }} |
@endswitch
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
