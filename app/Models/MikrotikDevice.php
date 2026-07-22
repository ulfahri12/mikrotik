<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RouterOS\Client;

class MikrotikDevice extends Model
{
    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'is_active', 'last_connected_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_connected_at' => 'datetime',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function connect(): Client
    {
        return new Client([
            'host' => $this->host,
            'user' => $this->username,
            'pass' => $this->password,
            'port' => $this->port,
        ]);
    }
}