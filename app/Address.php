<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasApiTokens,Notifiable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country', 'city','user_id'
    ];
    protected $hidden = [
        'updated_at','created_at'
    ];
}
