<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $fillable = [
        'customer_id','subject','message','status','manager_replied_at'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('public');
    }

    // Scopes для статистики
    public function scopeToday(Builder $q): Builder
    {
        return $q->whereDate('created_at', now()->toDateString());
    }
    public function scopeThisWeek(Builder $q): Builder
    {
        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }
    public function scopeThisMonth(Builder $q): Builder
    {
        return $q->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month);
    }
}
