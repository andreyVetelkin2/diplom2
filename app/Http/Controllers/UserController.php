<?php

namespace App\Http\Controllers;
use App\Models\FormTemplate;
use App\Models\Form;
use App\Models\User;
use App\Models\Author;
use App\Models\FormEntry;
use App\Models\TemplateField;
use App\Models\FieldEntryValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;


class UserController extends Controller
{
    //запускаю форму
    public function index()
    {
        $users = User::with('author')->get();
        return view('users.index', compact('users'));
    }

    //Обновление автор id, строка на форме
    public function updateAuthorId(Request $request, $userId)
    {
        $request->validate([
            'author_id' => 'required|string'
        ]);

        $user = User::findOrFail($userId);

        try {
            if ($user->author) {
                // Обновляем существующего автора
                $user->author->update(['author_id' => $request->author_id]);
            } else {
                // Создаем нового автора, если нет
                Author::create([
                    'user_id' => $user->id,
                    'author_id' => $request->author_id,
                    'name' => $user->name
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Author ID updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    //загрузка данных из файлика
//     public function uploadData(Request $request)
//     {
//         $request->validate([
//             'data_file' => 'required|file|mimes:json,txt|max:2048' // Ограничение 2MB
//         ]);
//
//         try {
//             $file = $request->file('data_file');
//             $content = file_get_contents($file->path());
//
//             // Проверяем и исправляем JSON
//             $fixedContent = $this->ensureValidJson($content);
//
//             // Декодируем JSON
//             $data = json_decode($fixedContent, true);
//
//             if (json_last_error() !== JSON_ERROR_NONE) {
//                 throw new \Exception('Неверный формат JSON: ' . json_last_error_msg());
//             }
//
//             // Обработка данных
//             $processedUsers = 0;
//             foreach ($data as $item) {
//                 if (empty($item['user_id'])) continue;
//
//                 $user = User::find($item['user_id']);
//                 if ($user) {
//                     $user->update([
//                         'citations' => $item['citations'] ?? null,
//                         'hirsh' => $item['hirsh'] ?? null
//                     ]);
//                  // Обновляем или создаем автора
//                  if (!empty($item['author_id'])) {
//                      $authorData = [
//                          'author_id' => $item['author_id'],
//                          'user_id' => $user->id,
//                          'name' => $user->name
//                      ];
//
//                      if ($user->author) {
//                          $user->author->update($authorData);
//                      } else {
//                          Author::create($authorData);
//                      }
//                      $updatedAuthors++;
//                  }
//                  if (!empty($item['articles']) && is_array($item['articles'])) {
//                         foreach ($item['articles'] as $article) {
//                             $this->storePublication(
//                                 $user,
//                                 $article['year'] ?? '',
//                                 $article['title'] ?? '',
//                                 $article['publication'] ?? '',
//                                 $article['authors'] ?? ''
//                             );
//                             $processedArticles++;
//                         }
//                  }
//
//
//                     $processedUsers++;
//                 }
//             }
//
//             return back()->with('success',[  'users' => "Обновлено пользователей: $updatedUsers",
//                                                       'authors' => "Обновлено авторов: $updatedAuthors"]);
//
//         } catch (\Exception $e) {
//             \Log::error('File upload failed', [
//                 'error' => $e->getMessage(),
//                 'file' => $request->file('data_file')->getClientOriginalName()
//             ]);
//
//             return back()->with('error', 'Ошибка: ' . $e->getMessage());
//         }
//     }
public function uploadData(Request $request)
{
    $request->validate([
        'data_file' => 'required|file|mimes:json,txt|max:2048'
    ]);

    try {
        $file = $request->file('data_file');
        $content = file_get_contents($file->path());

        // Проверяем и исправляем JSON
        $fixedContent = $this->ensureValidJson($content);

        // Декодируем JSON
        $data = json_decode($fixedContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Неверный формат JSON: ' . json_last_error_msg());
        }

        // Инициализируем счетчики
        $processedUsers = 0;
        $updatedAuthors = 0;
        $processedArticles = 0;

        foreach ($data as $item) {
            if (empty($item['user_id'])) continue;

            $user = User::with('author')->find($item['user_id']);
            if (!$user) continue;

            // Обновляем данные пользователя
            $user->update([
                'citations' => $item['citations'] ?? $user->citations,
                'hirsh' => $item['hirsh'] ?? $user->hirsh
            ]);

            // Обновляем или создаем автора
            if (!empty($item['author_id'])) {
                $authorData = [
                    'author_id' => $item['author_id'],
                    'user_id' => $user->id,
                    'name' => $user->name
                ];

                if ($user->author) {
                    $user->author->update($authorData);
                } else {
                    Author::create($authorData);
                }
                $updatedAuthors++;
            }
            Cache::put("articles_{$user->author->author_id}", function(){
                              return $item['json1'];
                          }, now()->addDays(100));
            Cache::put("graph_{$user->author->author_id}", function(){
                                          return $item['json2'];
                                      }, now()->addDays(100));


            // Обрабатываем статьи
            if (!empty($item['articles']) && is_array($item['articles'])) {
                foreach ($item['articles'] as $article) {
                    try {
                        $this->storePublication(
                            $user,
                            $article['year'] ?? '',
                            $article['title'] ?? '',
                            $article['publication'] ?? '',
                            $article['authors'] ?? ''
                        );
                        $processedArticles++;
                    } catch (\Exception $e) {
                        \Log::error('Error processing article', [
                            'error' => $e->getMessage(),
                            'article' => $article
                        ]);
                        continue;
                    }
                }
            }

            $processedUsers++;
        }

//         return back()->with('success', [
//             'users' => "Обновлено пользователей: $processedUsers",
//             'authors' => "Обновлено авторов: $updatedAuthors",
//             'articles' => "Добавлено публикаций: $processedArticles"
//         ]);

    return back()->with('success',[  'users' => "Обновлено пользователей: $updatedUsers",
                                                       'authors' => "Обновлено авторов: $updatedAuthors"]);
    } catch (\Exception $e) {
        \Log::error('File upload failed', [
            'error' => $e->getMessage(),
            'file' => $request->file('data_file')->getClientOriginalName()
        ]);

        return back()->with('error', 'Ошибка: ' . $e->getMessage());
    }
}
    //стучимся в гугл по апи
    protected function fetchOrCreateAuthor($authorId)
    {
               $token =env('SERPAPI_KEY');
               $invalid= false;
               if(!!auth()->user()->author && !!auth()->user()->author->google_key){
                   $token=auth()->user()->author->google_key;
               }

               $client = new Client();
               $response = $client->get("https://serpapi.com/search.json", [
                   'query' => [
                       'engine' => 'google_scholar_author',
                       'author_id' => $authorId,
                       'api_key' => $token,
                       'hl' => 'en'
                   ]
               ]);

               $data = json_decode($response->getBody(), true);


               if (!isset($data['author'])) {
                   $invalid =true;
               }else{

                   $author = Author::updateOrCreate(
                          ['author_id' => $authorId],
                          [
                              'name' => $data['author']['name'] ?? 'Unknown',
                              'affiliation' => $data['author']['affiliations'] ?? null,
                              'email' => $data['author']['email'] ?? null,
                              'interests' => isset($data['author']['interests']) ? json_encode($data['author']['interests']) : null,
                              'cited_by' => $data['cited_by']['table'][0]['citations']['all'] ?? 0,
                          ]
                      );
                   User::updateOrCreate(
                   ['id'=>$author->user_id],
                   [
                   'citations' => $data['cited_by']['table'][0]['citations']['all'] ?? 0,
                   'hirsh' => $data['cited_by']['table'][1]['h_index']['all'] ?? 0,
                   ]
                   );
               }

           return  [$data,$invalid];
    }

    //проход по выбраным юзерам и формирование файлика
//     public function fetchGoogleScholarData(Request $request)
//     {
//
//          $selectedUsers = $request->input('selected_users', []);
//          $data = [];
//          $invalids = [];
//          // 1. Собираем данные
//          foreach ($selectedUsers as $userId) {
//              $user = User::with('author')->findOrFail($userId);
//
//              if ($user->author && $user->author->author_id) {
//                  $authorData = $this->fetchOrCreateAuthor($user->author->author_id);
//                  if($authorData[1]==true){
//                     $invalids[]=$user->name;
//                  }
//
//                  foreach ($authorData[0]['articles'] as $article) {
//                      $this->storePublication(
//                          $user,
//                          $article['year'] ?? '',          // год публикации
//                          $article['title'] ?? '',         // заголовок
//                          $article['publication'] ?? '',   // издательство
//                          $article['authors'] ?? ''     // авторы
//                      );
//                  }
//
//                  $data[] = [
//                      'user_id' => $user->id,
//                      'author_id' => $user->author->author_id,
//                      'citations' => $user->citations,
//                      'hirsh' => $user->hirsh,
//                      'articles' => array_map(function($article) {
//                                                       return [
//                                                           'year' => $article['year'] ?? '',
//                                                           'title' => $article['title'] ?? '',
//                                                           'publication' => $article['publication'] ?? '',
//                                                           'authors' => $article['authors'] ?? ''
//                                                       ];
//                                                   }, $authorData[0]['articles'] ?? []),
// //     'json1' => $authorData[0]['articles'] ?? null,
// //     'json2' => isset($authorData[0]['cited_by']['graph']) ? $authorData[0]['cited_by']['graph'] : null
//                  ];
//
//              }
//          }
//
//          // 2. Формируем JSON с гарантией закрывающей скобки
//          $fileName = 'google_scholar_data_' . now()->format('Ymd_His') . '.json';
//          $tempFile = tempnam(sys_get_temp_dir(), 'scholar');
//
//          try {
//              $file = fopen($tempFile, 'w');
//
//              // Начало массива
//              fwrite($file, "[\n");
//
//              // Записываем элементы
//              $count = count($data);
//              foreach ($data as $index => $item) {
//                  $jsonLine = json_encode($item, JSON_UNESCAPED_UNICODE);
//                  fwrite($file, '    ' . $jsonLine);
//
//                  // Добавляем запятую, если это не последний элемент
//                  if ($index < $count - 1) {
//                      fwrite($file, ",");
//                  }
//                  fwrite($file, "\n");
//              }
//
//              // Закрываем массив
//              fwrite($file, "]");
//              fclose($file);
//
//              // 3. Проверяем и сохраняем
//              $content = file_get_contents($tempFile);
//              if (!json_decode($content)) {
//                  throw new \Exception('Сформирован невалидный JSON');
//              }
//
//              Storage::put($fileName, $content);
//
//              // 4. Дополнительная проверка
//              $savedContent = Storage::get($fileName);
//              if (!str_ends_with(trim($savedContent), ']')) {
//                  throw new \Exception('Файл поврежден при сохранении');
//              }
//          if($invalids != []){
//          return redirect()->back()
//                           ->with('download_file', $fileName)
//                           ->with('success',"Не удалось получить данные пользователей: " . implode(', ', $invalids) . ". \nПроверьте корректность данных." );
//          }
//      else{
//
//
//              return redirect()->back()
//                  ->with('download_file', $fileName)
//                  ->with('success', 'Данные успешно получены');
// }
//          }finally {
//              if (file_exists($tempFile)) {
//                  unlink($tempFile);
//              }
//          }
//     }
public function fetchGoogleScholarData(Request $request)
{
        // Устанавливаем лимиты для больших данных
        set_time_limit(3600); // 1 час
        ini_set('memory_limit', '1024M'); // 1GB

    $selectedUsers = $request->input('selected_users', []);
    $invalids = [];
    $fileName = 'google_scholar_data_' . now()->format('Ymd_His') . '.json';



    try {
        // Начало JSON массива
        Storage::put($fileName, '[');
        $firstItem = true;

        foreach ($selectedUsers as $userId) {
            try {
                $user = User::with('author')->findOrFail($userId);

                if (!$user->author || !$user->author->author_id) {
                    $invalids[] = "{$user->name} (нет Scholar ID)";
                    continue;
                }

                $authorData = $this->fetchOrCreateAuthor($user->author->author_id);

                if ($authorData[1] === true) {
                    $invalids[] = "{$user->name} (некорректные данные)";
                    continue;
                }

                // Обработка статей
                foreach ($authorData[0]['articles'] ?? [] as $article) {
                    $this->storePublication(
                        $user,
                        $article['year'] ?? '',
                        $article['title'] ?? '',
                        $article['publication'] ?? '',
                        $article['authors'] ?? ''
                    );
                }

                // Формируем элемент данных
                $item = [
                    'user_id' => $user->id,
                    'author_id' => $user->author->author_id,
                    'citations' => $user->citations,
                    'hirsh' => $user->hirsh,
                    'articles' => array_map(function($article) {
                        return [
                            'year' => $article['year'] ?? '',
                            'title' => $article['title'] ?? '',
                            'publication' => $article['publication'] ?? '',
                            'authors' => $article['authors'] ?? ''
                        ];
                    }, $authorData[0]['articles'] ?? []),
                        'json1' => $authorData[0]['articles'] ?? null,
                        'json2' => isset($authorData[0]['cited_by']['graph']) ? $authorData[0]['cited_by']['graph'] : null

                ];

                // Добавляем запятую перед всеми элементами кроме первого
                $jsonLine = ($firstItem ? '' : ',') . json_encode($item, JSON_UNESCAPED_UNICODE);
                Storage::append($fileName, $jsonLine);

                $firstItem = false;

            } catch (\Exception $e) {
                $invalids[] = "{$user->name} (ошибка: {$e->getMessage()})";
                continue;
            }
        }

        // Завершаем JSON массив
        Storage::append($fileName, ']');

        // Валидация результата
        $content = Storage::get($fileName);
        if (!json_decode($content)) {
            throw new \Exception('Сформирован невалидный JSON');
        }

        $response = redirect()->back()
            ->with('download_file', $fileName);

        if (!empty($invalids)) {
            return $response->with('warning',
                "Возникли проблемы с пользователями: " . implode(', ', $invalids));
        }

        return $response->with('success', 'Данные успешно экспортированы');

    } catch (\Exception $e) {
        // Удаляем частично созданный файл при ошибке
        if (Storage::exists($fileName)) {
            Storage::delete($fileName);
        }

        return redirect()->back()
            ->with('error', 'Ошибка экспорта: ' . $e->getMessage());
    }
}
   //проверка json в файле
   protected function ensureValidJson(string $jsonContent): string
   {
       $trimmedContent = trim($jsonContent);

       // Если контент пустой, возвращаем пустой массив
       if (empty($trimmedContent)) {
           return '[]';
       }

       // Если не хватает закрывающей скобки
       if (!str_ends_with($trimmedContent, ']')) {
           // Удаляем возможные запятые в конце
           $trimmedContent = rtrim($trimmedContent, ",");
           // Добавляем закрывающую скобку
           $trimmedContent .= ']';
       }

       return $trimmedContent;
   }

     public function storePublication(User $user,string $date, string $title_scholar,string $publisher_google_scholar, string $authors_google_scholar)
     {
             // Находим шаблон
             $template = FormTemplate::where('name', 'Публикация Google Scholar')->first();
             if (!$template) {
                 return response()->json(['error' => 'Template not found'], 404);
             }
             // Находим форму
             $form = Form::where('title', 'Публикация Google Scholar')
                         ->where('form_template_id', $template->id)
                         ->first();
             if (!$form) {
                 return response()->json(['error' => 'Form not found'], 404);
             }

             // Проверяем, существует ли уже такая публикация
            $existingPublication = FormEntry::where('user_id', $user->id)
                ->where('form_template_id', $template->id)
                ->whereDate('date_achievement', Carbon::createFromDate($date, 1, 1))
                ->whereHas('fieldEntryValues', function($query) use ($title_scholar) {  // Исправлено fieldValues на fieldEntryValues
                    $query->whereHas('templateField', function($q) {
                        $q->where('name', 'title_google_scholar');
                    })->where('value', $title_scholar);
                })
                ->first();

             if ($existingPublication) {
                 return; // Публикация уже существует, ничего не делаем
             }
             // Находим поля шаблона
             $titleField = TemplateField::where('form_template_id', $template->id)
                                      ->where('name', 'title_google_scholar')
                                      ->first();
             $publisherField = TemplateField::where('form_template_id', $template->id)
                                          ->where('name', 'publisher_google_scholar')
                                          ->first();

             $authorsField = TemplateField::where('form_template_id', $template->id)
                                                       ->where('name', 'authors_google_scholar')
                                                       ->first();

             if (!$titleField || !$publisherField || !$authorsField) {
                 return response()->json(['error' => 'Template fields not found'], 404);
             }

             // Создаем запись формы
             $formEntry = FormEntry::create([
                 'form_template_id' => $template->id,
                 'user_id' => $user->id,
                 'form_id' => $form->id,
                 'status' => 'review',
                 'date_achievement' => Carbon::createFromDate($date, 1, 1), // 1 января указанного года
                 'comment' => 'Автоматическая публикация. Измените процент участия и дату публикации',
                 'percent' => 0,

             ]);

             // Создаем значения полей
             FieldEntryValue::create([
                 'form_entry_id' => $formEntry->id,
                 'template_field_id' => $titleField->id,
                 'value' => $title_scholar,

             ]);

             FieldEntryValue::create([
                 'form_entry_id' => $formEntry->id,
                 'template_field_id' => $publisherField->id,
                 'value' => $publisher_google_scholar,
             ]);

             FieldEntryValue::create([
                 'form_entry_id' => $formEntry->id,
                 'template_field_id' => $authorsField->id,
                 'value' => $authors_google_scholar,
             ]);

     }
public function downloadFile($filename)
{
    // Проверяем существование файла
    if (!Storage::exists($filename)) {
        return back()->with('error', 'Файл не найден или был удалён.');
    }

    // Удаляем файл после скачивания (опционально)
    $fileContent = Storage::get($filename);
    Storage::delete($filename);

    // Возвращаем файл для скачивания
    return response()->make($fileContent, 200, [
        'Content-Type' => 'application/json',
        'Content-Disposition' => 'attachment; filename="google_scholar_data.json"',
    ]);
}

}

