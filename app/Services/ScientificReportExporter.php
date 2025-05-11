<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWord\IOFactory;

class ScientificReportExporter
{
    public function exportIndividual(array $data): string
    {
        $phpWord = new PhpWord();

        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);
        $section = $phpWord->addSection();
        // Основная информация
        $section->addText('ТАБЛИЦА', [ 'bold' => true], ['align' => 'center']);
        $section->addText('индивидуальных результатов научной работы', ['bold' => true, ], ['align' => 'center']);


        $section->addText("___________________________  \t ____________________________", ['bold' => false ], ['align' => 'center']);
        $section->addText(" (должность) \t\t\t\t\t  (ФИО)",['bold' => false ], ['align' => 'center']);

        $section->addText("___________________________________________________________________________");
        $section->addText(" (кафедры)",['bold' => false ], ['align' => 'center']);

        $section->addText("с «{$data['date_from']}» ____________ 202__ г. по «{$data['date_to']}» ____________ 202__ г.");

        $section->addText("Индекс Хирша \t" . ($data['hirsh'] ?? '_____'));
        $section->addText("Количество цитирований \t" . ($data['citations'] ?? '_____'));

        $section->addTextBreak(1);

        // Заголовок таблицы
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 50,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'position' => new TablePosition(['align' => 'center']),
        ]);

        // Шапка таблицы
        $table->addRow();
        $table->addCell(800)->addText('№ п/п', ['bold' => true],['align' => 'center']);
        $table->addCell(3000)->addText('Наименование показателя', ['bold' => true],['align' => 'center']);
        $table->addCell(2000)->addText('Обозначение показателя', ['bold' => true],['align' => 'center']);
        $table->addCell(1500)->addText('Количе-ство баллов', ['bold' => true],['align' => 'center']);
        $table->addCell(4000)->addText('Выходные данные (обоснование)', ['bold' => true],['align' => 'center']);

        // Разделы
        foreach ($data['sections'] as $sectionName => $items) {
            // Строка раздела
            $table->addRow();
            $table->addCell(null, ['gridSpan' => 5])->addText($sectionName, ['bold' => true],['align' => 'center']);

            $index = 1;
            foreach ($items as $item) {
                $table->addRow();
                $table->addCell(800)->addText($index++);
                $table->addCell(3000)->addText($item['name']);
                $table->addCell(2000)->addText($item['code']);
                $table->addCell(1500)->addText($item['total']);
                $table->addCell(4000)->addText($item['justification']);
            }
        }

        $section->addTextBreak(2);

        // Подписи
        $section->addText('___________________________________');
        $section->addText( '(должность)',['align' => 'center']);

        $section->addText(" ______________ \t __________________ \t ____________________________");
        $section->addText(" (воинское звание) \t\t (подпись) \t\t\t\t (ФИО)");

        $section->addText('Начальник кафедры');
        $section->addText(" ______________ \t __________________ \t ____________________________");
        $section->addText(" (воинское звание) \t\t (подпись) \t\t\t\t (ФИО)");

        $section->addText("«_____» ______________ 202__ года");

        // Сохраняем файл
        Storage::makeDirectory('exports/reports/'.auth()->user()->id); // создаёт при необходимости

        $userid = auth()->id();
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $filename = "report-individual-" . now()->format('Y-m-d_H-i-s') . ".docx";
        $relativePath = "exports/reports/{$userid}/{$filename}";
        $fullPath = storage_path("app/{$relativePath}");

        $phpWord->save($fullPath, 'Word2007');

        return $filename;
    }

    public function exportDepartment(array $data): string
    {
        $phpWord = new PhpWord();

        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);
        $section = $phpWord->addSection();
        // Основная информация
        $section->addText('ТАБЛИЦА', [ 'bold' => true], ['align' => 'center']);
        $section->addText('результатов научной работы кафедры', ['bold' => true, ], ['align' => 'center']);

        $section->addText("___________________________________________________________________________");
        $section->addText(" (наименование кафедры)",['bold' => false ], ['align' => 'center']);

        $section->addText("с «{$data['date_from']}» ____________ 202__ г. по «{$data['date_to']}» ____________ 202__ г.");

        $section->addTextBreak(1);

        // Заголовок таблицы
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 50,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'position' => new TablePosition(['align' => 'center']),
        ]);

        // Шапка таблицы
        $table->addRow();
        $table->addCell(800)->addText('№ п/п', ['bold' => true],['align' => 'center']);
        $table->addCell(3000)->addText('Наименование показателя', ['bold' => true],['align' => 'center']);
        $table->addCell(2000)->addText('Обозначение показателя', ['bold' => true],['align' => 'center']);
        $table->addCell(1500)->addText('Количе-ство баллов', ['bold' => true],['align' => 'center']);
        $table->addCell(4000)->addText('Выходные данные (обоснование)', ['bold' => true],['align' => 'center']);

        // Разделы
        foreach ($data['sections'] as $sectionName => $items) {
            // Строка раздела
            $table->addRow();
            $table->addCell(null, ['gridSpan' => 5])->addText($sectionName, ['bold' => true],['align' => 'center']);

            $index = 1;
            foreach ($items as $item) {
                $table->addRow();
                $table->addCell(800)->addText($index++);
                $table->addCell(3000)->addText($item['name']);
                $table->addCell(2000)->addText($item['code']);
                $table->addCell(1500)->addText($item['total']);
                $table->addCell(4000)->addText($item['justification']);
            }
        }

        $section->addTextBreak(2);

        // Подписи
        $section->addText('Профессорско-педагогического состава кафедры, имеющий ученую степень     _____%.');
        $section->addText("Количество целочисленных ставок на кафедре \t\t\t\t\t    _____ .");



        $section->addText('Начальник кафедры');
        $section->addText(" ______________ \t __________________ \t ____________________________");
        $section->addText(" (воинское звание) \t\t (подпись) \t\t\t\t (ФИО)");

        $section->addText("«_____» ______________ 202__ года");

        // Сохраняем файл
        Storage::makeDirectory('exports/reports/'.auth()->user()->id); // создаёт при необходимости
        $userid = auth()->id();
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $filename = "report-department-" . now()->format('Y-m-d_H-i-s') . ".docx";
        $relativePath = "exports/reports/{$userid}/{$filename}";
        $fullPath = storage_path("app/{$relativePath}");

        $phpWord->save($fullPath, 'Word2007');

        return $filename;
    }
}
