// CRM Application JavaScript

// Global state
let contacts = [];
let customFields = [];
let currentView = 'contact-list';
let editingContactId = null;
let editingFieldId = null;
let selectedContacts = [];
let currentPage = 1;
let itemsPerPage = 10;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeData();
    setupEventListeners();
    showView('contact-list');
});

// Initialize sample data
function initializeData() {
    // Sample custom fields
    customFields = [
        { id: '1', label: 'Company', type: 'text', required: false, createdAt: new Date('2024-01-15') },
        { id: '2', label: 'Birthday', type: 'date', required: false, createdAt: new Date('2024-01-15') },
        { id: '3', label: 'Salary', type: 'number', required: false, createdAt: new Date('2024-01-15') },
    ];

    // Sample contacts
    contacts = [
        {
            id: '1',
            name: 'John Doe',
            email: 'john@example.com',
            phone: '+1 (555) 123-4567',
            gender: 'male',
            profileImage: 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=150',
            customFields: { Company: 'Tech Corp', Birthday: '1990-05-15', Salary: 75000 },
            createdAt: new Date('2024-01-15'),
            updatedAt: new Date('2024-01-15'),
        },
        {
            id: '2',
            name: 'Jane Smith',
            email: 'jane@example.com',
            phone: '+1 (555) 987-6543',
            gender: 'female',
            profileImage: 'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=150',
            customFields: { Company: 'Design Studio', Birthday: '1988-12-03', Salary: 85000 },
            createdAt: new Date('2024-01-16'),
            updatedAt: new Date('2024-01-16'),
        },
        {
            id: '3',
            name: 'Mike Johnson',
            email: 'mike@example.com',
            phone: '+1 (555) 456-7890',
            gender: 'male',
            profileImage: 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=150',
            customFields: { Company: 'StartupXYZ', Birthday: '1992-08-20', Salary: 65000 },
            createdAt: new Date('2024-01-17'),
            updatedAt: new Date('2024-01-17'),
        },
    ];

    renderContacts();
    renderCustomFields();
}

// Setup event listeners
function setupEventListeners() {
    // Navigation
    document.getElementById('nav-contacts').addEventListener('click', (e) => {
        e.preventDefault();
        showView('contact-list');
    });

    document.getElementById('nav-custom-fields').addEventListener('click', (e) => {
        e.preventDefault();
        showView('custom-fields');
    });

    // Contact list actions
    document.getElementById('add-contact-btn').addEventListener('click', () => {
        editingContactId = null;
        showContactForm();
    });

    document.getElementById('search-input').addEventListener('input', debounce(renderContacts, 300));
    document.getElementById('gender-filter').addEventListener('change', renderContacts);
    document.getElementById('select-all').addEventListener('change', handleSelectAll);
    document.getElementById('merge-btn').addEventListener('click', showMergeModal);

    // Contact form
    document.getElementById('contact-form').addEventListener('submit', handleContactSubmit);
    document.getElementById('cancel-btn').addEventListener('click', () => showView('contact-list'));
    document.getElementById('profile-image').addEventListener('change', handleImageUpload);
    document.getElementById('remove-image').addEventListener('click', removeProfileImage);

    // Contact detail
    document.getElementById('back-to-contacts').addEventListener('click', () => showView('contact-list'));
    document.getElementById('edit-contact-btn').addEventListener('click', () => {
        showContactForm(editingContactId);
    });
    document.getElementById('delete-contact-btn').addEventListener('click', handleDeleteContact);

    // Custom fields
    document.getElementById('add-field-btn').addEventListener('click', () => {
        editingFieldId = null;
        showCustomFieldModal();
    });
    document.getElementById('custom-field-form').addEventListener('submit', handleCustomFieldSubmit);
}

// View management
function showView(viewName) {
    // Hide all views
    document.querySelectorAll('.view').forEach(view => {
        view.classList.remove('active');
    });

    // Show selected view
    document.getElementById(viewName + '-view').classList.add('active');

    // Update navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });

    if (viewName === 'contact-list') {
        document.getElementById('nav-contacts').classList.add('active');
    } else if (viewName === 'custom-fields') {
        document.getElementById('nav-custom-fields').classList.add('active');
    }

    currentView = viewName;
}

