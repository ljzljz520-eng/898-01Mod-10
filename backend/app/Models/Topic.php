<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'view_count',
        'reply_count',
        'is_pinned',
        'status',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'status' => 'integer',
        'view_count' => 'integer',
        'reply_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function knowledgeCard()
    {
        return $this->hasOne(KnowledgeCard::class);
    }

    public function scopeEligibleForKnowledgeCard($query)
    {
        return $query->whereIn('category', ['broadband', 'school', 'parking', 'renovation'])
            ->whereDoesntHave('knowledgeCard');
    }
}
