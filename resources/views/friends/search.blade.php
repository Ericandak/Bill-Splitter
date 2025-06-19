@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Search and Results Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h2 class="h4 mb-0">Find Friends</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('friends.search') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" 
                                   name="query" 
                                   class="form-control" 
                                   placeholder="Search by name or email" 
                                   value="{{ request('query') }}"
                                   autocomplete="off">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                        </div>
                    </form>

                    @if(request('query'))
                        @if($users->count() > 0)
                            <div class="list-group fade-in">
                                @foreach($users as $user)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <div class="request-button" data-user-id="{{ $user->id }}">
                                            @if($user->hasPendingFriendRequestFrom(auth()->user()))
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-clock me-1"></i>Request Sent
                                                </button>
                                            @elseif($user->isFriendWith(auth()->user()))
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Friends
                                                </span>
                                            @else
                                                <form action="{{ route('friends.request', $user) }}" 
                                                      method="POST" 
                                                      class="friend-request-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm request-btn">
                                                        <i class="fas fa-user-plus me-1"></i>Send Request
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 fade-in">
                                <p class="text-muted mb-0">No users found matching "{{ request('query') }}"</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Friends List Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h3 class="h5 mb-0">My Friends</h3>
                </div>
                <div class="card-body">
                    @if(auth()->user()->friends->count() > 0)
                        <div class="list-group">
                            @foreach(auth()->user()->friends as $friend)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar-circle">
                                            {{ substr($friend->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $friend->name }}</h6>
                                        <small class="text-muted">{{ $friend->email }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No friends yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.request-btn.sending {
    position: relative;
    pointer-events: none;
}

.request-btn.sending::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: rgba(255,255,255,0.8);
    border-radius: inherit;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.list-group-item {
    transition: all 0.2s;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.friend-request-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const button = this.querySelector('.request-btn');
            button.classList.add('sending');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                const container = this.closest('.request-button');
                container.innerHTML = `
                    <button class="btn btn-secondary btn-sm" disabled>
                        <i class="fas fa-clock me-1"></i>Request Sent
                    </button>
                `;
            });
        });
    });
});
</script>
@endsection
