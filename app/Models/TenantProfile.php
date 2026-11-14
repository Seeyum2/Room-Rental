<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantProfile extends Model
{
    protected $table = 'tenant_profiles';

    protected $fillable = [
        'tenant_id',
        'name',
        'gender',
        'profile_picture',
        'nid_number',
        'university_name',
        'student_id_number',
        'student_id_photo',
    ];

    // Relationship with User
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    // Profile picture URL
    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : null;
    }

    // Student ID photo URL
    public function getStudentIdPhotoUrlAttribute()
    {
        return $this->student_id_photo
            ? asset('storage/' . $this->student_id_photo)
            : null;
    }

    protected $appends = [
        'profile_picture_url',
        'student_id_photo_url',
    ];
}
