<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status'
    ];

    const STATUS_COMPLETED = 1;
    const STATUS_IN_COMPLETED = 0;

    //Accessor for formatting the status attribute
    public function getStatusAttribute($value)
    {
        return ($value == self::STATUS_COMPLETED) ? "Completed Task" : "Incompleted Task"; 
    }

}
