<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\Player;
use Database\Seeders\PlayerCategorySeeder;
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
        // Ensure default player categories exist
        $this->call(PlayerCategorySeeder::class);

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

        // Create Sample Players for Royal College (20 players)
        $royalPlayers = [
            ['full_name' => 'Kasun Perera', 'date_of_birth' => '2010-03-15', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN, 'jersey_number' => '1'],
            ['full_name' => 'Nuwan Fernando', 'date_of_birth' => '2011-07-22', 'player_category' => Player::CATEGORY_FAST_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST, 'jersey_number' => '2'],
            ['full_name' => 'Ashan de Silva', 'date_of_birth' => '2009-01-10', 'player_category' => Player::CATEGORY_SPIN_ALLROUNDER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX, 'jersey_number' => '3'],
            ['full_name' => 'Dilshan Jayawardena', 'date_of_birth' => '2012-11-30', 'player_category' => Player::CATEGORY_POWER_HITTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '4'],
            ['full_name' => 'Tharindu Mendis', 'date_of_birth' => '2008-05-18', 'player_category' => Player::CATEGORY_FAST_BOWLING_ALLROUNDER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '5'],
            ['full_name' => 'Sahan Wijesinghe', 'date_of_birth' => '2010-09-05', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '6'],
            ['full_name' => 'Kavinda Ratnayake', 'date_of_birth' => '2011-02-14', 'player_category' => Player::CATEGORY_MEDIUM_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '7'],
            ['full_name' => 'Dasun Shanaka', 'date_of_birth' => '2010-06-20', 'player_category' => Player::CATEGORY_FAST_BOWLING_ALLROUNDER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST, 'jersey_number' => '8'],
            ['full_name' => 'Isuru Bandara', 'date_of_birth' => '2009-11-03', 'player_category' => Player::CATEGORY_FINGER_SPIN_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN, 'jersey_number' => '9'],
            ['full_name' => 'Malith Samaraweera', 'date_of_birth' => '2011-04-17', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_LEG_SPIN, 'jersey_number' => '10'],
            ['full_name' => 'Pasindu Siriwardana', 'date_of_birth' => '2010-08-25', 'player_category' => Player::CATEGORY_WRIST_SPIN_BOWLER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_LEG_SPIN, 'jersey_number' => '11'],
            ['full_name' => 'Lahiru Kumara', 'date_of_birth' => '2012-01-08', 'player_category' => Player::CATEGORY_FAST_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_FAST, 'jersey_number' => '12'],
            ['full_name' => 'Chamika Gunasekara', 'date_of_birth' => '2009-07-30', 'player_category' => Player::CATEGORY_POWER_HITTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '13'],
            ['full_name' => 'Dineth Thilakarathne', 'date_of_birth' => '2011-12-12', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX, 'jersey_number' => '14'],
            ['full_name' => 'Ravindu Wickramasinghe', 'date_of_birth' => '2010-05-09', 'player_category' => Player::CATEGORY_SPIN_ALLROUNDER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN, 'jersey_number' => '15'],
            ['full_name' => 'Nipun Dananjaya', 'date_of_birth' => '2008-10-22', 'player_category' => Player::CATEGORY_MEDIUM_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '16'],
            ['full_name' => 'Sachith Pathirana', 'date_of_birth' => '2011-03-28', 'player_category' => Player::CATEGORY_FINGER_SPIN_BOWLER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX, 'jersey_number' => '17'],
            ['full_name' => 'Ashen Bandara', 'date_of_birth' => '2010-11-15', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '18'],
            ['full_name' => 'Dhananjaya Lakshan', 'date_of_birth' => '2009-04-07', 'player_category' => Player::CATEGORY_FAST_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST, 'jersey_number' => '19'],
            ['full_name' => 'Minod Bhanuka', 'date_of_birth' => '2012-06-18', 'player_category' => Player::CATEGORY_POWER_HITTER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '20'],
        ];

        foreach ($royalPlayers as $playerData) {
            $playerData['school_id'] = $school->id;
            $playerData['age_category'] = Player::calculateAgeCategory($playerData['date_of_birth']);
            Player::create($playerData);
        }

        // Create a Second Approved School for testing matches
        $pendingSchoolUser = User::create([
            'name' => 'Trinity Admin',
            'email' => 'trinity@school.lk',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SCHOOL,
            'email_verified_at' => now(),
        ]);

        $trinitySchool = School::create([
            'user_id' => $pendingSchoolUser->id,
            'school_name' => 'Trinity College',
            'school_type' => School::TYPE_GOVERNMENT,
            'district' => 'Kandy',
            'province' => 'Central',
            'school_address' => 'Chapel Road, Kandy',
            'contact_number' => '+94 81 222 4568',
            'cricket_incharge_name' => 'Mr. David Perera',
            'cricket_incharge_contact' => '+94 71 987 6543',
            'status' => School::STATUS_APPROVED,
        ]);

        // Create Sample Players for Trinity College (20 players)
        $trinityPlayers = [
            ['full_name' => 'Kavindu Seneviratne', 'date_of_birth' => '2010-01-12', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN, 'jersey_number' => '1'],
            ['full_name' => 'Nethsara Jayasuriya', 'date_of_birth' => '2011-05-08', 'player_category' => Player::CATEGORY_FAST_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST, 'jersey_number' => '2'],
            ['full_name' => 'Senura Dissanayake', 'date_of_birth' => '2009-09-20', 'player_category' => Player::CATEGORY_SPIN_ALLROUNDER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX, 'jersey_number' => '3'],
            ['full_name' => 'Thilina Rathnayake', 'date_of_birth' => '2012-03-05', 'player_category' => Player::CATEGORY_POWER_HITTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '4'],
            ['full_name' => 'Hasindu Rajapaksha', 'date_of_birth' => '2008-08-16', 'player_category' => Player::CATEGORY_FAST_BOWLING_ALLROUNDER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '5'],
            ['full_name' => 'Reshan Kawshalya', 'date_of_birth' => '2010-12-25', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '6'],
            ['full_name' => 'Pawan Rupasinghe', 'date_of_birth' => '2011-06-14', 'player_category' => Player::CATEGORY_MEDIUM_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '7'],
            ['full_name' => 'Vihanga Abeysekara', 'date_of_birth' => '2010-02-28', 'player_category' => Player::CATEGORY_FAST_BOWLING_ALLROUNDER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST, 'jersey_number' => '8'],
            ['full_name' => 'Theekshana Subasinghe', 'date_of_birth' => '2009-10-11', 'player_category' => Player::CATEGORY_FINGER_SPIN_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN, 'jersey_number' => '9'],
            ['full_name' => 'Dulaj Basnayake', 'date_of_birth' => '2011-08-03', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_LEG_SPIN, 'jersey_number' => '10'],
            ['full_name' => 'Sandun Weerakkody', 'date_of_birth' => '2010-04-19', 'player_category' => Player::CATEGORY_WRIST_SPIN_BOWLER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_LEG_SPIN, 'jersey_number' => '11'],
            ['full_name' => 'Himesh Gunawardena', 'date_of_birth' => '2012-07-07', 'player_category' => Player::CATEGORY_FAST_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_FAST, 'jersey_number' => '12'],
            ['full_name' => 'Avishka Jayaratne', 'date_of_birth' => '2009-03-15', 'player_category' => Player::CATEGORY_POWER_HITTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '13'],
            ['full_name' => 'Janith Liyanage', 'date_of_birth' => '2011-11-22', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX, 'jersey_number' => '14'],
            ['full_name' => 'Deshan Senarath', 'date_of_birth' => '2010-07-01', 'player_category' => Player::CATEGORY_SPIN_ALLROUNDER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_OFF_SPIN, 'jersey_number' => '15'],
            ['full_name' => 'Vishen Wanniarachchi', 'date_of_birth' => '2008-12-09', 'player_category' => Player::CATEGORY_MEDIUM_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_MEDIUM, 'jersey_number' => '16'],
            ['full_name' => 'Nirmana Herath', 'date_of_birth' => '2011-09-30', 'player_category' => Player::CATEGORY_FINGER_SPIN_BOWLER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_LEFT_ARM_ORTHODOX, 'jersey_number' => '17'],
            ['full_name' => 'Bhanuka Moragoda', 'date_of_birth' => '2010-10-18', 'player_category' => Player::CATEGORY_TOP_ORDER_BATTER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '18'],
            ['full_name' => 'Sithija Weerasinghe', 'date_of_birth' => '2009-06-24', 'player_category' => Player::CATEGORY_FAST_BOWLER, 'batting_style' => Player::BATTING_RIGHT_HAND, 'bowling_style' => Player::BOWLING_RIGHT_ARM_FAST, 'jersey_number' => '19'],
            ['full_name' => 'Lithmal Udugama', 'date_of_birth' => '2012-02-10', 'player_category' => Player::CATEGORY_POWER_HITTER, 'batting_style' => Player::BATTING_LEFT_HAND, 'bowling_style' => Player::BOWLING_DOES_NOT_BOWL, 'jersey_number' => '20'],
        ];

        foreach ($trinityPlayers as $playerData) {
            $playerData['school_id'] = $trinitySchool->id;
            $playerData['age_category'] = Player::calculateAgeCategory($playerData['date_of_birth']);
            Player::create($playerData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('-------------------');
        $this->command->info('Admin: admin@find11.com / password');
        $this->command->info('School (Royal College): royal@school.lk / password');
        $this->command->info('School (Trinity College): trinity@school.lk / password');
    }
}
