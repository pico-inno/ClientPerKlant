<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = ['name'];

    public function variants()
    {
        return $this->hasMany(LicenseVariant::class);
    }

    public function instellingen()
    {
        return $this->hasMany(Instelling::class);
    }
}
