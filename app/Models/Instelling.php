<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instelling extends Model
{
    protected $table = 'instellingen';
    protected $fillable = [
        'instelling_id',
        'instelling_naam',
        'license_variant_id',
        'license_id',
        'is_active',
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function variant()
    {
        return $this->belongsTo(LicenseVariant::class, 'license_variant_id');
    }
}
