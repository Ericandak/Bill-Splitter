<?php
namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function create(Bill $bill)
    {
        // Load members directly since they are already users
        $friends = $bill->members()->get();
        return view('expenses.create', compact('bill', 'friends'));
    }

    public function store(Request $request, Bill $bill)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'paid_by' => 'required|exists:users,id',
            'shared_by' => 'required|array|min:1',
            'shared_by.*' => 'exists:users,id'
        ]);

        if (count($request->shared_by) === 1 && $request->paid_by !== 'none') {
            $singleSharer = $request->shared_by[0];
            
            if ($request->paid_by != $singleSharer) {
                return back()->withErrors([
                    'paid_by' => 'When an expense is shared by only one person, that person must be the one who paid.'
                ])->withInput();
            }
        }

        // Verify that all selected users are members of this bill
        $billMemberIds = $bill->members->pluck('id')->toArray();
        if (!in_array($request->paid_by, $billMemberIds) || 
            !empty(array_diff($request->shared_by, $billMemberIds))) {
            return back()->withErrors(['error' => 'Invalid member selection'])->withInput();
        }

        $expense = Expense::create([
            'bill_id' => $bill->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'paid_by' => $request->paid_by
        ]);

        $expense->sharedBy()->attach($request->shared_by);

        return redirect()->route('bills.show', $bill)->with('success', 'Expense added successfully!');
    }
}