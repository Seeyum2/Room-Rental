<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TenantProfile;

class TenantProfileController extends Controller
{
    // GET: Fetch logged-in tenant's profile
    public function show()
    {
        $profile = TenantProfile::with('tenant:id,name,phone_number,email')
            ->where('tenant_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Tenant profile fetched successfully',
            'data' => $profile
        ], 200);
    }

    // POST: Create tenant profile
    public function store(Request $request)
    {
        // Check if profile already exists
        if (TenantProfile::where('tenant_id', auth()->id())->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Tenant profile already exists.'
            ], 409);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'nid_number' => 'nullable|string|max:50',
            'university_name' => 'nullable|string|max:255',
            'student_id_number' => 'nullable|string|max:100',
            'student_id_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Automatically assign logged-in user's ID
        $validated['tenant_id'] = auth()->id();

        // Upload profile picture
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')
                ->store('tenant_profiles/profile_pictures', 'public');
        }

        // Upload student ID photo
        if ($request->hasFile('student_id_photo')) {
            $validated['student_id_photo'] = $request->file('student_id_photo')
                ->store('tenant_profiles/student_id_photos', 'public');
        }

        $profile = TenantProfile::create($validated);

        $profile->load('tenant:id,name,phone_number,email');

        return response()->json([
            'status' => true,
            'message' => 'Tenant profile created successfully',
            'data' => $profile
        ], 201);
    }

    // PUT/PATCH: Update tenant profile
    public function update(Request $request)
    {
        $profile = TenantProfile::where('tenant_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'nullable|string|max:20',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'nid_number' => 'nullable|string|max:50',
            'university_name' => 'nullable|string|max:255',
            'student_id_number' => 'nullable|string|max:100',
            'student_id_photo' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Replace profile picture if a new one is uploaded
        if ($request->hasFile('profile_picture')) {

            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }

            $validated['profile_picture'] = $request->file('profile_picture')
                ->store('tenant_profiles/profile_pictures', 'public');
        }

        // Replace student ID photo if a new one is uploaded
        if ($request->hasFile('student_id_photo')) {

            if ($profile->student_id_photo) {
                Storage::disk('public')->delete($profile->student_id_photo);
            }

            $validated['student_id_photo'] = $request->file('student_id_photo')
                ->store('tenant_profiles/student_id_photos', 'public');
        }

        $profile->update($validated);

        $profile->load('tenant:id,name,phone_number,email');

        return response()->json([
            'status' => true,
            'message' => 'Tenant profile updated successfully',
            'data' => $profile
        ], 200);
    }

    // DELETE: Delete tenant profile
    public function destroy()
    {
        $profile = TenantProfile::where('tenant_id', auth()->id())
            ->firstOrFail();

        // Delete profile picture
        if ($profile->profile_picture) {
            Storage::disk('public')->delete($profile->profile_picture);
        }

        // Delete student ID photo
        if ($profile->student_id_photo) {
            Storage::disk('public')->delete($profile->student_id_photo);
        }

        $profile->delete();

        return response()->json([
            'status' => true,
            'message' => 'Tenant profile deleted successfully'
        ], 200);
    }
}
