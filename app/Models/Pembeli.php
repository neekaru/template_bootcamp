<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pembeli extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'pembelis';
    protected $fillable = ['username', 'email', 'password', 'provider', 'provider_id', 'avatar'];
    protected $hidden = ['password', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'pembeli_id');
    }
}
