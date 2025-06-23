@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contacts</h1>
            <p class="text-gray-600">Manage your contact database with ease</p>
        </div>
        <button onclick="openContactForm()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Contact
        </button>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="search-input" placeholder="Search by name or email..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select id="gender-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="All Genders">All Genders</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Prefer Not To Say">Prefer Not To Say</option>
        </select>
        <button id="merge-button" onclick="openMergeModal()" class="hidden inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            Merge (<span id="selected-count">0</span>)
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birthday</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="contacts-table-body" class="bg-white divide-y divide-gray-200">
                    @foreach($contacts as $contact)
                    <tr class="table-row" data-contact-id="{{ $contact->id }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="contact-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   value="{{ $contact->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($contact->profile_image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $contact->profile_image }}" alt="{{ $contact->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($contact->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $contact->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->company }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->birthday ? $contact->birthday->format('Y-m-d') : '' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewContact({{ $contact->id }})" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded transition-colors" title="View">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button onclick="editContact({{ $contact->id }})" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded transition-colors" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteContact({{ $contact->id }})" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors" title="Delete">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Contact Form Modal -->
<div id="contact-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 modal">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Add New Contact</h2>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="contact-form" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" id="name" name="name" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <span class="error text-red-600 text-sm hidden"></span>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" id="email" name="email" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <span class="error text-red-600 text-sm hidden"></span>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                    <input type="tel" id="phone" name="phone" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <span class="error text-red-600 text-sm hidden"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="gender" value="Male" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Male</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="gender" value="Female" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Female</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="gender" value="Prefer Not To Say" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Prefer Not To Say</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                    <input type="text" id="company" name="company"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday</label>
                    <input type="date" id="birthday" name="birthday"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image URL</label>
                <input type="url" id="profile_image" name="profile_image"
                       placeholder="https://example.com/image.jpg"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="custom-fields-section" class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Custom Fields</h3>
                <div id="custom-fields-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Custom fields will be inserted here -->
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeContactModal()" class="btn-secondary">Cancel</button>
                <button type="submit" id="submit-button" class="btn-primary">Add Contact</button>
            </div>
        </form>
    </div>
</div>

<!-- Contact Detail Modal -->
<div id="contact-detail-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 modal">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Contact Details</h2>
            <button onclick="closeContactDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="contact-detail-content" class="p-6">
            <!-- Contact details will be inserted here -->
        </div>
    </div>
</div>

<!-- Merge Modal -->
<div id="merge-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 modal">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Merge Contacts
            </h2>
            <button onclick="closeMergeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="merge-modal-content" class="p-6">
            <!-- Merge content will be inserted here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
let contacts = @json($contacts);
let customFields = @json($customFields);
let selectedContacts = [];
let editingContactId = null;
let mergeStep = 'select';
let selectedMaster = null;
let resolvedFields = {};
const APP_URL = "{{ url('/') }}";

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    renderCustomFields();
});

