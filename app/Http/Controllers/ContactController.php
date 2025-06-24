<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CustomField;
use App\Models\MergeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::active();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender') && $request->gender !== 'All Genders') {
            $query->where('gender', $request->gender);
        }

        $contacts = $query->orderBy('name')->get();
        $customFields = CustomField::all();

        if ($request->ajax()) {
            return response()->json([
                'contacts' => $contacts,
                'customFields' => $customFields
            ]);
        }

        return view('contacts.index', compact('contacts', 'customFields'));
    }

    public function show(Contact $contact)
    {
        $customFields = CustomField::all();
        return response()->json([
            'contact' => $contact,
            'customFields' => $customFields
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Prefer Not To Say',
            'company' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'profile_image' => 'nullable|url',
            'custom_fields' => 'nullable|array'
        ]);

        $contact = Contact::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'contact' => $contact
        ]);
    }

    public function update(Request $request, Contact $contact)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Prefer Not To Say',
            'company' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'profile_image' => 'nullable|url',
            'custom_fields' => 'nullable|array'
        ]);

        $contact->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'contact' => $contact
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully'
        ]);
    }

    public function merge(Request $request)
    {
        $request->validate([
            'target_id' => 'required|exists:contacts,id',
            'source_ids' => 'required|array|min:1',
            'source_ids.*' => 'exists:contacts,id',
            'resolved_fields' => 'nullable|array'
        ]);

        return DB::transaction(function () use ($request) {
            $targetContact = Contact::findOrFail($request->target_id);
            $sourceContacts = Contact::whereIn('id', $request->source_ids)->get();

            foreach ($sourceContacts as $sourceContact) {
                // Create merge history record
                MergeHistory::create([
                    'source_contact_id' => $sourceContact->id,
                    'target_contact_id' => $targetContact->id,
                    'source_contact_data' => $sourceContact->toArray(),
                    'target_contact_data' => $targetContact->toArray(),
                    'conflicts_resolved' => $request->resolved_fields ?? [],
                    'merged_at' => now()
                ]);

                // Mark source contact as merged
                $sourceContact->update([
                    'status' => 'merged',
                    'merged_into' => $targetContact->id
                ]);
            }

            // Update target contact with resolved fields
            if ($request->resolved_fields) {
                $targetContact->update($request->resolved_fields);
            }

            return response()->json([
                'success' => true,
                'message' => count($sourceContacts) . ' contact(s) merged successfully'
            ]);
        });
    }
}
