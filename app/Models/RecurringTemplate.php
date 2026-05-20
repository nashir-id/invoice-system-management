<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecurringTemplate extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'client_id',
        'invoice_id',
        'template_name',  
        'mode',          
        'generate_day',  
        'status',        
        'last_generated', 
        'notes',         
    ];

    // -------------------------------------------------------
    // CASTS
    // -------------------------------------------------------
    protected function casts(): array
    {
        return [
            'generate_day'   => 'integer',  // jadi integer
            'last_generated' => 'date',     // jadi Carbon date
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    
    public function scopeAutoMode($query)
    {
        return $query->where('mode', 'auto');
    }

    // Template mode manual / reminder
    // Contoh: RecurringTemplate::manualMode()->get()
    public function scopeManualMode($query)
    {
        return $query->where('mode', 'manual');
    }

    // Template yang jatuh tempo hari ini (siap digenerate)
    // Contoh: RecurringTemplate::dueToday()->get()
    public function scopeDueToday($query)
    {
        return $query->active()
            ->where('generate_day', now()->day);
    }

    // -------------------------------------------------------
    // HELPER
    // -------------------------------------------------------

    // Pause template
    public function pause(): bool
    {
        return $this->update(['status' => 'paused']);
    }

    // Resume template
    public function resume(): bool
    {
        return $this->update(['status' => 'active']);
    }

    // Cek apakah template sudah digenerate bulan ini
    public function isGeneratedThisMonth(): bool
    {
        return $this->last_generated
            && $this->last_generated->isCurrentMonth();
    }
}