function setupEventListeners() {
    // Search functionality
    document.getElementById('search-input').addEventListener('input', debounce(filterContacts, 300));

    // Gender filter
    document.getElementById('gender-filter').addEventListener('change', filterContacts);

    // Select all checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.contact-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectedContacts();
    });

    // Individual checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('contact-checkbox')) {
            updateSelectedContacts();
        }
    });

    // Contact form submission
    document.getElementById('contact-form').addEventListener('submit', handleContactSubmit);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function filterContacts() {
    const search = document.getElementById('search-input').value.toLowerCase();
    const gender = document.getElementById('gender-filter').value;

    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (gender !== 'All Genders') params.append('gender', gender);

    fetch(`${APP_URL}/contacts?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        contacts = data.contacts;
        renderContactsTable();
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error filtering contacts');
    });
}

function renderContactsTable() {
    const tbody = document.getElementById('contacts-table-body');
    tbody.innerHTML = '';

    contacts.forEach(contact => {
        const row = createContactRow(contact);
        tbody.appendChild(row);
    });
}

function createContactRow(contact) {
    const row = document.createElement('tr');
    row.className = 'table-row';
    row.setAttribute('data-contact-id', contact.id);

    const profileImage = contact.profile_image ?
        `<img class="h-10 w-10 rounded-full object-cover" src="${contact.profile_image}" alt="${contact.name}">` :
        `<div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
            <span class="text-sm font-medium text-gray-700">${contact.name.charAt(0)}</span>
        </div>`;

    row.innerHTML = `
        <td class="px-6 py-4">
            <input type="checkbox" class="contact-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                   value="${contact.id}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">${profileImage}</div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">${contact.name}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.email}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.phone}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.gender}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.company || ''}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.birthday || ''}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex space-x-2">
                <button onclick="viewContact(${contact.id})" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded transition-colors" title="View">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
                <button onclick="editContact(${contact.id})" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded transition-colors" title="Edit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button onclick="deleteContact(${contact.id})" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors" title="Delete">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </td>
    `;

    return row;
}

function updateSelectedContacts() {
    const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
    selectedContacts = Array.from(checkboxes).map(cb => parseInt(cb.value));

    const selectAllCheckbox = document.getElementById('select-all');
    const allCheckboxes = document.querySelectorAll('.contact-checkbox');

    if (selectedContacts.length === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (selectedContacts.length === allCheckboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }

    const mergeButton = document.getElementById('merge-button');
    const selectedCount = document.getElementById('selected-count');

    if (selectedContacts.length >= 2) {
        mergeButton.classList.remove('hidden');
        selectedCount.textContent = selectedContacts.length;
    } else {
        mergeButton.classList.add('hidden');
    }
}

function openContactForm() {
    editingContactId = null;
    document.getElementById('modal-title').textContent = 'Add New Contact';
    document.getElementById('submit-button').textContent = 'Add Contact';
    document.getElementById('contact-form').reset();
    renderCustomFields();
    document.getElementById('contact-modal').classList.remove('hidden');
}

function editContact(id) {
    const contact = contacts.find(c => c.id === id);
    if (!contact) return;

    editingContactId = id;
    document.getElementById('modal-title').textContent = 'Edit Contact';
    document.getElementById('submit-button').textContent = 'Update Contact';

    // Populate form
    document.getElementById('name').value = contact.name;
    document.getElementById('email').value = contact.email;
    document.getElementById('phone').value = contact.phone;
    document.querySelector(`input[name="gender"][value="${contact.gender}"]`).checked = true;
    document.getElementById('company').value = contact.company || '';
    document.getElementById('birthday').value = contact.birthday || '';
    document.getElementById('profile_image').value = contact.profile_image || '';

    renderCustomFields(contact.custom_fields);
    document.getElementById('contact-modal').classList.remove('hidden');
}

function closeContactModal() {
    document.getElementById('contact-modal').classList.add('hidden');
    editingContactId = null;
}

function renderCustomFields(values = {}) {
    const container = document.getElementById('custom-fields-container');
    container.innerHTML = '';

    customFields.forEach(field => {
        const fieldDiv = document.createElement('div');
        const value = values[field.key] || '';

        let inputHtml = '';
        switch (field.type) {
            case 'date':
                inputHtml = `<input type="date" id="custom_${field.key}" name="custom_fields[${field.key}]" value="${value}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">`;
                break;
            case 'number':
                inputHtml = `<input type="number" id="custom_${field.key}" name="custom_fields[${field.key}]" value="${value}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">`;
                break;
            case 'email':
                inputHtml = `<input type="email" id="custom_${field.key}" name="custom_fields[${field.key}]" value="${value}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">`;
                break;
            case 'url':
                inputHtml = `<input type="url" id="custom_${field.key}" name="custom_fields[${field.key}]" value="${value}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">`;
                break;
            default:
                inputHtml = `<input type="text" id="custom_${field.key}" name="custom_fields[${field.key}]" value="${value}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">`;
        }

        fieldDiv.innerHTML = `
            <label for="custom_${field.key}" class="block text-sm font-medium text-gray-700">
                ${field.label} ${field.required ? '*' : ''}
            </label>
            ${inputHtml}
        `;

        container.appendChild(fieldDiv);
    });
}

function handleContactSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = {};

    // Standard fields
    data.name = formData.get('name');
    data.email = formData.get('email');
    data.phone = formData.get('phone');
    data.gender = formData.get('gender');
    data.company = formData.get('company');
    data.birthday = formData.get('birthday');
    data.profile_image = formData.get('profile_image');

    // Custom fields
    data.custom_fields = {};
    customFields.forEach(field => {
        const value = formData.get(`custom_fields[${field.key}]`);
        if (value) {
            data.custom_fields[field.key] = value;
        }
    });

    const url = editingContactId ? `${APP_URL}/contacts/${editingContactId}` : `${APP_URL}/contacts`;
    const method = editingContactId ? 'PUT' : 'POST';

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
            closeContactModal();
            filterContacts(); // Refresh the table
        } else {
            showNotification('error', 'Error saving contact');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error saving contact');
    });
}

function viewContact(id) {
    fetch(`/contacts/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        renderContactDetail(data.contact, data.customFields);
        document.getElementById('contact-detail-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error loading contact details');
    });
}

