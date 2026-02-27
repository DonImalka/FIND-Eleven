<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\PlayerCategory;
use Illuminate\Database\Seeder;

class PlayerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = Player::getDefaultPlayerCategories();

        foreach ($defaults as $category) {
            PlayerCategory::firstOrCreate(
                ['name' => $category],
                ['is_active' => true, 'is_default' => true]
            );
        }
    }
}
