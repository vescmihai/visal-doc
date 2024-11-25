<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placa extends Model
{
    use HasFactory;

    protected $fillable = [
        'tramite_id',
        'placa',
        'motor',
        'chasis',
        'poliza',
        'pago',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
}
