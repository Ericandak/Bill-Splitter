<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('created_at', '!=', null);
    }

    public function bills()
    {
        return $this->belongsToMany(Bill::class, 'friends','user_id','bill_id');
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function hasPendingFriendRequestFrom(User $user)
    {
        return $this->receivedFriendRequests()
            ->where('sender_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function hasSentFriendRequestTo(User $user)
    {
        return $this->sentFriendRequests()
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function isFriendWith(User $user)
    {
        return $this->friends()->where('friend_id', $user->id)->exists() ||
               $user->friends()->where('friend_id', $this->id)->exists();
    }

    public function createdBills()
    {
        return $this->hasMany(Bill::class, 'created_by');
    }

    public function paidExpenses()
    {
        return $this->hasMany(Expense::class, 'paid_by');
    }

    public function sharedExpenses()
    {
        return $this->belongsToMany(Expense::class, 'expense_shares');
    }
}
