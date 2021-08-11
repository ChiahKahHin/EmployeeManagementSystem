@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Edit Department
@endsection

@section('pageTitle')
    Edit Department
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Edit Department</h4>
            </div>
        </div>

        <form action="{{ route('editDepartment', ['id' => $departments->id]) }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Department Code</label>
                        <input class="form-control @error('departmentCode') form-control-danger @enderror" type="text" name="departmentCode" placeholder="Enter department code" value="{{ old('departmentCode', $departments->departmentCode) }}">
						
						@error("departmentCode")
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
                        <label>Department Name</label>
                        <input class="form-control @error('departmentName') form-control-danger @enderror" type="text" name="departmentName" placeholder="Enter department name" value="{{ old('departmentName', $departments->departmentName) }}">
						
						@error("departmentName")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Edit Department</button>
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