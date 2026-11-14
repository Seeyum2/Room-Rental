<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertiesModel extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'property_type',
        'rent_amount',
        'tenant_gender',
        'bedrooms',
        'bathrooms',
        'sqft',
        'tenant_type',
        'address',
        'city',
        'area',
        'postal_code',
        'available_from',
        'status',
    ];

    protected $casts = [
        'rent_amount' => 'decimal:2',
        'sqft' => 'decimal:2',
        'available_from' => 'date',
    ];

    // Relationship with Landlord
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    // Relationship with Property Images
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }
}
