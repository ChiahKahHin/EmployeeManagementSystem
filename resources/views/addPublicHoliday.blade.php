@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Add Public Holiday
@endsection

@section('pageTitle')
    Add Public Holiday
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Add Public Holiday</h4>
            </div>
        </div>

        <form action="{{ route('addPublicHoliday') }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Public Holiday Name <i>(must be unique)</i></label>
                        <input class="form-control @error('name') form-control-danger @enderror" type="text" name="name" placeholder="Enter public holiday name" value="{{ old('name') }}" required>
						
						@error("name")
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
						<label>Public Holiday Date</label>
						<input class="form-control @error('date') form-control-danger @enderror" type="date" min="@php echo date("Y-m-d") @endphp" name="date" placeholder="Select public holiday date" value="{{ old('date') }}" required>
						
						@error("date")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Add Public Holiday</button>
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