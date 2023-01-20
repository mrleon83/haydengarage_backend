<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'email_address',
        'phone_number',
        'vehicle_make',
        'vehicle_model'
     ];

     public function appointments(){
        return $this->hasMany(Appointment::class, 'customer_id', 'id');
     }

     public function appointment(){
        return $this->belongsTo(Appointment::class, 'id', 'customer_id', 'id' );
     }

}
