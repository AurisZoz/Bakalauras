<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'rehabilitation_plan_id',
        'file_path',
        'original_file_name',
    ];

    public function plan()
    {
        return $this->belongsTo(RehabilitationPlan::class, 'rehabilitation_plan_id');
    }
}
