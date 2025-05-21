<?php
namespace Database\Seeders;
use App\Models\Institute;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    public function run()
    {
        $institute = Institute::where('name', 'Саратовский военный ордена Жукова Краснознаменный институт войск национальной гвардии Российской Федерации')->first();

        if (!$institute) {
            throw new Exception('Институт не найден в базе данных');
        }

        $departments = [
            ['slug' => 'УПД', 'name' => 'Кафедра управления повседневной деятельностью'],
            ['slug' => 'ТСБПП', 'name' => 'Кафедра тактики служебно-боевого применения подразделений'],
            ['slug' => 'АБВТ', 'name' => 'Кафедра автомобилей, бронетанкового вооружения и техники'],
            ['slug' => 'ОП', 'name' => 'Кафедра огневой подготовки'],
            ['slug' => 'ОСБД', 'name' => 'Кафедра обеспечения служебно-боевой деятельности войск национальной гвардии РФ'],
            ['slug' => 'МИ', 'name' => 'Кафедра математики и информатики'],
            ['slug' => 'ТГП', 'name' => 'Кафедра теории и истории государства и права'],
            ['slug' => 'КАП', 'name' => 'Кафедра конституционного и административного права'],
            ['slug' => 'УПК', 'name' => 'Кафедра уголовного процесса и криминалистики'],
            ['slug' => 'ГП', 'name' => 'Кафедра гражданского права'],
            ['slug' => 'ФПС', 'name' => 'Кафедра физической подготовки и спорта'],
            ['slug' => 'ГСН', 'name' => 'Кафедра гуманитарных и социальных наук'],
            ['slug' => 'ВПП', 'name' => 'Кафедра военной педагогики и психологии'],
            ['slug' => 'ИЯ', 'name' => 'Кафедра иностранных языков'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['slug' => $department['slug']],
                [
                    'name' => $department['name'],
                    'institute_id' => $institute->id
                ]
            );
        }
    }
}
