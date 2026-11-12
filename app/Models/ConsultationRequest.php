<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationRequest extends Model
{
    public const STATUSES = [
        'new' => 'جديد',
        'contacted' => 'تم التواصل',
        'closed' => 'مغلق',
    ];

    protected $guarded = [];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
