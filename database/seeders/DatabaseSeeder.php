<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Creates: 1 Admin, 1 Approved School with sample players
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@find11.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Create Approved School User
        $schoolUser = User::create([
            'name' => 'Royal College Admin',
            'email' => 'royal@school.lk',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SCHOOL,
            'email_verified_at' => now(),
        ]);

        // Create School Profile (Approved)
        $school = School::create([
            'user_id' => $schoolUser->id,
            'school_name' => 'Royal College',
            'school_type' => School::TYPE_GOVERNMENT,
            'district' => 'Colombo',
            'province' => 'Western',
            'school_address' => 'Rajakeeya Mawatha, Colombo 07',
            'contact_number' => '+94 11 2695 268',
            'cricket_incharge_name' => 'Mr. John Silva',
            'cricket_incharge_contact' => '+94 77 123 4567',
            'status' => School::STATUS_APPROVED,
        ]);

        // Create Sample Players for the school
        $players = [
            [
                'full_name' => 'Kasun Perera',
                'date_of_birth' => '2010-03-15',
                'player_category' => Player::CATEGORY_TOP_ORDER_BATTER,
                'batting_style' => Player::BATTING_RIGHT_HAND,
                'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN,
                'jersey_number' => '10',
            ],
            [
                'full_name' => 'Nuwan Fernando',
                'date_of_birth' => '2011-07-22',
                'player_category' => Player::CATEGORY_FAST_BOWLER,
                'batting_style' => Player::BATTING_RIGHT_HAND,
                'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST,
                'jersey_number' => '11',
            ],
            [
                'full_name' => 'Ashan de Silva',
                'date_of_birth' => '2009-01-10',
                'player_category' => Player::CATEGORY_SPIN_ALLROUNDER,
                'batting_style' => Player::BATTING_LEFT_HAND,
                'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX,
                'jersey_number' => '7',
            ],
            [
                'full_name' => 'Dilshan Jayawardena',
                'date_of_birth' => '2012-11-30',
                'player_category' => Player::CATEGORY_POWER_HITTER,
                'batting_style' => Player::BATTING_RIGHT_HAND,
                'bowling_style' => Player::BOWLING_DOES_NOT_BOWL,
                'jersey_number' => '45',
            ],
            [
                'full_name' => 'Tharindu Mendis',
                'date_of_birth' => '2008-05-18',
                'player_category' => Player::CATEGORY_FAST_BOWLING_ALLROUNDER,
                'batting_style' => Player::BATTING_RIGHT_HAND,
                'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM,
                'jersey_number' => '33',
            ],
        ];

        foreach ($players as $playerData) {
            $playerData['school_id'] = $school->id;
            $playerData['age_category'] = Player::calculateAgeCategory($playerData['date_of_birth']);
            Player::create($playerData);
        }

        // Create a Pending School for testing approval workflow
        $pendingSchoolUser = User::create([
            'name' => 'Trinity Admin',
            'email' => 'trinity@school.lk',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SCHOOL,
            'email_verified_at' => now(),
        ]);

        School::create([
            'user_id' => $pendingSchoolUser->id,
            'school_name' => 'Trinity College',
            'school_type' => School::TYPE_GOVERNMENT,
            'district' => 'Kandy',
            'province' => 'Central',
            'school_address' => 'Chapel Road, Kandy',
            'contact_number' => '+94 81 222 4568',
            'cricket_incharge_name' => 'Mr. David Perera',
            'cricket_incharge_contact' => '+94 71 987 6543',
            'status' => School::STATUS_PENDING,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('-------------------');
        $this->command->info('Admin: admin@find11.com / password');
        $this->command->info('School (Approved): royal@school.lk / password');
        $this->command->info('School (Pending): trinity@school.lk / password');
    }
}
