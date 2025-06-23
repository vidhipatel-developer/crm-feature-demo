@extends('layout.app',['page_title' => 'Modern CRM | Contacts'])
@section('content')
    <div class="px-4 py-6 sm:px-0">
        <!-- Contact List View -->
        <div id="contact-list-view" class="view active">
            <div class="space-y-6">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Contacts</h1>
                        <p class="mt-2 text-sm text-gray-700">Manage your contact database with ease</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button id="add-contact-btn" class="btn-primary inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Contact
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" id="search-input" placeholder="Search by name or email..." class="input-field pl-10">
                        </div>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                            </svg>
                            <select id="gender-filter" class="input-field pl-10 appearance-none">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="merge-btn" class="btn-secondary inline-flex items-center" style="display: none;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Merge Selected
                            </button>
                            <span id="selected-count" class="text-sm text-gray-600" style="display: none;"></span>
                        </div>
                    </div>
                </div>

                <!-- Contact Table -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birthday</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="contacts-tbody" class="bg-white divide-y divide-gray-200">
                            <!-- Contacts will be populated here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                        <!-- Pagination will be populated here -->
                    </div>
                </div>

                <!-- No contacts message -->
                <div id="no-contacts" class="text-center py-12" style="display: none;">
                    <div class="text-gray-400 text-lg mb-2">No contacts found</div>
                    <p class="text-gray-600">Get started by adding your first contact</p>
                </div>
            </div>
        </div>

        <!-- Contact Modal -->
        <!-- Modal Backdrop -->
        <div id="contact-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-y-auto">
            <!-- Modal Content -->
            <div class="bg-white w-full max-w-xl mx-auto rounded-xl shadow-xl p-6 relative my-10">

                <!-- Close Button -->
                <button id="modal-close-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Modal Title -->
                <h1 id="modal-form-title" class="text-2xl font-bold text-gray-800 mb-6">Add New Contact</h1>

                <form id="contact-form" class="space-y-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-700">Basic Information</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Name *</label>
                            <input type="text" id="contact-name" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-100" placeholder="Enter full name" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email *</label>
                            <input type="email" id="contact-email" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-100" placeholder="Enter email address" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Phone *</label>
                            <input type="tel" id="contact-phone" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-100" placeholder="Enter phone number" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Gender *</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center text-sm">
                                    <input type="radio" name="gender" value="male" class="text-blue-600" checked>
                                    <span class="ml-2">Male</span>
                                </label>
                                <label class="flex items-center text-sm">
                                    <input type="radio" name="gender" value="female" class="text-blue-600">
                                    <span class="ml-2">Female</span>
                                </label>
                                <label class="flex items-center text-sm">
                                    <input type="radio" name="gender" value="other" class="text-blue-600">
                                    <span class="ml-2">Other</span>
                                </label>
                            </div>
                        </div>

                        <!-- Profile Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Profile Image</label>
                            <div class="flex items-center space-x-4">
                                <div id="profile-preview" class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <label class="bg-white px-4 py-2 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 cursor-pointer">
                                    Upload Image
                                    <input type="file" id="profile-image" class="sr-only" accept="image/*">
                                </label>
                                <button type="button" id="remove-image" class="text-sm text-red-500 hover:underline hidden">Remove</button>
                            </div>
                        </div>

                        <!-- Additional File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Additional File</label>
                            <input type="file" id="additional-file" class="block w-full text-sm text-gray-600">
                        </div>
                    </div>

                    <!-- Custom Fields Section -->
                    <div id="custom-fields-section" class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-700">Additional Information</h3>
                        <div id="custom-fields-container">
                            <!-- Fields injected dynamically -->
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" id="cancel-btn" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</button>
                        <button type="submit" id="save-btn" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Contact</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Contact Detail View -->
        <div id="contact-detail-view" class="view">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <button id="back-to-contacts" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to contacts
                    </button>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div id="detail-profile-image" class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                                <span id="detail-profile-initial" class="text-xl font-medium text-gray-700"></span>
                            </div>
                            <div>
                                <h1 id="detail-name" class="text-2xl font-bold text-gray-900"></h1>
                                <p id="detail-gender" class="text-gray-600 capitalize"></p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button id="edit-contact-btn" class="btn-secondary inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            <button id="delete-contact-btn" class="btn-danger inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Contact Information -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-500">Email</div>
                                        <div id="detail-email" class="font-medium"></div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-500">Phone</div>
                                        <div id="detail-phone" class="font-medium"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Fields -->
                        <div id="detail-custom-fields" class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
                            <div id="detail-custom-fields-content" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Custom fields will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                            <div class="space-y-3">
                                <div>
                                    <div class="text-sm text-gray-500">Created</div>
                                    <div id="detail-created" class="font-medium"></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Last Updated</div>
                                    <div id="detail-updated" class="font-medium"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                            <div class="space-y-2">
                                <a id="send-email-link" href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Send Email
                                </a>
                                <a id="call-phone-link" href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Call Phone
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addBtn = document.getElementById('add-contact-btn');
            const contactModal = document.getElementById('contact-modal');
            const modalCloseBtn = document.getElementById('modal-close-btn');
            const formTitle = document.getElementById('modal-form-title');
            const saveBtn = document.getElementById('save-btn');

            // Open modal to add new contact
            addBtn.addEventListener('click', function () {
                resetForm();
                formTitle.textContent = 'Add New Contact';
                saveBtn.textContent = 'Create Contact';
                contactModal.classList.remove('hidden');
            });

            // Close modal
            modalCloseBtn.addEventListener('click', function () {
                contactModal.classList.add('hidden');
            });

            // Cancel button
            document.getElementById('cancel-btn').addEventListener('click', function () {
                contactModal.classList.add('hidden');
            });

            // Open modal to edit contact
            document.getElementById('contacts-tbody').addEventListener('click', function (e) {
                if (e.target.closest('.edit-contact-btn')) {
                    const contactId = e.target.closest('.edit-contact-btn').dataset.id;

                    fetch(`/contacts/${contactId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            populateForm(data);
                            formTitle.textContent = 'Edit Contact';
                            saveBtn.textContent = 'Update Contact';
                            contactModal.classList.remove('hidden');
                        });
                }
            });

            function resetForm() {
                document.getElementById('contact-form').reset();
                document.getElementById('profile-preview').innerHTML = `<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>`;
            }

            function populateForm(contact) {
                document.getElementById('contact-name').value = contact.name || '';
                document.getElementById('contact-email').value = contact.email || '';
                document.getElementById('contact-phone').value = contact.phone || '';
                document.querySelector(`input[name="gender"][value="${contact.gender}"]`).checked = true;
                // Load more if needed
            }
        });
    </script>
@endpush
