<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MailerAccount extends Model
{
    protected $fillable = [
        'name',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_address',
        'from_name',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'port'      => 'integer',
        'priority'  => 'integer',
    ];

    protected $hidden = ['password'];

    // Encrypt on set
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    // Decrypt on get
    public function getPasswordAttribute(string $value): string
    {
        return Crypt::decryptString($value);
    }

    public static function activeOrdered()
    {
        return static::where('is_active', true)->orderBy('priority')->orderBy('id');
    }
}