// Contact management
function renderContacts() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const genderFilter = document.getElementById('gender-filter').value;

    let filteredContacts = contacts.filter(contact => {
        const matchesSearch = contact.name.toLowerCase().includes(searchTerm) ||
            contact.email.toLowerCase().includes(searchTerm);
        const matchesGender = !genderFilter || contact.gender === genderFilter;
        return matchesSearch && matchesGender;
    });

    const totalPages = Math.ceil(filteredContacts.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const paginatedContacts = filteredContacts.slice(startIndex, startIndex + itemsPerPage);

    const tbody = document.getElementById('contacts-tbody');
    const noContacts = document.getElementById('no-contacts');

    if (filteredContacts.length === 0) {
        tbody.innerHTML = '';
        noContacts.style.display = 'block';
        document.getElementById('pagination').style.display = 'none';
    } else {
        noContacts.style.display = 'none';
        document.getElementById('pagination').style.display = 'flex';

        tbody.innerHTML = paginatedContacts.map(contact => `
            <tr class="table-row">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" class="contact-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                           value="${contact.id}" onchange="handleContactSelection()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            ${contact.profileImage ?
            `<img class="h-10 w-10 rounded-full object-cover" src="${contact.profileImage}" alt="${contact.name}">` :
            `<div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">${contact.name.charAt(0).toUpperCase()}</span>
                                </div>`
        }
                        </div>
                        <div class="ml-4">
                            <button onclick="showContactDetail('${contact.id}')" class="text-sm font-medium text-gray-900 hover:text-primary-600 transition-colors duration-200">
                                ${contact.name}
                            </button>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.email}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.phone}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 capitalize">
                        ${contact.gender}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.customFields.Company || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${contact.customFields.Birthday ? formatDate(contact.customFields.Birthday) : '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick="showContactForm('${contact.id}')" class="text-primary-600 hover:text-primary-900 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteContact('${contact.id}')" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        renderPagination(totalPages);
    }
}

function renderPagination(totalPages) {
    if (totalPages <= 1) {
        document.getElementById('pagination').innerHTML = '';
        return;
    }

    const pagination = document.getElementById('pagination');
    pagination.innerHTML = `
        <div class="flex flex-1 justify-between sm:hidden">
            <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
            </button>
            <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Page <span class="font-medium">${currentPage}</span> of <span class="font-medium">${totalPages}</span>
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                    <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    ${Array.from({length: totalPages}, (_, i) => i + 1).map(page => `
                        <button onclick="changePage(${page})"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${
        page === currentPage ? 'z-10 bg-primary-600 text-white' : 'text-gray-900'
    }">
                            ${page}
                        </button>
                    `).join('')}
                    <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    `;
}

function changePage(page) {
    const totalPages = Math.ceil(getFilteredContacts().length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderContacts();
    }
}

function getFilteredContacts() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const genderFilter = document.getElementById('gender-filter').value;

    return contacts.filter(contact => {
        const matchesSearch = contact.name.toLowerCase().includes(searchTerm) ||
            contact.email.toLowerCase().includes(searchTerm);
        const matchesGender = !genderFilter || contact.gender === genderFilter;
        return matchesSearch && matchesGender;
    });
}

function handleContactSelection() {
    const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
    selectedContacts = Array.from(checkboxes).map(cb => cb.value);

    const mergeBtn = document.getElementById('merge-btn');
    const selectedCount = document.getElementById('selected-count');

    if (selectedContacts.length === 2) {
        mergeBtn.style.display = 'inline-flex';
        selectedCount.style.display = 'inline';
        selectedCount.textContent = `${selectedContacts.length} selected`;
    } else if (selectedContacts.length > 0) {
        mergeBtn.style.display = 'none';
        selectedCount.style.display = 'inline';
        selectedCount.textContent = `${selectedContacts.length} selected`;
    } else {
        mergeBtn.style.display = 'none';
        selectedCount.style.display = 'none';
    }
}

function handleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.contact-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });

    handleContactSelection();
}

function showContactForm(contactId = null) {
    editingContactId = contactId;
    const isEditing = Boolean(contactId);

    document.getElementById('form-title').textContent = isEditing ? 'Edit Contact' : 'Add New Contact';
    document.getElementById('save-btn').textContent = isEditing ? 'Update Contact' : 'Create Contact';

    if (isEditing) {
        const contact = contacts.find(c => c.id === contactId);
        if (contact) {
            document.getElementById('contact-name').value = contact.name;
            document.getElementById('contact-email').value = contact.email;
            document.getElementById('contact-phone').value = contact.phone;
            document.querySelector(`input[name="gender"][value="${contact.gender}"]`).checked = true;

            if (contact.profileImage) {
                const preview = document.getElementById('profile-preview');
                preview.innerHTML = `<img src="${contact.profileImage}" alt="Profile" class="profile-image-preview">`;
                document.getElementById('remove-image').style.display = 'inline';
            }

            // Populate custom fields
            renderCustomFieldsInForm(contact.customFields);
        }
    } else {
        document.getElementById('contact-form').reset();
        document.getElementById('profile-preview').innerHTML = `
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
        `;
        document.getElementById('remove-image').style.display = 'none';
        renderCustomFieldsInForm();
    }

    showView('contact-form');
}

function renderCustomFieldsInForm(existingValues = {}) {
    const container = document.getElementById('custom-fields-container');

    if (customFields.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">No custom fields defined</p>';
        return;
    }

    container.innerHTML = customFields.map(field => {
        const value = existingValues[field.label] || '';
        let inputHtml = '';

        switch (field.type) {
            case 'date':
                inputHtml = `<input type="date" id="custom-${field.id}" class="input-field" value="${value}">`;
                break;
            case 'number':
                inputHtml = `<input type="number" id="custom-${field.id}" class="input-field" value="${value}">`;
                break;
            case 'email':
                inputHtml = `<input type="email" id="custom-${field.id}" class="input-field" value="${value}">`;
                break;
            case 'phone':
                inputHtml = `<input type="tel" id="custom-${field.id}" class="input-field" value="${value}">`;
                break;
            default:
                inputHtml = `<input type="text" id="custom-${field.id}" class="input-field" value="${value}">`;
        }

        return `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    ${field.label} ${field.required ? '*' : ''}
                </label>
                ${inputHtml}
            </div>
        `;
    }).join('');
}

function handleContactSubmit(e) {
    e.preventDefault();

    const formData = {
        name: document.getElementById('contact-name').value.trim(),
        email: document.getElementById('contact-email').value.trim(),
        phone: document.getElementById('contact-phone').value.trim(),
        gender: document.querySelector('input[name="gender"]:checked').value,
        profileImage: document.getElementById('profile-preview').querySelector('img')?.src || '',
        customFields: {}
    };

    // Collect custom field values
    customFields.forEach(field => {
        const input = document.getElementById(`custom-${field.id}`);
        if (input && input.value) {
            formData.customFields[field.label] = input.value;
        }
    });

    // Validate required fields
    if (!formData.name || !formData.email || !formData.phone) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    if (editingContactId) {
        // Update existing contact
        const contactIndex = contacts.findIndex(c => c.id === editingContactId);
        if (contactIndex !== -1) {
            contacts[contactIndex] = {
                ...contacts[contactIndex],
                ...formData,
                updatedAt: new Date()
            };
            showToast('Contact updated successfully', 'success');
        }
    } else {
        // Create new contact
        const newContact = {
            id: Date.now().toString(),
            ...formData,
            createdAt: new Date(),
            updatedAt: new Date()
        };
        contacts.push(newContact);
        showToast('Contact created successfully', 'success');
    }

    renderContacts();
    showView('contact-list');
}

function handleImageUpload(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Profile" class="profile-image-preview">`;
            document.getElementById('remove-image').style.display = 'inline';
        };
        reader.readAsDataURL(file);
    }
}

