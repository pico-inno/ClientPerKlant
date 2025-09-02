<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ClientPerKlant extends Model
{
    protected $table = 'client_per_klants';

    protected $fillable = [
        'instelling_id',
        'instelling_naam',
        'aantal_actieve_clienten',
        'aantal_inactieve_klanten',
        'recorded_month',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'recorded_month' => 'date',
        'aantal_actieve_clienten' => 'integer',
        'aantal_inactieve_klanten' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
                $model->created_by = Auth::id() ?? 1;
                $model->updated_by = Auth::id() ?? 1;

        });
        static::updating(function ($model) {
                $model->updated_by = Auth::id() ?? 1;
        });


    }
    public function getYearAttribute()
    {
        return explode('-', $this->recorded_month)[0] ?? null;
    }


    public function getMonthAttribute()
    {
        return explode('-', $this->recorded_month)[1] ?? null;
    }







    /**
     * Get the total number of clients (active + inactive)
     *
     * @return int
     */
    public function getTotaalClientenAttribute(): int
    {
        return $this->aantal_actieve_clienten + $this->aantal_inactieve_klanten;
    }

    /**
     * Get the percentage of active clients
     *
     * @return float
     */
    public function getPercentageActiefAttribute(): float
    {
        $totaal = $this->totaal_clienten;
        if ($totaal === 0) {
            return 0.0;
        }

        return round(($this->aantal_actieve_clienten / $totaal) * 100, 2);
    }

    /**
     * Relationship with the user who created the record
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with the user who last updated the record
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }



    /**
     * Scope a query to filter by institution ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $instellingId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByInstelling($query, $instellingId)
    {
        return $query->where('instelling_id', $instellingId);
    }

    /**
     * Scope a query to filter by recorded month
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $month (format: Y-m)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMonth($query, $month)
    {
        return $query->where('recorded_month', 'like', $month . '%');
    }

    /**
     * Scope a query to get records for a specific year
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('recorded_month', $year);
    }

    /**
     * Scope a query to get active clients only
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActieveClienten($query)
    {
        return $query->where('aantal_actieve_clienten', '>', 0);
    }

    /**
     * Scope a query to get records with both active and inactive clients
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithClienten($query)
    {
        return $query->where(function ($q) {
            $q->where('aantal_actieve_clienten', '>', 0)
                ->orWhere('aantal_inactieve_klanten', '>', 0);
        });
    }
}
