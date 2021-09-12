@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Edit Position
@endsection

@section('pageTitle')
    Edit Position
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Edit Position</h4>
            </div>
        </div>

        <form action="{{ route('editPosition', ['id' => $position->id]) }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Position Name</label>
                        <input class="form-control @error('positionName') form-control-danger @enderror" type="text" name="positionName" placeholder="Enter position name" value="{{ old('positionName', $position->positionName) }}" required>
						
						@error("positionName")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Edit Position</button>
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