function removeProfileImage() {
    const preview = document.getElementById('profile-preview');
    preview.innerHTML = `
        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
        </svg>
    `;
    document.getElementById('remove-image').style.display = 'none';
    document.getElementById('profile-image').value = '';
}

function showContactDetail(contactId) {
    const contact = contacts.find(c => c.id === contactId);
    if (!contact) return;

    editingContactId = contactId;

    // Populate contact details
    document.getElementById('detail-name').textContent = contact.name;
    document.getElementById('detail-gender').textContent = contact.gender;
    document.getElementById('detail-email').textContent = contact.email;
    document.getElementById('detail-phone').textContent = contact.phone;
    document.getElementById('detail-created').textContent = contact.createdAt.toLocaleDateString();
    document.getElementById('detail-updated').textContent = contact.updatedAt.toLocaleDateString();

    // Profile image
    const profileImageContainer = document.getElementById('detail-profile-image');
    const profileInitial = document.getElementById('detail-profile-initial');

    if (contact.profileImage) {
        profileImageContainer.innerHTML = `<img src="${contact.profileImage}" alt="${contact.name}" class="w-16 h-16 rounded-full object-cover">`;
    } else {
        profileImageContainer.className = 'w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center';
        profileInitial.textContent = contact.name.charAt(0).toUpperCase();
    }

    // Custom fields
    const customFieldsContent = document.getElementById('detail-custom-fields-content');
    const customFieldsSection = document.getElementById('detail-custom-fields');

    if (Object.keys(contact.customFields).length > 0) {
        customFieldsSection.style.display = 'block';
        customFieldsContent.innerHTML = Object.entries(contact.customFields).map(([label, value]) => {
            if (!value) return '';

            const field = customFields.find(f => f.label === label);
            let displayValue = value;

            if (field && field.type === 'date') {
                displayValue = formatDate(value);
            } else if (field && field.type === 'number') {
                displayValue = Number(value).toLocaleString();
            }

            return `
                <div class="flex items-start space-x-3">
                    <div class="w-5 h-5 bg-gray-200 rounded-full mt-0.5"></div>
                    <div>
                        <div class="text-sm text-gray-500">${label}</div>
                        <div class="font-medium">${displayValue}</div>
                    </div>
                </div>
            `;
        }).join('');
    } else {
        customFieldsSection.style.display = 'none';
    }

    // Quick action links
    document.getElementById('send-email-link').href = `mailto:${contact.email}`;
    document.getElementById('call-phone-link').href = `tel:${contact.phone}`;

    showView('contact-detail');
}

