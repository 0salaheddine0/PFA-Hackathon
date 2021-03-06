<?php

namespace App\Models;

use App\Models\Objective;
use App\Models\Competition;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;


class Evaluator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'evaluators';

    protected $guard = 'evaluator';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function competitions(){
        return $this->belongsToMany(Competition::class,'competition_evaluator_objectives');
    }

    public function objectives(){
        return $this->belongsToMany(Objective::class,'competition_evaluator_objectives');
    }
}
