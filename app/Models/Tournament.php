<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tournament extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'start_date',
    'end_date',
    'description',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
