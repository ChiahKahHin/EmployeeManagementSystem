@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Edit Memorandum
@endsection

@section('pageTitle')
	Edit Memorandum
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Edit Memorandum</h4>
			</div>
		</div>

		<form action="{{ route('editMemo', ['id' => $memo->id]) }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Memo Title</label>
						<input class="form-control @error('memoTitle') form-control-danger @enderror" type="text" name="memoTitle" placeholder="Enter memo title" value="{{ old('memoTitle', $memo->memoTitle) }}" required>
						
						@error("memoTitle")
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
						<label>Memo Description</label>
						<textarea class="form-control @error('memoDescription') form-control-danger @enderror" id="memoDescription" name="memoDescription" style="min-height:100px; max-height:200px; height:45px; resize: vertical;" placeholder="Enter memo description" maxlength="65535" onkeyup="countWords()" data-gramm_editor="false" required>{{ old('memoDescription', $memo->memoDescription) }}</textarea>
						
						<div id="memoDescription_word_count" class="text-sm" style="text-align: right"></div>
						
						@error("memoDescription")
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
						<label>Memo Date</label>
                        <input class="form-control @error('memoDate') form-control-danger @enderror" type="date" name="memoDate" placeholder="Select claim date" value="{{ old('memoDate', $memo->memoDate) }}" required>
						
						@error("memoDate")
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
						<label>Memo Recipient <i>(By Departments)</i></label>
						
						<select class="form-control selectpicker @error('memoRecipient') form-control-danger @enderror" id="memoRecipient" name="memoRecipient" required>
							<option value="" selected disabled hidden>Select memo recipent</option>
							<option value="0" {{ (old('memoRecipient', $memo->memoRecipient) == "0" ? "selected": null) }}>All departments</option>
							
							<optgroup label="Each department">
							@foreach ($departments as $department)
								<option value="{{ $department->id }}" {{ (old('memoRecipient', $memo->memoRecipient) == $department->id ? "selected": null) }}>{{ $department->departmentName }}</option>
							@endforeach
						</select>
						
						@error("memoRecipient")
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
						<label>Schedule Memorandum?</label>
						<input class="form-control switch-btn" type="checkbox" id="scheduledMemo" name="scheduledMemo" onchange="showInput();" data-size="small" data-color="#0099ff" {{ (old('scheduledMemo')? "checked": null) }} {{ (($memo->memoScheduled != null)? "checked" : null)}}>
					</div>
				</div>
			</div>

			<div id="showScheduledMemoDate" style="display: none;">
				<div class="row">
					<div class="col-md-6">
						<label>Scheduled Memorandum Date & Time</label>
						<input class="form-control @error('scheduledMemoDateTime') form-control-danger @enderror" type="datetime-local" min="@php echo date("Y-m-d\TH:i", strtotime("+1 day")) @endphp" id="scheduledMemoDateTime" name="scheduledMemoDateTime" placeholder="Select schedule memo date & time" value="{{ old('scheduledMemoDateTime', date("Y-m-d\TH:i", strtotime($memo->memoScheduled))) }}">
						
						@error("scheduledMemoDateTime")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
				<br>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Edit Memorandum</button>
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
			showInput();
		});

		function countWords(){
			var words = document.getElementById('memoDescription');
			$('#memoDescription_word_count').text(words.value.length + "/" + words.maxLength);
		};

		function showInput() {
			var checked = document.getElementById('scheduledMemo').checked;
			if(checked == true){
				document.getElementById('showScheduledMemoDate').removeAttribute('style');
				document.getElementById('scheduledMemoDateTime').setAttribute('required', '');
				document.getElementById('scheduledMemoDateTime').value = "{{ date('Y-m-d\TH:i', strtotime($memo->memoScheduled)) }}";
			}
			else{
				document.getElementById('showScheduledMemoDate').setAttribute('style', 'display:none;');
				document.getElementById('scheduledMemoDateTime').removeAttribute('required');
				document.getElementById('scheduledMemoDateTime').value = "";
			}
		}
	</script>
@endsection