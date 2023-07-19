<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'groomer_id',
        'date',
        'day',
        'time',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function groomer(){
        return $this->belongsTo(Groomer::class , 'groomer_id');
    }
}
