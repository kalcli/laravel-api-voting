<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidates extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidatePicture',
        'candidateName',
        'description',
        'election_id'
    ];

    public function election():BelongsTo
    {
        return $this->belongsTo(Elections::class);
    }

    public function votes():HasMany
    {
        return $this->hasMany(Votes::class);
    }
}
