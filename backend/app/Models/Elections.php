<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Elections extends Model
{
    use HasFactory;

    public function candidates():HasMany
    {
        return $this->hasMany(Candidates::class);
    }

    protected $fillable = [
        'electionPicture',
        'electionName',
        'electionDescription',
        'startDate',
        'endDate'
    ];
}
