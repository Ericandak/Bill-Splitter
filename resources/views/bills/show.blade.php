@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-0">{{ $bill->name }}</h1>
                            <span class="badge bg-{{ $bill->status === 'settled' ? 'success' : ($bill->status === 'partially_settled' ? 'warning' : 'primary') }}">
                                {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                            </span>
                        </div>
                        <div class="btn-group">
                            @if($bill->status !== 'settled')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#settleModal">
                                    <i class="fas fa-check-circle me-2"></i>Mark as Settled
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" onclick="reopenBill()">
                                    <i class="fas fa-redo me-2"></i>Reopen Bill
                                </button>
                            @endif
                            <a href="{{ route('expenses.create', $bill) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Expense
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @php
                        $splitDetails = $bill->calculateSplitDetails();
                        $balances = $splitDetails['balances'];
                        $transactions = $splitDetails['transactions'];
                    @endphp

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-1">Total Amount</h6>
                                    <h3 class="mb-0">₹{{ $bill->expenses->sum('amount') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-1">Members</h6>
                                    <h3 class="mb-0">{{ $bill->members->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-1">Expenses</h6>
                                    <h3 class="mb-0">{{ $bill->expenses->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Sheet -->
                    <h4 class="mb-3">Balance Sheet</h4>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Paid</th>
                                    <th>Net Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($balances as $balance)
                                    <tr>
                                        <td>{{ $balance['name'] }}</td>
                                        <td>₹{{ number_format($balance['paid'], 2) }}</td>
                                        <td>
                                            <span class="badge {{ $balance['net'] > 0 ? 'bg-success' : ($balance['net'] < 0 ? 'bg-danger' : 'bg-secondary') }}">
                                                ₹{{ number_format(abs($balance['net']), 2) }}
                                                {{ $balance['net'] > 0 ? 'gets back' : ($balance['net'] < 0 ? 'owes' : 'settled') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Settlement Plan -->
                    <h4 class="mb-3">Settlement Plan</h4>
                    @if(count($transactions) > 0)
                        <div class="list-group">
                            @foreach($transactions as $transaction)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                        <strong>{{ $transaction['from'] }}</strong> pays 
                                        <strong>{{ $transaction['to'] }}</strong>
                                    </div>
                                    <span class="badge bg-primary">₹{{ number_format($transaction['amount'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No settlements needed - all balanced!</p>
                    @endif

                    <!-- Expense List -->
                    <h4 class="mt-4 mb-3">Expenses</h4>
                    @if($bill->expenses->count() > 0)
                        <div class="list-group">
                            @foreach($bill->expenses as $expense)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">{{ $expense->title }}</h6>
                                        <span class="badge bg-secondary">₹{{ number_format($expense->amount, 2) }}</span>
                                    </div>
                                    <p class="mb-1 text-muted">
                                        Paid by <strong>{{ $expense->payer->name }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        Shared by: {{ $expense->sharedBy->pluck('name')->join(', ') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No expenses added yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settlement Modal -->
<div class="modal fade" id="settleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bills.settle', $bill) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Settle Bill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Settlement Status</label>
                        <select name="status" class="form-select" required>
                            <option value="settled">Fully Settled</option>
                            <option value="partially_settled">Partially Settled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Settlement Notes</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                placeholder="Optional: Add notes about how the bill was settled..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Settlement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection