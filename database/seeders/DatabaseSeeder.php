<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\CustomField;
use App\Models\MergeHistory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create custom fields
        $customFields = [
            [
                'label' => 'LinkedIn Profile',
                'key' => 'linkedin',
                'type' => 'url',
                'required' => false,
            ],
            [
                'label' => 'Department',
                'key' => 'department',
                'type' => 'text',
                'required' => false,
            ],
            [
                'label' => 'Start Date',
                'key' => 'start_date',
                'type' => 'date',
                'required' => false,
            ],
            [
                'label' => 'Salary',
                'key' => 'salary',
                'type' => 'number',
                'required' => false,
            ],
            [
                'label' => 'Notes',
                'key' => 'notes',
                'type' => 'text',
                'required' => false,
            ],
        ];

        foreach ($customFields as $fieldData) {
            CustomField::create($fieldData);
        }

        // Create sample contacts
        $contacts = [
            [
                'name' => 'Daniel Rodriguez',
                'email' => 'daniel.r@example.com',
                'phone' => '+1 (555) 999-0000',
                'gender' => 'Male',
                'company' => 'Creative Minds',
                'birthday' => '1991-06-22',
                'profile_image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
                'custom_fields' => [
                    'linkedin' => 'https://linkedin.com/in/daniel-rodriguez',
                    'department' => 'Engineering',
                    'start_date' => '2020-01-15',
                    'salary' => 75000,
                    'notes' => 'Senior developer with React expertise'
                ],
                'status' => 'active',
            ],
            [
                'name' => 'Chloe Taylor',
                'email' => 'chloe.t@example.com',
                'phone' => '+1 (555) 000-1111',
                'gender' => 'Prefer Not To Say',
                'company' => 'Data Systems',
                'birthday' => '1994-08-01',
                'profile_image' => 'https://images.pexels.com/photos/3763188/pexels-photo-3763188.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
                'custom_fields' => [
                    'linkedin' => 'https://linkedin.com/in/chloe-taylor',
                    'department' => 'Marketing',
                    'start_date' => '2021-03-10',
                    'salary' => 65000,
                    'notes' => 'Digital marketing specialist'
                ],
                'status' => 'active',
            ],
            [
                'name' => 'Michael Miller',
                'email' => 'michael.m@example.com',
                'phone' => '+1 (555) 777-8888',
                'gender' => 'Male',
                'company' => 'Solutions Corp.',
                'birthday' => '1989-04-25',
                'profile_image' => 'https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
                'custom_fields' => [
                    'linkedin' => 'https://linkedin.com/in/michael-miller',
                    'department' => 'Sales',
                    'start_date' => '2019-11-20',
                    'salary' => 80000,
                    'notes' => 'Top performing sales representative'
                ],
                'status' => 'active',
            ],
            [
                'name' => 'Olivia Martinez',
                'email' => 'olivia.m@example.com',
                'phone' => '+1 (555) 444-5555',
                'gender' => 'Female',
                'company' => 'Creative Minds',
                'birthday' => '1995-07-30',
                'profile_image' => 'https://images.pexels.com/photos/3182812/pexels-photo-3182812.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
                'custom_fields' => [
                    'linkedin' => 'https://linkedin.com/in/olivia-martinez',
                    'department' => 'Design',
                    'start_date' => '2022-01-05',
                    'salary' => 70000,
                    'notes' => 'UX/UI designer with creative vision'
                ],
                'status' => 'active',
            ],
            [
                'name' => 'Sophia Brown',
                'email' => 'sophia.b@example.com',
                'phone' => '+1 (555) 666-7777',
                'gender' => 'Female',
                'company' => 'Innovate Inc.',
                'birthday' => '1993-12-12',
                'profile_image' => 'https://images.pexels.com/photos/3785077/pexels-photo-3785077.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
                'custom_fields' => [
                    'linkedin' => 'https://linkedin.com/in/sophia-brown',
                    'department' => 'HR',
                    'start_date' => '2020-08-15',
                    'salary' => 68000,
                    'notes' => 'HR specialist focused on employee engagement'
                ],
                'status' => 'active',
            ],
        ];

        foreach ($contacts as $contactData) {
            Contact::create($contactData);
        }

        // Create sample merge history
        $danielContact = Contact::where('name', 'Daniel Rodriguez')->first();
        $michaelContact = Contact::where('name', 'Michael Miller')->first();

        if ($danielContact && $michaelContact) {
            // Create sample merge history entries
            MergeHistory::create([
                'source_contact_id' => 999, // Fake ID for merged contact
                'target_contact_id' => $danielContact->id,
                'source_contact_data' => [
                    'id' => 999,
                    'name' => 'test2',
                    'email' => 'test2@test.com',
                    'phone' => '+1 (555) 123-4567',
                    'gender' => 'Male',
                    'company' => 'Tech Corp',
                    'birthday' => '1990-01-01',
                    'custom_fields' => [],
                ],
                'target_contact_data' => $danielContact->toArray(),
                'conflicts_resolved' => [
                    'name' => 'Daniel Rodriguez',
                    'email' => 'daniel.r@example.com'
                ],
                'merged_at' => now()->subDays(5),
            ]);

            MergeHistory::create([
                'source_contact_id' => 998, // Fake ID for merged contact
                'target_contact_id' => $michaelContact->id,
                'source_contact_data' => [
                    'id' => 998,
                    'name' => 'Emily Davis',
                    'email' => 'emily.d@example.com',
                    'phone' => '+1 (555) 234-5678',
                    'gender' => 'Female',
                    'company' => 'Design Studio',
                    'birthday' => '1992-02-02',
                    'profile_image' => 'https://images.pexels.com/photos/3763188/pexels-photo-3763188.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
                    'custom_fields' => [],
                ],
                'target_contact_data' => $michaelContact->toArray(),
                'conflicts_resolved' => [
                    'name' => 'Michael Miller',
                    'company' => 'Solutions Corp.'
                ],
                'merged_at' => now()->subDays(3),
            ]);
        }
    }
}