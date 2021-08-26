<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimType extends Model
{
    use HasFactory;

    protected $table = "claim_types";

    protected $fillable = [
        'claimCategory',
        'claimType',
        'claimAmount',
        'claimPeriod',
    ];

    public function getClaimCategory(){
        return $this->belongsTo(ClaimCategory::class, "claimCategory");
    }
}
