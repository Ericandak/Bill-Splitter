<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'created_by', 'status', 'settlement_notes', 'settled_at'];

    protected $casts = [
        'settled_at' => 'datetime'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'friends','bill_id','user_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class,'friends','bill_id','user_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateSplitDetails()
    {
        $members = $this->members;
        $expenses = $this->expenses;
        
        // Initialize arrays
        $balances = [];
        $transactions = [];
        
        // Initialize member balances
        foreach ($members as $member) {
            $balances[$member->id] = [
                'name' => $member->name,
                'paid' => 0,
                'owes' => 0,
                'net' => 0
            ];
        }

        // Calculate paid amounts and shares
        foreach ($expenses as $expense) {
            // Add what was paid
            $balances[$expense->paid_by]['paid'] += $expense->amount;
            
            // Calculate shares
            $sharedBy = $expense->sharedBy;
            $shareAmount = $expense->amount / $sharedBy->count();
            
            foreach ($sharedBy as $sharer) {
                $balances[$sharer->id]['owes'] += $shareAmount;
            }
        }

        // Calculate net balances
        foreach ($balances as $userId => &$balance) {
            $balance['net'] = $balance['paid'] - $balance['owes'];
        }

        // Calculate settlement transactions
        $debtors = array_filter($balances, fn($b) => $b['net'] < 0);
        $creditors = array_filter($balances, fn($b) => $b['net'] > 0);

        foreach ($debtors as $debtorId => $debtor) {
            $amountToPayTotal = abs($debtor['net']);
            
            foreach ($creditors as $creditorId => &$creditor) {
                if ($amountToPayTotal <= 0) break;
                
                if ($creditor['net'] > 0) {
                    $amount = min($amountToPayTotal, $creditor['net']);
                    $transactions[] = [
                        'from' => $debtor['name'],
                        'to' => $creditor['name'],
                        'amount' => round($amount, 2)
                    ];
                    
                    $amountToPayTotal -= $amount;
                    $creditor['net'] -= $amount;
                }
            }
        }

        return [
            'balances' => $balances,
            'transactions' => $transactions
        ];
    }

    public function markAsSettled($notes = null)
    {
        $this->update([
            'status' => 'settled',
            'settlement_notes' => $notes,
            'settled_at' => now()
        ]);
    }

    public function markAsPartiallySettled($notes = null)
    {
        $this->update([
            'status' => 'partially_settled',
            'settlement_notes' => $notes
        ]);
    }

    public function reopen()
    {
        $this->update([
            'status' => 'open',
            'settlement_notes' => null,
            'settled_at' => null
        ]);
    }
}
