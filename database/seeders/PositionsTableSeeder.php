<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionsTableSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            ['name' => 'Начальник'],
            ['name' => 'Заместитель начальника'],
            ['name' => 'Профессор'],
            ['name' => 'Доцент'],
            ['name' => 'Старший преподаватель'],
            ['name' => 'Преподаватель'],
        ];

        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['name' => $position['name']]
            );
        }
    }
}
