<?php

namespace App\Models\Message;

use App\Models\Base\BaseModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
