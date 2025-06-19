<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['bill_id', 'title', 'amount', 'paid_by'];

    public function bill(){
        return $this->belongsTo(Bill::class);
    }

    public function payer(){
        return $this->belongsTo(User::class,'paid_by');
    }

    public function sharedBy(){
        return $this->belongsToMany(User::class,'expense_shares');
    }
}