<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'has_account',
        'account_user_id',
        'priority',
        'is_active',

    ];
    //un contacto pertenece a una usuaria
    public function user()
    {
        return $this->belongsTo(User::class);

    }

    //si el contacto tiene cuenta, esta es su cuenta
    public function account()
    {
        return $this->belongsTo(User::class, 'account_user_id');
    }
}
