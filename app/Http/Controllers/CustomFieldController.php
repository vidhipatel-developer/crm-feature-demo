<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        $customFields = CustomField::orderBy('label')->get();
        return view('custom-fields.index', compact('customFields'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:custom_fields,key',
            'type' => 'required|in:text,number,date,email,url',
            'required' => 'boolean'
        ]);

        $customField = CustomField::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Custom field created successfully',
            'customField' => $customField
        ]);
    }

    public function update(Request $request, CustomField $customField)
    {
        $validatedData = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,email,url',
            'required' => 'boolean'
        ]);

        $customField->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Custom field updated successfully',
            'customField' => $customField
        ]);
    }

    public function destroy(CustomField $customField)
    {
        $customField->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom field deleted successfully'
        ]);
    }
}