function handleDeleteContact() {
    const contact = contacts.find(c => c.id === editingContactId);
    if (!contact) return;

    if (confirm(`Are you sure you want to delete ${contact.name}?`)) {
        deleteContact(editingContactId);
        showView('contact-list');
    }
}

function deleteContact(contactId) {
    const contact = contacts.find(c => c.id === contactId);
    if (!contact) return;

    if (confirm(`Are you sure you want to delete ${contact.name}?`)) {
        contacts = contacts.filter(c => c.id !== contactId);
        showToast(`${contact.name} has been deleted`, 'success');
        renderContacts();
    }
}

// Custom Fields Management
function renderCustomFields() {
    const tbody = document.getElementById('custom-fields-tbody');
    const noFields = document.getElementById('no-custom-fields');

    if (customFields.length === 0) {
        tbody.innerHTML = '';
        noFields.style.display = 'block';
    } else {
        noFields.style.display = 'none';

        tbody.innerHTML = customFields.map(field => `
            <tr class="table-row">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${field.label}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                        ${getFieldTypeLabel(field.type)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
            field.required ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
        }">
                        ${field.required ? 'Yes' : 'No'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${field.createdAt.toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick="editCustomField('${field.id}')" class="text-primary-600 hover:text-primary-900 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteCustomField('${field.id}')" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
}

function showCustomFieldModal(fieldId = null) {
    editingFieldId = fieldId;
    const isEditing = Boolean(fieldId);

    document.getElementById('custom-field-modal-title').textContent =
        isEditing ? 'Edit Custom Field' : 'Add New Custom Field';

    if (isEditing) {
        const field = customFields.find(f => f.id === fieldId);
        if (field) {
            document.getElementById('field-label').value = field.label;
            document.getElementById('field-type').value = field.type;
            document.getElementById('field-required').checked = field.required;
        }
    } else {
        document.getElementById('custom-field-form').reset();
    }

    document.getElementById('custom-field-modal').style.display = 'block';
}

function hideCustomFieldModal() {
    document.getElementById('custom-field-modal').style.display = 'none';
    editingFieldId = null;
}

function handleCustomFieldSubmit(e) {
    e.preventDefault();

    const formData = {
        label: document.getElementById('field-label').value.trim(),
        type: document.getElementById('field-type').value,
        required: document.getElementById('field-required').checked
    };

    if (!formData.label) {
        showToast('Field label is required', 'error');
        return;
    }

    // Check for duplicate labels
    const existingField = customFields.find(
        field => field.label.toLowerCase() === formData.label.toLowerCase() &&
            field.id !== editingFieldId
    );

    if (existingField) {
        showToast('A field with this label already exists', 'error');
        return;
    }

    if (editingFieldId) {
        // Update existing field
        const fieldIndex = customFields.findIndex(f => f.id === editingFieldId);
        if (fieldIndex !== -1) {
            customFields[fieldIndex] = {
                ...customFields[fieldIndex],
                ...formData
            };
            showToast('Custom field updated successfully', 'success');
        }
    } else {
        // Create new field
        const newField = {
            id: Date.now().toString(),
            ...formData,
            createdAt: new Date()
        };
        customFields.push(newField);
        showToast('Custom field created successfully', 'success');
    }

    renderCustomFields();
    hideCustomFieldModal();
}

function editCustomField(fieldId) {
    showCustomFieldModal(fieldId);
}

function deleteCustomField(fieldId) {
    const field = customFields.find(f => f.id === fieldId);
    if (!field) return;

    if (confirm(`Are you sure you want to delete the "${field.label}" field? This will remove the field from all contacts.`)) {
        customFields = customFields.filter(f => f.id !== fieldId);

        // Remove field from all contacts
        contacts.forEach(contact => {
            delete contact.customFields[field.label];
        });

        showToast(`${field.label} field has been deleted`, 'success');
        renderCustomFields();
        renderContacts();
    }
}

// Merge functionality
function showMergeModal() {
    if (selectedContacts.length !== 2) {
        showToast('Please select exactly 2 contacts to merge', 'warning');
        return;
    }

    const contact1 = contacts.find(c => c.id === selectedContacts[0]);
    const contact2 = contacts.find(c => c.id === selectedContacts[1]);

    if (!contact1 || !contact2) return;

    const mergeContent = document.getElementById('merge-content');
    mergeContent.innerHTML = `
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Select Master Contact</h4>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="master" value="${contact1.id}" checked class="text-primary-600 focus:ring-primary-500">
                    <div class="flex items-center space-x-3">
                        ${contact1.profileImage ?
        `<img src="${contact1.profileImage}" alt="" class="w-8 h-8 rounded-full object-cover">` :
        `<div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-xs font-medium text-gray-700">${contact1.name.charAt(0).toUpperCase()}</span>
                            </div>`
    }
                        <span class="font-medium">${contact1.name}</span>
                    </div>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="master" value="${contact2.id}" class="text-primary-600 focus:ring-primary-500">
                    <div class="flex items-center space-x-3">
                        ${contact2.profileImage ?
        `<img src="${contact2.profileImage}" alt="" class="w-8 h-8 rounded-full object-cover">` :
        `<div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-xs font-medium text-gray-700">${contact2.name.charAt(0).toUpperCase()}</span>
                            </div>`
    }
                        <span class="font-medium">${contact2.name}</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Merge Summary</h4>
            <p class="text-sm text-blue-800">
                The secondary contact will be deleted and all data will be merged into the master contact.
                Custom fields from both contacts will be combined.
            </p>
        </div>

        <div class="flex justify-end space-x-3">
            <button onclick="hideMergeModal()" class="btn-secondary">Cancel</button>
            <button onclick="confirmMerge()" class="btn-primary">Confirm Merge</button>
        </div>
    `;

    document.getElementById('merge-modal').style.display = 'block';
}

function hideMergeModal() {
    document.getElementById('merge-modal').style.display = 'none';
}

function confirmMerge() {
    const masterRadio = document.querySelector('input[name="master"]:checked');
    if (!masterRadio) return;

    const masterId = masterRadio.value;
    const secondaryId = selectedContacts.find(id => id !== masterId);

    const masterContact = contacts.find(c => c.id === masterId);
    const secondaryContact = contacts.find(c => c.id === secondaryId);

    if (!masterContact || !secondaryContact) return;

    // Merge custom fields
    const mergedCustomFields = {
        ...secondaryContact.customFields,
        ...masterContact.customFields
    };

    // Update master contact
    const masterIndex = contacts.findIndex(c => c.id === masterId);
    contacts[masterIndex] = {
        ...masterContact,
        customFields: mergedCustomFields,
        updatedAt: new Date()
    };

    // Remove secondary contact
    contacts = contacts.filter(c => c.id !== secondaryId);

    showToast('Contacts merged successfully', 'success');
    hideMergeModal();
    selectedContacts = [];
    renderContacts();
}

// Utility functions
function getFieldTypeLabel(type) {
    const labels = {
        text: 'Text',
        number: 'Number',
        date: 'Date',
        email: 'Email',
        phone: 'Phone'
    };
    return labels[type] || 'Text';
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
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

// Toast notifications
function showToast(message, type = 'info', duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    const toastId = Date.now().toString();

    const iconMap = {
        success: `<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>`,
        error: `<svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`,
        warning: `<svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>`,
        info: `<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>`
    };

    const colorMap = {
        success: 'bg-green-50 border-green-200',
        error: 'bg-red-50 border-red-200',
        warning: 'bg-yellow-50 border-yellow-200',
        info: 'bg-blue-50 border-blue-200'
    };

    const toast = document.createElement('div');
    toast.id = `toast-${toastId}`;
    toast.className = `toast max-w-sm w-full shadow-lg rounded-lg pointer-events-auto border ${colorMap[type]}`;
    toast.innerHTML = `
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${iconMap[type]}
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="removeToast('${toastId}')" class="bg-transparent rounded-md inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        removeToast(toastId);
    }, duration);
}

function removeToast(toastId) {
    const toast = document.getElementById(`toast-${toastId}`);
    if (toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}
