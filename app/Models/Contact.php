<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'company',
        'birthday',
        'profile_image',
        'custom_fields',
        'status',
        'merged_into'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'birthday' => 'date'
    ];

    public function mergedIntoContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'merged_into');
    }

    public function mergeHistory(): HasMany
    {
        return $this->hasMany(MergeHistory::class, 'target_contact_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMerged($query)
    {
        return $query->where('status', 'merged');
    }
}