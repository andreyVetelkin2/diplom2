<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\JcTable;
use Illuminate\Support\Facades\Storage;


class ScientificReportExporter
{
    protected array $config = [
        'individual' => [
            'subtitle' => 'индивидуальных результатов научной работы',
            'org_line' => '___________________________  \t ____________________________',
            'org_labels' => '(должность) \t\t\t\t\t  (ФИО)',
            'footer' => [
                '___________________________________',
                '(должность)',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                'Начальник кафедры',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                '«_____» ______________ 202__ года',
            ],
        ],
        'position' => [
            'subtitle' => 'результатов научной работы сотрудников',
            'org_line' => '___________________________  \t ____________________________',
            'org_labels' => '(должность) \t\t\t\t\t  (ФИО)',
            'footer' => [
                '___________________________________',
                '(должность)',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                'Начальник кафедры',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                '«_____» ______________ 202__ года',
            ],
        ],
        'forms' => [
            'subtitle' => 'результатов научной работы сотрудников',
            'org_line' => '___________________________  \t ____________________________',
            'org_labels' => '(должность) \t\t\t\t\t  (ФИО)',
            'footer' => [
                '___________________________________',
                '(должность)',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                'Начальник кафедры',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                '«_____» ______________ 202__ года',
            ],
        ],
        'department' => [
            'subtitle' => 'результатов научной работы кафедры',
            'org_line' => '___________________________________________________________________________',
            'org_labels' => '(наименование кафедры)',
            'footer' => [
                'Профессорско-педагогического состава кафедры, имеющий ученую степень     _____%.',
                'Количество целочисленных ставок на кафедре \t\t\t\t\t    _____.',
                'Начальник кафедры',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                '«_____» ______________ 202__ года',
            ],
        ],
        'user' => [
            'subtitle' => 'результатов научной работы сотрудников',
            'org_line' => '___________________________________________________________________________',
            'org_labels' => '(наименование кафедры)',
            'footer' => [
                'Профессорско-педагогического состава кафедры, имеющий ученую степень     _____%.',
                'Количество целочисленных ставок на кафедре \t\t\t\t\t    _____.',
                'Начальник кафедры',
                ' ______________ \t __________________ \t ____________________________',
                '(воинское звание) \t\t (подпись) \t\t\t\t (ФИО)',
                '«_____» ______________ 202__ года',
            ],
        ],
    ];

    public function exportReport(string $type, array $data): string
    {
        if (!isset($this->config[$type])) {
            throw new \InvalidArgumentException("Unknown report type: {$type}");
        }

        $conf = $this->config[$type];
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $sect = $phpWord->addSection();
        // Заголовок
        $sect->addText('ТАБЛИЦА', ['bold' => true], ['align' => 'center']);
        $sect->addText($conf['subtitle'], ['bold' => true], ['align' => 'center']);
        $sect->addText($conf['org_line'], [], ['align' => 'center']);
        $sect->addText($conf['org_labels'], [], ['align' => 'center']);
        $sect->addText("с «{$data['date_from']}» — «{$data['date_to']}»");
        $sect->addText("Индекс Хирша: " . ($data['blocks'][0]['hirsh'] ?? '_____'));
        $sect->addText("Цитирования: " . ($data['blocks'][0]['citations'] ?? '_____'));
        $sect->addTextBreak(1);

        // Для каждого блока (пользователя) своя таблица
        foreach ($data['blocks'] as $block) {
            $sect->addText($block['full_name'], ['bold' => true], ['align' => 'center']);
            $sect->addText("Должность: {$block['position']}; Кафедра: {$block['department']}");
            $sect->addTextBreak(1);

            $table = $sect->addTable([
                'borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 50,
                'alignment' => JcTable::CENTER
            ]);
            // шапка
            $table->addRow();
            foreach (['№ п/п', 'Показатель', 'Обозначение', 'Баллы', 'Выходные данные'] as $text) {
                $table->addCell(($text === 'Показатель' ? 3000 : ($text === 'Обозначение' ? 2000 : ($text === 'Баллы' ? 1500 : 4000))))
                    ->addText($text, ['bold' => true], ['align' => 'center']);
            }
            // разделы
            foreach ($block['sections'] as $sectionData) {
                $table->addRow();
                $table->addCell(null, ['gridSpan' => 5])
                    ->addText($sectionData['category'], ['bold' => true], ['align' => 'center']);
                $i = 1;
                foreach ($sectionData['forms'] as $form) {
                    $table->addRow();
                    $table->addCell(800)->addText($i++);
                    $table->addCell(3000)->addText($form['name']);
                    $table->addCell(2000)->addText($form['code']);
                    $table->addCell(1500)->addText($form['total']);
                    $table->addCell(4000)->addText($form['entries_data'], [], ['preserveLineBreaks' => true]);
                }
            }
            //$sect->addPageBreak();
        }

        // подписи (после всех блоков)
        foreach ($conf['footer'] as $line) {
            $sect->addText($line);
        }

        // сохранение
        $uid = auth()->id();
        Storage::makeDirectory("exports/reports/{$uid}");
        $fn = "report-{$type}-" . now()->format('Y-m-d_His') . ".docx";
        $path = storage_path("app/exports/reports/{$uid}/{$fn}");

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);
        return $fn;
    }
}
