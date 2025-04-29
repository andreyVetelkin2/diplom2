<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormTemplate;
use App\Models\TemplateField;
use App\Models\FieldOption;

class ScientificAchievementFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Создаём шаблон формы
        $template = FormTemplate::create([
            'name' => 'Загрузка научного достижения',
            'description' => 'Форма для добавления информации о новом научном достижении',
        ]);

        // 2. Определяем поля шаблона
        $fields = [
            [
                'name' => 'title',
                'label' => 'Название достижения',
                'type' => 'string',
                'required' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'achieved_at',
                'label' => 'Дата достижения',
                'type' => 'datetime',
                'required' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'description',
                'label' => 'Краткое описание',
                'type' => 'string',
                'required' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'work_type',
                'label' => 'Вид научной работы',
                'type' => 'list',
                'required' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'published',
                'label' => 'Опубликовано',
                'type' => 'checkbox',
                'required' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($fields as $f) {
            $field = $template->fields()->create($f);

            // 3. Для поля списка добавляем опции
            if ($f['name'] === 'work_type') {
                $options = [
                    ['value' => 'article', 'label' => 'Статья', 'sort_order' => 1],
                    ['value' => 'conference', 'label' => 'Доклад на конференции', 'sort_order' => 2],
                    ['value' => 'monograph', 'label' => 'Монография', 'sort_order' => 3],
                    ['value' => 'patent', 'label' => 'Патент', 'sort_order' => 4],
                ];
                $field->options()->createMany($options);
            }
        }
    }
}
