<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class License extends Model
{
    protected $table = 'licenses';
    protected $fillable = ['name'];

    public function variants(): HasMany
    {
        return $this->hasMany(LicenseVariant::class, 'license_id');
    }

    public function instellingen()
    {
        return $this->hasMany(Instelling::class);
    }
}
