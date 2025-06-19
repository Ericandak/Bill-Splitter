@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h2 class="h4 mb-0">Friend Requests</h2>
                </div>

                <div class="card-body">
                    @if($receivedRequests->count() > 0)
                        <div class="list-group">
                            @foreach($receivedRequests as $request)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $request->sender->name }}</h6>
                                        <small class="text-muted">{{ $request->sender->email }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <form action="{{ route('friends.accept', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm me-2">
                                                <i class="fas fa-check me-1"></i>Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.reject', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No pending friend requests</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
