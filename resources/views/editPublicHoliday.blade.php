@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Edit Public Holiday
@endsection

@section('pageTitle')
    Edit Public Holiday
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Edit Public Holiday</h4>
            </div>
        </div>

        <form action="{{ route('editPublicHoliday', ['id' => $publicHoliday->id]) }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Public Holiday Name <i>(must be unique)</i></label>
                        <input class="form-control @error('name') form-control-danger @enderror" type="text" name="name" placeholder="Enter public holiday name" value="{{ old('name', $publicHoliday->name) }}" required>
						
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
						<input class="form-control @error('date') form-control-danger @enderror" type="date" min="{{ $publicHoliday->date }}" name="date" placeholder="Select public holiday date" value="{{ old('date', $publicHoliday->date) }}" required>
						
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
					<button type="submit" class="btn btn-primary btn-block">Edit Public Holiday</button>
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
				//timer:5000
			});
		</script>
	@endif
@endsection