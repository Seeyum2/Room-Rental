<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PropertiesModel;

class PropertiesController extends Controller
{
    // GET: Fetch all properties of logged-in landlord
    public function index()
    {
        $properties = PropertiesModel::where('landlord_id', auth()->id())
            ->with('landlord:id,name,email')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Properties fetched successfully',
            'data' => $properties
        ], 200);
    }

    // POST: Create a new property
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,house,studio,villa,commercial,seat,room',
            'rent_amount' => 'required|numeric|min:0',
            'tenant_gender' => 'nullable|in:male,female,other',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'sqft' => 'nullable|numeric|min:0',
            'tenant_type' => 'nullable|in:bachelor,job_holder,student',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'available_from' => 'nullable|date',
        ]);

        $validated['landlord_id'] = auth()->id();
        $validated['status'] = 'pending';

        $property = PropertiesModel::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Property created successfully',
            'data' => $property
        ], 201);
    }

    // GET: Fetch single property
    public function show($id)
    {
        $property = PropertiesModel::findOrFail($id);

        // Check property ownership
        abort_if(
            $property->landlord_id != auth()->id(),
            403,
            'Unauthorized. You cannot access another landlord\'s property.'
        );

        $property->load('landlord:id,name,email');

        return response()->json([
            'status' => true,
            'message' => 'Property details fetched successfully',
            'data' => $property
        ], 200);
    }

    // PUT/PATCH: Update property
    public function update(Request $request, $id)
    {
        $property = PropertiesModel::findOrFail($id);

        // Check property ownership
        abort_if(
            $property->landlord_id != auth()->id(),
            403,
            'Unauthorized. You cannot update another landlord\'s property.'
        );

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'sometimes|required|in:apartment,house,studio,villa,commercial,seat,room',
            'rent_amount' => 'sometimes|required|numeric|min:0',
            'tenant_gender' => 'nullable|in:male,female,other',
            'bedrooms' => 'sometimes|required|integer|min:0',
            'bathrooms' => 'sometimes|required|integer|min:0',
            'sqft' => 'nullable|numeric|min:0',
            'tenant_type' => 'nullable|in:bachelor,job_holder,student',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'area' => 'sometimes|required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'available_from' => 'nullable|date',
        ]);

        $property->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Property updated successfully',
            'data' => $property->fresh()
        ], 200);
    }

    // DELETE: Delete property
    public function destroy($id)
    {
        $property = PropertiesModel::findOrFail($id);

        // Check property ownership
        abort_if(
            $property->landlord_id != auth()->id(),
            403,
            'Unauthorized. You cannot delete another landlord\'s property.'
        );

        $property->delete();

        return response()->json([
            'status' => true,
            'message' => 'Property deleted successfully'
        ], 200);
    }
}
