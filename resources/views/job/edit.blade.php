@extends('dashboard')
@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">

                <h1>Edit {{ $listing->title }} job</h1>
                <form action="{{ route('job.update', [$listing->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                    {{-- image field --}}
                    <div class="form-group">
                        <label for="title">Feature Image</label>
                        <input type="file" name="feature_image" id="feature_image" class="form-control">
                        @if($errors->has('feature_image'))
                            <div class="error"> {{$errors->first('feature_image')}}  </div>
                        @endif
                    </div>

                    {{-- title page --}}
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" value="{{ $listing->title }}" name="title" id="title" class="form-control">
                        @if($errors->has('title'))
                            <div class="error"> {{$errors->first('title')}}  </div>
                        @endif
                    </div>

                    {{-- ckeditor div description field--}}
                    <div class="form-group" id="editor">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control">{{ $listing->description }}</textarea>
                        @if($errors->has('description'))
                            <div class="error"> {{$errors->first('description')}}  </div>
                        @endif
                    </div>

                    {{-- ckeditor div roles div--}}
                    <div class="form-group" id="roles">
                        <label for="description">Roles and Responsibility</label>
                        <textarea name="roles" class="form-control">{{ $listing->roles }}</textarea>
                        @if($errors->has('roles'))
                            <div class="error"> {{$errors->first('roles')}}  </div>
                        @endif
                    </div>

                    {{-- job type field --}}
                    <div class="form-group">
                        <label>Job types</label>

                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="job_type" id="Fulltime" value="Fulltime" {{ $listing->job_type === 'Fulltime' ? 'checked' : '' }}>
                            <label for="Fulltime" class="form-check-label">Fulltime</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="job_type" id="Parttime" value="Parttime" {{ $listing->job_type === 'Parttime' ? 'checked' : '' }}>
                            <label for="Parttime" class="form-check-label">Parttime</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="job_type" id="casual" value="Casual" {{ $listing->job_type === 'Casual' ? 'checked' : '' }}>
                            <label for="casual" class="form-check-label">Casual</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="job_type" id="Contract" value="Contract" {{ $listing->job_type === 'Contract' ? 'checked' : '' }}>
                            <label for="Contract" class="form-check-label">Contract</label>
                        </div>
                        @if($errors->has('job_type'))
                            <div class="error"> {{$errors->first('job_type')}}  </div>
                        @endif
                    </div>

                    {{-- address field --}}
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" value="{{ $listing->address }}" name="address" id="address" class="form-control">
                        @if($errors->has('address'))
                            <div class="error"> {{$errors->first('address')}}  </div>
                        @endif
                    </div>

                    {{-- salary field --}}
                    <div class="form-group">
                        <label for="address">Salary</label>
                        <input type="text" value="{{ $listing->salary }}" name="salary" id="salary" class="form-control">
                        @if($errors->has('salary'))
                            <div class="error"> {{$errors->first('salary')}}  </div>
                        @endif
                    </div>

                    {{-- close date field --}}
                    <div class="form-group">
                        <label for="date">Application closing date</label>
                        <input type="date" value="{{ $listing->application_close_date }}" name="application_close_date" id="datepicker" class="form-control">
                        @if($errors->has('date'))
                            <div class="error"> {{$errors->first('application_close_date')}}  </div>
                        @endif
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <style>
        .note-insert {
            display: none!important;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .ck-toolbar_grouping{
            margin-top: 20px !important;
        }
    </style>

@endsection

{{-- @section('script')

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

@endsection --}}
