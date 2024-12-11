<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $incrementing = false;
    
    public $fillable = [
        'code',
        'fen',
        'pass',
        'modified_at',
    ];
}
