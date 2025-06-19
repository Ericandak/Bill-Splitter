<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseShare extends Model
{
    use HasFactory;

    protected $fillable = ['expense_id', 'friend_id'];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function friend()
    {
        return $this->belongsTo(Friend::class);
    }
}