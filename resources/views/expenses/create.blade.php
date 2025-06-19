@extends('layouts.app')

@section('content')
<div class="row justify-content-center fade-in">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h2 class="h4 mb-0">Add Expense to {{ $bill->name }}</h2>
            </div>

            <div class="card-body">
                <form action="{{ route('expenses.store', ['bill' => $bill->id]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="title" class="form-label">Expense Title</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label">Amount (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   value="{{ old('amount') }}" 
                                   required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="paid_by" class="form-label">Paid By</label>
                        <select class="form-select @error('paid_by') is-invalid @enderror" 
                                id="paid_by" 
                                name="paid_by" 
                                required>
                            <option value="">Select who paid</option>
                            @foreach($friends as $friend)
                                <option value="{{ $friend->id }}" 
                                        {{ old('paid_by') == $friend->id ? 'selected' : '' }}>
                                    {{ $friend->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('paid_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Shared By</label>
                        <div class="friend-selection">
                            @foreach($friends as $friend)
                                <div class="friend-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="shared_by[]" 
                                           value="{{ $friend->id }}" 
                                           id="friend_{{ $friend->id }}"
                                           {{ in_array($friend->id, old('shared_by', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="friend_{{ $friend->id }}">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ substr($friend->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="friend-name">{{ $friend->name }}</div>
                                                <div class="share-amount text-muted small"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('shared_by')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg submit-expense">
                            <span class="button-content">
                                <i class="fas fa-plus me-2"></i>Add Expense
                            </span>
                            <div class="button-loader d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Adding...
                            </div>
                        </button>
                        <a href="{{ route('bills.show', ['bill' => $bill->id]) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
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
    display: flex;
    align-items: center;
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
    transition: all 0.2s;
}

.form-check-input:checked ~ .form-check-label .avatar-circle {
    background: var(--success-color);
    transform: scale(1.05);
}

.friend-name {
    font-weight: 500;
}

.share-amount {
    opacity: 0;
    transition: opacity 0.3s;
}

.form-check-input:checked ~ .form-check-label .share-amount {
    opacity: 1;
}

.submit-expense {
    position: relative;
    min-width: 150px;
}

.submit-expense.loading .button-content {
    display: none;
}

.submit-expense.loading .button-loader {
    display: flex !important;
    align-items: center;
    gap: 0.5rem;
}

/* Animation for checkbox */
.form-check-input {
    transition: all 0.2s;
}

.form-check-input:checked {
    transform: scale(1.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sharedByCheckboxes = document.querySelectorAll('input[name="shared_by[]"]');
    const paidBySelect = document.getElementById('paid_by');
    const amountInput = document.getElementById('amount');
    const form = document.querySelector('form');
    
    function updatePaidBy() {
        const checkedBoxes = document.querySelectorAll('input[name="shared_by[]"]:checked');
        
        if (checkedBoxes.length === 1) {
            const singlePersonId = checkedBoxes[0].value;
            paidBySelect.value = singlePersonId;
            paidBySelect.style.backgroundColor = '#e3f2fd';
        } else {
            paidBySelect.style.backgroundColor = '';
        }
        
        updateShareAmounts();
    }
    
    function updateShareAmounts() {
        const amount = parseFloat(amountInput.value) || 0;
        const checkedBoxes = document.querySelectorAll('input[name="shared_by[]"]:checked');
        const shareAmount = checkedBoxes.length > 0 ? amount / checkedBoxes.length : 0;
        
        sharedByCheckboxes.forEach(checkbox => {
            const amountElement = checkbox.nextElementSibling.querySelector('.share-amount');
            if (checkbox.checked && shareAmount > 0) {
                amountElement.textContent = `Share: ₹${shareAmount.toFixed(2)}`;
            } else {
                amountElement.textContent = '';
            }
        });
    }
    
    sharedByCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePaidBy);
        // Make the entire friend-check div clickable
        checkbox.closest('.friend-check').addEventListener('click', (e) => {
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });
    
    amountInput.addEventListener('input', updateShareAmounts);
    
    // Form submission animation
    form.addEventListener('submit', function() {
        const submitButton = document.querySelector('.submit-expense');
        submitButton.classList.add('loading');
        submitButton.disabled = true;
    });
});
</script>
@endsection