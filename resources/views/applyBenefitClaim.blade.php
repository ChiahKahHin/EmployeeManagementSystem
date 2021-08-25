@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Apply Benefit Claim
@endsection

@section('pageTitle')
	Apply Benefit Claim
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Apply Benefit Claim</h4>
			</div>
		</div>
		<form action="{{ route('applyBenefitClaim') }}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Claim Type</label>
						
						<select class="form-control selectpicker @error('claimType') form-control-danger @enderror" id="claimType" name="claimType" onchange="checkClaimAmount();" required>
							<option value="" selected disabled hidden>Select claim type</option>
							
							@foreach ($claimTypes as $claimType)
								<option value="{{ $claimType->id }}" data-claimType="{{ $claimType->claimType }}" data-claimAmount="{{ $claimType->claimAmount }}" {{ (old('claimType') == $claimType->id ? "selected": null) }}>{{ $claimType->claimType }}</option>
							@endforeach
						</select>
						
						@error("claimType")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label id="claimAmountLabel">Claim Amount</label>
						<input class="form-control @error('claimAmount') form-control-danger @enderror" type="number" min="1" step="1" id="claimAmount" name="claimAmount" placeholder="Enter benefit claim amount" value="{{ old('claimAmount') }}" onkeyup="checkClaimAmount();" required>
						
						@error("claimAmount")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Claim Date</label>
                        <input class="form-control @error('claimDate') form-control-danger @enderror" type="date" max="@php echo date("Y-m-d") @endphp" name="claimDate" placeholder="Select claim date" value="{{ old('claimDate') }}" required>
						
						@error("claimDate")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Claim Description</label>
						<textarea class="form-control @error('claimDescription') form-control-danger @enderror" id="claimDescription" name="claimDescription" style="min-height:100px; max-height:200px; height:45px; resize: vertical;" placeholder="Enter claim description" maxlength="65535" onkeyup="countWords()" data-gramm_editor="false" required>{{ old('claimDescription') }}</textarea>
						
						<div id="claimDescription_word_count" class="text-sm" style="text-align: right"></div>
						
						@error("claimDescription")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Claim Supporting Documents <i>(e.g. medical certificate/ receipts)</i></label>							
						<input type="file" class="form-control-file form-control height-auto @error('claimAttachment') form-control-danger @enderror" id="claimAttachment" name="claimAttachment" accept=".pdf,.jpg,.png,.jpeg" required>
						<label style="font-size: 14px;"><i>(Only attachments with .pdf, .jpg, .png, .jpeg extension can be accepted)</i></label>
						
						@error("claimAttachment")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Apply Benefit Claim</button>
				</div>
			</div>
		</form>
	</div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				type: 'success',
				confirmButtonClass: 'btn btn-success',
				timer: 5000
			});
		</script>
	@endif
@endsection

@section("script")
	<script>
		$(document).ready(function() {
			countWords();
			checkClaimAmount();
		});

		function countWords(){
			var words = document.getElementById('claimDescription');
			$('#claimDescription_word_count').text(words.value.length + "/" + words.maxLength);
		};

		function checkClaimAmount() {
			var claimTypeInput = document.getElementById('claimType');
			if(claimTypeInput.value != ""){
				var claimType = claimTypeInput.options[claimTypeInput.selectedIndex].getAttribute('data-claimType');
				var claimAmount = claimTypeInput.options[claimTypeInput.selectedIndex].getAttribute('data-claimAmount');
				
				var totalClaimed = 0;
		
				@foreach ($approvedClaims as $approvedClaim)
					var claimDate = new Date(Date.parse("{{ date("d F Y", strtotime($approvedClaim->claimDate)) }}"));
					var currentYear = new Date();
					
					if(claimDate.getFullYear() == currentYear.getFullYear()){
						if(claimTypeInput.value == {{ $approvedClaim->claimType }}){
							totalClaimed += {{ $approvedClaim->claimAmount }};
						}
					}
				@endforeach
		
				var remainingAmount = claimAmount - totalClaimed;
				var remainingBalance = remainingAmount;
				document.getElementById('claimAmount').setAttribute('max', remainingAmount);

				claimAmountInput = document.getElementById('claimAmount').value;
				if (claimAmountInput != "") {
					remainingAmount = remainingAmount - claimAmountInput;
				}

				if (claimAmountInput != "" && claimAmountInput < 1) {
					document.getElementById('claimAmountLabel').innerHTML = "Claim Amount (Minimum is RM1)";
				}
				else{
					if(remainingBalance == claimAmountInput){
						document.getElementById('claimAmountLabel').innerHTML = "Claim Amount (Maximum amount reached)";
					}
					else if (remainingAmount >= 0) {
						document.getElementById('claimAmountLabel').innerHTML = "Claim Amount (Remaining Amount: RM" + remainingAmount +")";
					}
					else{
						document.getElementById('claimAmountLabel').innerHTML = "Claim Amount (Exceed the available amount)";
					}
				}
			}
		}
	</script>
@endsection
