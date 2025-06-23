<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MergeHistory extends Model
{
    use HasFactory;

    protected $table = 'merge_history';

    protected $fillable = [
        'source_contact_id',
        'target_contact_id',
        'source_contact_data',
        'target_contact_data',
        'conflicts_resolved',
        'merged_at'
    ];

    protected $casts = [
        'source_contact_data' => 'array',
        'target_contact_data' => 'array',
        'conflicts_resolved' => 'array',
        'merged_at' => 'datetime'
    ];

    public function targetContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'target_contact_id');
    }
}