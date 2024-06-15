@extends('dashboard')
@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    All Jobs Posted
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date Created</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Date Created</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @forelse ($jobs as $job)
                                <tr>
                                    <td>{{ $job->title }}</td>
                                    <td>{{ $job->created_at->format('Md, Y h:i A') }}</td>
                                    <td><a class="btn btn-primary" href="{{ route('job.edit', [$job->id]) }}">Edit</a></td>
                                    <td><a class="btn btn-danger" onclick="return confirm('Do you want to delete item?')" href="{{ route('jobs.delete', [$job->id]) }}">Delete</a></td>
                                </tr>
                                @empty
                                    <td>No Job Posted</td>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
