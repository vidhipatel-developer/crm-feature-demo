@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Merge History</h1>
                <p class="text-gray-600">Track all contact merge activities and relationships</p>
            </div>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-600">Total Merged Contacts</div>
            <div class="text-3xl font-bold text-blue-600">{{ $mergeHistory->count() }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                All Merged Contacts
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Complete list of contacts that have been merged into other contacts
            </p>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($mergeHistory as $merge)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <!-- Source Contact -->
                        <div class="flex items-center space-x-3">
                            @if(!empty($merge->source_contact_data['profile_image']))
                                <img src="{{ $merge->source_contact_data['profile_image'] }}" 
                                     alt="{{ $merge->source_contact_data['name'] }}" 
                                     class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700">
                                        {{ substr($merge->source_contact_data['name'], 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $merge->source_contact_data['name'] }}</div>
                                <div class="text-sm text-gray-600">{{ $merge->source_contact_data['email'] }}</div>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>

                        <!-- Target Contact -->
                        <div class="flex items-center space-x-3">
                            @if($merge->targetContact && $merge->targetContact->profile_image)
                                <img src="{{ $merge->targetContact->profile_image }}" 
                                     alt="{{ $merge->targetContact->name }}" 
                                     class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-lg font-medium text-blue-700">
                                        {{ $merge->targetContact ? substr($merge->targetContact->name, 0, 1) : 'N' }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $merge->targetContact ? $merge->targetContact->name : $merge->target_contact_data['name'] }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $merge->targetContact ? $merge->targetContact->email : $merge->target_contact_data['email'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Merged on</div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $merge->merged_at->format('M j, Y') }}
                            </div>
                        </div>
                        <button onclick="restoreContact({{ $merge->id }})" 
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Restore
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No merge history</h3>
                <p>When you merge contacts, they will appear here with the ability to restore them.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function restoreContact(mergeId) {
    if (!confirm('Are you sure you want to restore this contact? This will recreate the contact as an active record.')) return;
    
    fetch(`/merge-history/${mergeId}/restore`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            location.reload(); // Reload to refresh the list
        } else {
            showNotification('error', 'Error restoring contact');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error restoring contact');
    });
}
</script>
@endpush
@endsection