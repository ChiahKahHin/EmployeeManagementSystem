<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedTask extends Model
{
    use HasFactory;

    protected $table = "rejected_tasks";
}
