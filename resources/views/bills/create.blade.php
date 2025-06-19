@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h2 class="h4 mb-0">Create New Bill</h2>
                </div>

                <div class="card-body">
                    @if(auth()->user()->friends->count() > 0)
                        <form action="{{ route('bills.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label">Bill Name</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-block">Select Friends to Split With</label>
                                <div class="friend-selection">
                                    @foreach($friends as $friend)
                                        <div class="friend-check">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   name="friends[]" 
                                                   value="{{ $friend->id }}" 
                                                   id="friend_{{ $friend->id }}"
                                                   {{ in_array($friend->id, old('friends', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="friend_{{ $friend->id }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2">
                                                        {{ substr($friend->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="friend-name">{{ $friend->name }}</div>
                                                        <small class="text-muted">{{ $friend->email }}</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('friends')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create Bill
                                </button>
                                <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-user-friends fa-3x text-muted"></i>
                            </div>
                            <h5>No Friends Added Yet</h5>
                            <p class="text-muted mb-4">Add friends to start splitting bills with them!</p>
                            <a href="{{ route('friends.search') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Find Friends
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.friend-selection {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem;
}

.friend-check {
    padding: 0.75rem;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}

.friend-check:last-child {
    margin-bottom: 0;
}

.friend-check:hover {
    background-color: #f8f9fa;
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

.form-check-input:checked ~ .form-check-label .avatar-circle {
    background: var(--success-color);
}

.friend-name {
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const friendChecks = document.querySelectorAll('.friend-check');
    
    friendChecks.forEach(check => {
        check.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            }
        });
    });
});
</script>
@endsection