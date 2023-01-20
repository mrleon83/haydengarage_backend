<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'day',
        'month',
        'year',
        'time',
        'date_string'
     ];

     public function customer(){
        return $this->belongsTo(Customer::class, 'id', 'customer_id' );
     }

     public function customerdetails(){
        return $this->hasOne(Customer::class, 'id', 'customer_id' );
     }


}
