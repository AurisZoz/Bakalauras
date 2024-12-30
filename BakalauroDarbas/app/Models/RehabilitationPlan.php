<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RehabilitationPlan extends Model
{
    use HasFactory;

    protected $table = 'rehabilitation_plans';

    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function files()
    {
        return $this->hasMany(PlanFile::class, 'rehabilitation_plan_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
