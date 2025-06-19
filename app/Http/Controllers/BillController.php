<?php

namespace App\Http\Controllers;
use App\Models\Bill;
use App\Models\Friend;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with('members')
            ->where('created_by', auth()->id())
            ->latest()
            ->get();
        return view('bills.index', compact('bills'));
    }

    public function create()
    {
        $friends = auth()->user()->friends;
        return view('bills.create', compact('friends'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'friends' => 'required|array|min:1',
            'friends.*' => 'exists:users,id'
        ]);

        $bill = Bill::create([
            'name' => $request->name,
            'created_by' => auth()->id()
        ]);

        // Attach selected friends to the bill
        $bill->members()->attach($request->friends);
        $bill->members()->attach(auth()->id()); // Also add the creator as a member

        return redirect()->route('bills.show', $bill);
    }

    public function show(Bill $bill)
    {
        // Check if user is a member of this bill
        if (!$bill->members->contains(auth()->id())) {
            return redirect()->route('bills.index');
        }

        $bill->load(['members', 'expenses.payer', 'expenses.sharedBy']);
        $balances = $this->calculateBalances($bill);
        return view('bills.show', compact('bill', 'balances'));
    }

    private function calculateBalances(Bill $bill)
    {
        $members = $bill->members;
        $expenses = $bill->expenses;
        
        // Initialize balances
        $balances = [];
        foreach ($members as $member) {
            $balances[$member->id] = [
                'name' => $member->name,
                'paid' => 0,
                'owes' => 0,
                'balance' => 0
            ];
        }

        // Calculate what each member paid and owes
        foreach ($expenses as $expense) {
            // Add to what the payer paid
            if (isset($balances[$expense->paid_by])) {
                $balances[$expense->paid_by]['paid'] += $expense->amount;
            }
            
            // Calculate share per person for this expense
            $shareCount = $expense->sharedBy->count();
            if ($shareCount > 0) {
                $shareAmount = $expense->amount / $shareCount;
                
                // Add to what each shared person owes
                foreach ($expense->sharedBy as $sharer) {
                    if (isset($balances[$sharer->id])) {
                        $balances[$sharer->id]['owes'] += $shareAmount;
                    }
                }
            }
        }

        // Calculate final balance (positive means they get money back, negative means they owe)
        foreach ($balances as $memberId => &$balance) {
            $balance['balance'] = $balance['paid'] - $balance['owes'];
        }

        return $balances;
    }
    public function settle(Request $request, Bill $bill)
    {
        $request->validate([
            'status' => 'required|in:settled,partially_settled',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($request->status === 'settled') {
            $bill->markAsSettled($request->notes);
        } else {
            $bill->markAsPartiallySettled($request->notes);
        }

        return redirect()->route('bills.show', $bill)
            ->with('success', 'Bill status updated successfully!');
    }

}