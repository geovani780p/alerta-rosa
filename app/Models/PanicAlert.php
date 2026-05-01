<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanicAlert extends Model
{
    protected $fillable = [
        'user_id',
        'triggered_by',
        'status',
        'paused_untill',
        'started_at',
        'cancelled_at',
        'expires_at'
    ];
    protected $casts = [
        'paused_untill' => 'datetime',
        'started_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    //Una alerta pertenece a una usuaria :)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function locations()
    {
    return $this->hasMany(LocationHistory::class, 'alert_id');
    }
}
