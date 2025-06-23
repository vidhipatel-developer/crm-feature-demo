<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MergeHistory;
use Illuminate\Http\Request;

class MergeHistoryController extends Controller
{
    public function index()
    {
        $mergeHistory = MergeHistory::with('targetContact')
            ->orderBy('merged_at', 'desc')
            ->get();

        return view('merge-history.index', compact('mergeHistory'));
    }

    public function restore(MergeHistory $mergeHistory)
    {
        // Restore the source contact
        $restoredContact = Contact::create([
            'name' => $mergeHistory->source_contact_data['name'],
            'email' => $mergeHistory->source_contact_data['email'],
            'phone' => $mergeHistory->source_contact_data['phone'],
            'gender' => $mergeHistory->source_contact_data['gender'],
            'company' => $mergeHistory->source_contact_data['company'] ?? null,
            'birthday' => $mergeHistory->source_contact_data['birthday'] ?? null,
            'profile_image' => $mergeHistory->source_contact_data['profile_image'] ?? null,
            'custom_fields' => $mergeHistory->source_contact_data['custom_fields'] ?? [],
            'status' => 'active'
        ]);

        // Delete the merge history record
        $mergeHistory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact restored successfully',
            'contact' => $restoredContact
        ]);
    }
}