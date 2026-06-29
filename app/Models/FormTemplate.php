<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormTemplate extends Model
{
    protected $fillable = ['name', 'description', 'fields', 'is_active', 'created_by'];

    protected $casts = [
        'fields'    => 'array',
        'is_active' => 'boolean',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(FormResponse::class, 'form_template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
