<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormTemplateSeeder extends Seeder
{
    public function run()
    {
        if (DB::table('form_templates')->where('name', 'Публикация Google Scholar')->doesntExist()) {
            $templateId = DB::table('form_templates')->insertGetId([
                'name' => 'Публикация Google Scholar',
                'description' => 'Форма редактирования публикаций, полученных из Google Scholar',
            ]);

            $this->seedTemplateFields($templateId);
            $this->seedForms($templateId);
        }
    }

    protected function seedTemplateFields($templateId)
    {
        $fields = [
            [
                'name' => 'title_google_scholar',
                'label' => 'Наименование',
                'type' => 'string',
                'required' => 1,
                'sort_order' => 0,
            ],
            [
                'name' => 'publisher_google_scholar',
                'label' => 'Издатель',
                'type' => 'string',
                'required' => 1,
                'sort_order' => 0,
            ],
            [
                'name' => 'authors_google_scholar',
                'label' => 'Авторы',
                'type' => 'string',
                'required' => 1,
                'sort_order' => 0,
            ],
        ];

        foreach ($fields as $field) {
            if (DB::table('template_fields')
                ->where('form_template_id', $templateId)
                ->where('name', $field['name'])
                ->doesntExist()) {

                DB::table('template_fields')->insert(array_merge($field, [
                    'form_template_id' => $templateId,
                ]));
            }
        }
    }

    protected function seedForms($templateId)
    {
        if (DB::table('forms')->where('title', 'Публикация Google Scholar')->doesntExist()) {
            // Получаем или создаем категорию
            $categoryId = DB::table('categories')
                ->where('name', 'Публикация Google Scholar')
                ->value('id');

            if (!$categoryId) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => 'Публикация Google Scholar',
                    // Добавьте другие необходимые поля для категории
                ]);
            }

            DB::table('forms')->insert([
                'title' => 'Публикация Google Scholar',
                'description' => '',
                'category_id' => $categoryId,
                'points' => '5',
                'form_template_id' => $templateId,
                'is_active' => 0,
                'single_entry' => 0,
                'slug' => 'publication-google-scholar', // Исправленный slug
            ]);
        }
    }
}
