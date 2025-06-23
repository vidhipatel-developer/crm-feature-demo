<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'key',
        'type',
        'required'
    ];

    protected $casts = [
        'required' => 'boolean'
    ];
}