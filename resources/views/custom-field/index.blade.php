@extends('layout.app',['page_title' => 'Modern CRM | Contacts'])
@section('content')
    <div class="px-4 py-6 sm:px-0">
       <!-- Custom Fields Management View -->
        <div id="custom-fields-view" class="view">
            <div class="space-y-6">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Custom Fields</h1>
                        <p class="mt-2 text-sm text-gray-700">Manage custom fields that can be added to contacts</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button id="add-field-btn" class="btn-primary inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Field
                        </button>
                    </div>
                </div>

                <!-- Custom Fields Table -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="custom-fields-tbody" class="bg-white divide-y divide-gray-200">
                            <!-- Custom fields will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- No custom fields message -->
                <div id="no-custom-fields" class="text-center py-12" style="display: none;">
                    <div class="text-gray-400 text-lg mb-2">No custom fields defined</div>
                    <p class="text-gray-600 mb-4">Create custom fields to capture additional information about your contacts</p>
                    <button class="btn-primary inline-flex items-center" onclick="showCustomFieldModal()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Your First Field
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
