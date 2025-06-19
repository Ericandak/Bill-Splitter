@extends('layouts.app')

@section('content')
<div class="row justify-content-center fade-in">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">My Bills</h1>
            <a href="{{ route('bills.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New Bill
            </a>
        </div>

        @if($bills->count() > 0)
            <div class="row">
                @foreach($bills as $bill)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">{{ $bill->name }}</h5>
                                    <span class="badge bg-primary">
                                        {{ $bill->friends->count() }} Friends
                                    </span>
                                </div>
                                
                                <p class="card-text text-muted small mb-3">
                                    <i class="fas fa-users me-2"></i>
                                    {{ $bill->friends->pluck('name')->join(', ') }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $bill->created_at->diffForHumans() }}
                                    </small>
                                    <a href="{{ route('bills.show', $bill) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-receipt fa-4x text-muted"></i>
                </div>
                <h3 class="h4 mb-3">No bills yet</h3>
                <p class="text-muted mb-4">Create your first bill to start splitting expenses with friends!</p>
                <a href="{{ route('bills.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Bill
                </a>
            </div>
        @endif
    </div>
</div>
@endsection