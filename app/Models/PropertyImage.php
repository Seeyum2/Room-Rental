<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    protected $table = 'property_images';

    protected $fillable = [
        'property_id',
        'image_path',
    ];

    // Relationship with Property
    public function property(): BelongsTo
    {
        return $this->belongsTo(PropertiesModel::class, 'property_id');
    }
}
