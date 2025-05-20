<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\FormTemplate;
use App\Models\Form;
use App\Models\TemplateField;

class PublicationGoogleScholarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Создаем или обновляем шаблон формы для публикаций Google Scholar
        $template = FormTemplate::updateOrCreate(
            ['name' => 'Публикация Google Scholar'],
            ['description' => 'Шаблон для автоматического добавления публикаций из Google Scholar']
        );

        $category = Category::updateOrCreate([
            'name' => 'Публикации Google Scholar'
        ]);

        // Создаем или обновляем форму, привязанную к шаблону
        $form = Form::updateOrCreate(
            [
                'title' => 'Публикация Google Scholar',
                'form_template_id' => $template->id,
                'category_id' => $category->id,
            ],
            ['is_active' => true]
        );

        // Определяем поля, необходимые для функции storePublication
        $fields = [
            [
                'name' => 'title_google_scholar',
                'label' => 'Название публикации',
                'type' => 'text',
                'sort_order' => 1,
                'required' => 1,
            ],
            [
                'name' => 'publisher_google_scholar',
                'label' => 'Издатель',
                'type' => 'text',
                'sort_order' => 2,
                'required' => 1,
            ],
            [
                'name' => 'authors_google_scholar',
                'label' => 'Авторы',
                'type' => 'textarea',
                'sort_order' => 3,
                'required' => 0,
            ],
        ];

        // Создаем или обновляем поля шаблона
        foreach ($fields as $fieldData) {
            TemplateField::updateOrCreate(
                [
                    'form_template_id' => $template->id,
                    'name' => $fieldData['name'],
                ],
                [
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'sort_order' => $fieldData['order'],
                    'required' => $fieldData['required'],
                ]
            );
        }

        $this->command->info('Seeder for Google Scholar publications template executed successfully.');
    }
}
