<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseVariant extends Model
{
    protected $table = 'license_variants';
    protected $fillable = ['name', 'license_id'];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function instellingen()
    {
        return $this->hasMany(Instelling::class);
    }
}
