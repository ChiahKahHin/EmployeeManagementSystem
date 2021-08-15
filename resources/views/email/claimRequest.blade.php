@component('mail::message')
Dear Human Resource Manager,

A new claim request is waiting approval.

<u><b>Claim Request Details</b></u>

@component('mail::table')
| Claim Type | Claim Amount | Claim Employee | Claim Date | Claim Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $claimRequest->getClaimType->claimType }} | {{ $claimRequest->claimAmount }} | {{ ucwords($claimRequest->getEmployee->firstname) }} {{ ucwords($claimRequest->getEmployee->lastname) }} | {{  date("d F Y", strtotime($claimRequest->claimDate)) }} | {{ $claimRequest->getStatus() }} |
@endcomponent

@component('mail::button', ['url' => url(Redirect::intended("/viewClaimRequest/$claimRequest->id")->getTargetUrl())])
View Claim Request Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
