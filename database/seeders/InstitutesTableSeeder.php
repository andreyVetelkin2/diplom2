<?php
namespace Database\Seeders;
use App\Models\Institute;
use Illuminate\Database\Seeder;
use App\Models\Position;

class InstitutesTableSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            ['name' => 'Саратовский военный ордена Жукова Краснознаменный институт войск национальной гвардии Российской Федерации'],
        ];

        foreach ($positions as $position) {
            Institute::updateOrCreate(
                ['name' => $position['name']]
            );
        }
    }
}
