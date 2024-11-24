<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'user_id',
        'observation'
    ];

    /**
     * Relationship: A trámite can have many documents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Relationship: A trámite belongs to a user (the client who created it).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class); // Relación con el modelo User
    }

    /**
     * Check if all associated documents are approved and update trámite status.
     *
     * @return bool
     */
    public function checkCompletion()
    {
        // If any document is not approved, the trámite is not completed.
        if ($this->documents()->where('status', '!=', 'Aprobado')->exists()) {
            return false;
        }

        // All documents are approved, update trámite status to 'Completado'.
        $this->update(['status' => 'Completado']);
        return true;
    }

    /**
     * Scope to filter trámites by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