function renderContactDetail(contact, customFields) {
    const content = document.getElementById('contact-detail-content');

    const profileImage = contact.profile_image ?
        `<img src="${contact.profile_image}" alt="${contact.name}" class="h-16 w-16 rounded-full object-cover">` :
        `<div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
            <span class="text-xl font-medium text-gray-700">${contact.name.charAt(0)}</span>
        </div>`;

    let customFieldsHtml = '';
    if (customFields.length > 0) {
        customFieldsHtml = `
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Custom Fields</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    ${customFields.map(field => `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">${field.label}</label>
                            <div class="mt-1 text-gray-900">${formatFieldValue(field, contact.custom_fields[field.key])}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    content.innerHTML = `
        <div class="flex items-center space-x-4 mb-6">
            ${profileImage}
            <div>
                <h1 class="text-2xl font-bold text-gray-900">${contact.name}</h1>
                <p class="text-gray-600">${contact.company || ''}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Contact Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <a href="mailto:${contact.email}" class="text-blue-600 hover:text-blue-800 underline">${contact.email}</a>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <a href="tel:${contact.phone}" class="text-blue-600 hover:text-blue-800 underline">${contact.phone}</a>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <p class="text-gray-900">${contact.gender}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Personal Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Company</label>
                        <p class="text-gray-900">${contact.company || '-'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Birthday</label>
                        <p class="text-gray-900">${contact.birthday || '-'}</p>
                    </div>
                </div>
            </div>
        </div>

        ${customFieldsHtml}

        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
            <button onclick="deleteContact(${contact.id})" class="btn-danger">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
            <button onclick="editContactFromDetail(${contact.id})" class="btn-primary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Contact
            </button>
        </div>
    `;
}

function formatFieldValue(field, value) {
    if (!value) return '-';

    switch (field.type) {
        case 'url':
            return `<a href="${value}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline">${value}</a>`;
        case 'email':
            return `<a href="mailto:${value}" class="text-blue-600 hover:text-blue-800 underline">${value}</a>`;
        case 'date':
            return new Date(value).toLocaleDateString();
        case 'number':
            return Number(value).toLocaleString();
        default:
            return value;
    }
}

function editContactFromDetail(id) {
    closeContactDetailModal();
    editContact(id);
}

function closeContactDetailModal() {
    document.getElementById('contact-detail-modal').classList.add('hidden');
}

function deleteContact(id) {
    if (!confirm('Are you sure you want to delete this contact?')) return;

    fetch(`/contacts/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeContactDetailModal();
            filterContacts(); // Refresh the table
        } else {
            showNotification('error', 'Error deleting contact');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error deleting contact');
    });
}

function openMergeModal() {
    if (selectedContacts.length < 2) return;

    mergeStep = 'select';
    selectedMaster = selectedContacts[0];
    resolvedFields = {};

    renderMergeModal();
    document.getElementById('merge-modal').classList.remove('hidden');
}

function renderMergeModal() {
    const content = document.getElementById('merge-modal-content');

    if (mergeStep === 'select') {
        const selectedContactsData = contacts.filter(c => selectedContacts.includes(c.id));

        content.innerHTML = `
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Select Master Contact</h3>
                <p class="text-gray-600">Choose which contact will be kept as the primary record</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                ${selectedContactsData.map(contact => {
                    const profileImage = contact.profile_image ?
                        `<img src="${contact.profile_image}" alt="${contact.name}" class="h-12 w-12 rounded-full object-cover">` :
                        `<div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-700">${contact.name.charAt(0)}</span>
                        </div>`;

                    return `
                        <div class="relative border-2 rounded-lg p-4 cursor-pointer transition-colors ${
                            selectedMaster === contact.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'
                        }" onclick="selectMasterContact(${contact.id})">
                            <div class="flex items-center space-x-3">
                                ${profileImage}
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${contact.name}</h4>
                                    <p class="text-sm text-gray-600">${contact.email}</p>
                                    <p class="text-sm text-gray-600">${contact.phone}</p>
                                </div>
                                ${selectedMaster === contact.id ? `
                                    <div class="absolute top-2 right-2">
                                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button onclick="closeMergeModal()" class="btn-secondary">Cancel</button>
                <button onclick="nextMergeStep()" class="btn-primary">Next</button>
            </div>
        `;
    } else if (mergeStep === 'resolve') {
        renderConflictResolution();
    }
}

function selectMasterContact(id) {
    selectedMaster = id;
    renderMergeModal();
}

function nextMergeStep() {
    if (mergeStep === 'select') {
        const conflicts = getConflicts();
        if (conflicts.length > 0) {
            // Initialize resolved fields with master contact's values
            const masterContact = contacts.find(c => c.id === selectedMaster);
            resolvedFields = {};
            conflicts.forEach(conflict => {
                resolvedFields[conflict.field] = masterContact[conflict.field] || '';
            });
            mergeStep = 'resolve';
            renderMergeModal();
        } else {
            confirmMerge();
        }
    } else {
        confirmMerge();
    }
}

function getConflicts() {
    const selectedContactsData = contacts.filter(c => selectedContacts.includes(c.id));
    const conflicts = [];

    const standardFields = [
        { key: 'name', label: 'Name' },
        { key: 'email', label: 'Email' },
        { key: 'phone', label: 'Phone' },
        { key: 'gender', label: 'Gender' },
        { key: 'company', label: 'Company' },
        { key: 'birthday', label: 'Birthday' }
    ];

    standardFields.forEach(field => {
        const values = selectedContactsData.map(c => ({
            contactId: c.id,
            contactName: c.name,
            value: c[field.key]
        })).filter(v => v.value);

        const uniqueValues = [...new Set(values.map(v => v.value))];
        if (uniqueValues.length > 1) {
            conflicts.push({ field: field.key, label: field.label, values });
        }
    });

    return conflicts;
}

function renderConflictResolution() {
    const conflicts = getConflicts();
    const selectedContactsData = contacts.filter(c => selectedContacts.includes(c.id));
    const masterContact = selectedContactsData.find(c => c.id === selectedMaster);
    const otherContacts = selectedContactsData.filter(c => c.id !== selectedMaster);

    const content = document.getElementById('merge-modal-content');

    content.innerHTML = `
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Resolve Conflicts</h3>
            <p class="text-gray-600">Choose which values to keep for conflicting fields</p>
        </div>

        <div class="space-y-6">
            ${conflicts.map(conflict => `
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-4">${conflict.label}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${conflict.values.map(valueOption => `
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors ${
                                resolvedFields[conflict.field] === valueOption.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'
                            }">
                                <input type="radio" name="${conflict.field}" value="${valueOption.value}"
                                       ${resolvedFields[conflict.field] === valueOption.value ? 'checked' : ''}
                                       onchange="updateResolvedField('${conflict.field}', '${valueOption.value}')"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">${valueOption.value}</div>
                                    <div class="text-xs text-gray-500">
                                        From ${valueOption.contactName}
                                        ${valueOption.contactId === selectedMaster ? '<span class="ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-800 text-xs rounded">Master</span>' : ''}
                                    </div>
                                </div>
                            </label>
                        `).join('')}
                    </div>
                </div>
            `).join('')}
        </div>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-3">Merge Summary</h4>
            <div class="flex items-center space-x-4 mb-3">
                <div class="flex items-center space-x-2">
                    ${masterContact.profile_image ?
                        `<img src="${masterContact.profile_image}" alt="${masterContact.name}" class="h-8 w-8 rounded-full object-cover">` :
                        `<div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-700">${masterContact.name.charAt(0)}</span>
                        </div>`
                    }
                    <span class="font-medium text-gray-900">${masterContact.name}</span>
                </div>
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <div class="flex -space-x-2">
                    ${otherContacts.map(contact => `
                        <div class="h-8 w-8 rounded-full bg-gray-300 border-2 border-white flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600 line-through">${contact.name.charAt(0)}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
            <p class="text-sm text-gray-600">
                ${otherContacts.map(c => c.name).join(', ')} will be merged into ${masterContact.name} and then deleted.
                ${conflicts.length} conflicts will be resolved based on your choices.
                All unique emails and phone numbers from both contacts will be combined.
            </p>
        </div>

        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
            <button onclick="closeMergeModal()" class="btn-secondary">Cancel</button>
            <button onclick="confirmMerge()" class="btn-primary">Confirm Merge</button>
        </div>
    `;
}

function updateResolvedField(field, value) {
    resolvedFields[field] = value;
}

function confirmMerge() {
    const sourceIds = selectedContacts.filter(id => id !== selectedMaster);

    fetch('/contacts/merge', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            target_id: selectedMaster,
            source_ids: sourceIds,
            resolved_fields: resolvedFields
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeMergeModal();
            selectedContacts = [];
            updateSelectedContacts();
            filterContacts(); // Refresh the table
        } else {
            showNotification('error', 'Error merging contacts');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error merging contacts');
    });
}

function closeMergeModal() {
    document.getElementById('merge-modal').classList.add('hidden');
    mergeStep = 'select';
    selectedMaster = null;
    resolvedFields = {};
}
</script>
@endpush
@endsection
