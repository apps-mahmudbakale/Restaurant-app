<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Que extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'user_id',
        'invoice_no',
        'que_no'
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getCustomerName()
    {
        if($this->customer) {
            return $this->customer->first_name . ' ' . $this->customer->last_name;
        }
        return 'WalkIn Customer';
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
