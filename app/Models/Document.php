<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
protected $fillable = ['user_id', 'type', 'file_path', 'status', 'observation'];

    /**
     * Relación con el modelo User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
