<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoTask extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['label', 'is_complete'];

    /**
     * @return BelongsTo
     */
    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }
}
