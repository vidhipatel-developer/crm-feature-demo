@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Custom Fields</h1>
            <p class="text-gray-600">Define additional fields for your contacts</p>
        </div>
        <button onclick="openCustomFieldForm()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Field
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="custom-fields-table-body" class="bg-white divide-y divide-gray-200">
                    @foreach($customFields as $field)
                    <tr class="hover:bg-gray-50" data-field-id="{{ $field->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $field->label }}</div>
                                <div class="text-sm text-gray-500">{{ $field->key }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeClasses = [
                                    'text' => 'bg-blue-100 text-blue-800',
                                    'number' => 'bg-green-100 text-green-800',
                                    'date' => 'bg-purple-100 text-purple-800',
                                    'email' => 'bg-orange-100 text-orange-800',
                                    'url' => 'bg-pink-100 text-pink-800'
                                ];
                                $typeIcons = [
                                    'text' => 'T',
                                    'number' => '#',
                                    'date' => '📅',
                                    'email' => '@',
                                    'url' => '🔗'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $typeClasses[$field->type] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $typeIcons[$field->type] ?? '' }} {{ $field->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $field->required ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="editCustomField({{ $field->id }})" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded transition-colors" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteCustomField({{ $field->id }})" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors" title="Delete">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($customFields->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No custom fields defined yet. Create your first custom field to get started.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Field Form Modal -->
<div id="custom-field-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 modal">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 id="field-modal-title" class="text-xl font-semibold text-gray-900">Add Custom Field</h2>
            <button onclick="closeCustomFieldModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="custom-field-form" class="p-6 space-y-4">
            <div>
                <label for="field-label" class="block text-sm font-medium text-gray-700 mb-1">Field Label *</label>
                <input type="text" id="field-label" name="label" required placeholder="e.g., Department"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <span class="error text-red-600 text-sm hidden"></span>
            </div>

            <div>
                <label for="field-key" class="block text-sm font-medium text-gray-700 mb-1">Field Key</label>
                <input type="text" id="field-key" name="key" placeholder="Auto-generated from label"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-xs text-gray-500" id="field-key-help">Auto-generated from the label</p>
                <span class="error text-red-600 text-sm hidden"></span>
            </div>

            <div>
                <label for="field-type" class="block text-sm font-medium text-gray-700 mb-1">Field Type</label>
                <select id="field-type" name="type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="date">Date</option>
                    <option value="email">Email</option>
                    <option value="url">URL</option>
                </select>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="field-required" name="required" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="field-required" class="ml-2 block text-sm text-gray-700">Required field</label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCustomFieldModal()" class="btn-secondary">Cancel</button>
                <button type="submit" id="field-submit-button" class="btn-primary">Add Field</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let customFields = @json($customFields);
let editingFieldId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupCustomFieldEventListeners();
});

function setupCustomFieldEventListeners() {
    // Form submission
    document.getElementById('custom-field-form').addEventListener('submit', handleCustomFieldSubmit);
    
    // Auto-generate key from label
    document.getElementById('field-label').addEventListener('input', function() {
        if (!editingFieldId) { // Only auto-generate for new fields
            const key = generateKey(this.value);
            document.getElementById('field-key').value = key;
        }
    });
}

function generateKey(label) {
    return label
        .toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '_')
        .substring(0, 50);
}

function openCustomFieldForm() {
    editingFieldId = null;
    document.getElementById('field-modal-title').textContent = 'Add Custom Field';
    document.getElementById('field-submit-button').textContent = 'Add Field';
    document.getElementById('custom-field-form').reset();
    
    // Enable key field for new fields
    document.getElementById('field-key').disabled = false;
    document.getElementById('field-key-help').textContent = 'Auto-generated from the label';
    
    document.getElementById('custom-field-modal').classList.remove('hidden');
}

function editCustomField(id) {
    const field = customFields.find(f => f.id === id);
    if (!field) return;
    
    editingFieldId = id;
    document.getElementById('field-modal-title').textContent = 'Edit Custom Field';
    document.getElementById('field-submit-button').textContent = 'Update Field';
    
    // Populate form
    document.getElementById('field-label').value = field.label;
    document.getElementById('field-key').value = field.key;
    document.getElementById('field-type').value = field.type;
    document.getElementById('field-required').checked = field.required;
    
    // Disable key field for existing fields
    document.getElementById('field-key').disabled = true;
    document.getElementById('field-key-help').textContent = 'Cannot be changed after creation';
    
    document.getElementById('custom-field-modal').classList.remove('hidden');
}

function closeCustomFieldModal() {
    document.getElementById('custom-field-modal').classList.add('hidden');
    editingFieldId = null;
}

function handleCustomFieldSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        label: formData.get('label'),
        key: formData.get('key'),
        type: formData.get('type'),
        required: formData.has('required')
    };
    
    const url = editingFieldId ? `/custom-fields/${editingFieldId}` : '/custom-fields';
    const method = editingFieldId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeCustomFieldModal();
            location.reload(); // Reload to refresh the table
        } else {
            showNotification('error', data.error || 'Error saving custom field');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error saving custom field');
    });
}

function deleteCustomField(id) {
    if (!confirm('Are you sure you want to delete this custom field? This will also remove all data for this field from existing contacts.')) return;
    
    fetch(`/custom-fields/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            location.reload(); // Reload to refresh the table
        } else {
            showNotification('error', 'Error deleting custom field');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error deleting custom field');
    });
}
</script>
@endpush
@endsection