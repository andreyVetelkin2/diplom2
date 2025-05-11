<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_id' => 'required|string|max:255'
        ]);
        if($validated){

            $author = Author::updateOrCreate(
                           ['user_id' =>auth()->user()->id ],
                           ['author_id' =>  $request['author_id'] ]  );

            return redirect()->route('author.show', ['authorid' => $request['author_id']]);
        }

        return redirect()->back();

    }
    public function prep()
    {
        // Проверяем аутентификацию
        if (!auth()->check()) {
            $message = "Пользователь не аутентифицирован. Перенаправление на страницу входа.";
            $redirectUrl = route('login');

            return response(
                "<script>
                    console.log('$message');
                </script>"
            );
        }

        if (!auth()->user()->author || !auth()->user()->author->author_id) {
            return view('author.search');

        }

        $authorId = auth()->user()->author->author_id;
        $message = "Автор найден. Перенаправление на страницу автора ID: $authorId";
        $redirectUrl = route('author.show', ['authorid' => $authorId]);

        return redirect()->route('author.show');
    }

    public function show()
        {
        $authorId = auth()->user()->author->author_id;
        $author2 = Cache::remember("articles_{$authorId}", now()->addDays(1), function() use ($authorId) {
                              return $this->fetchOrCreateAuthor($authorId);
                          });

//          $author2 = $this->fetchOrCreateAuthor($authorId);


        if (!isset($author2['data']['author'])) {
            return back()->withErrors('Author data not found');
        }

        $articlesData = $author2['data'];
        $author = $author2['author'];

        // Пагинация статей
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($articlesData['articles'] ?? [], ($currentPage - 1) * $perPage, $perPage);

        $articles = new LengthAwarePaginator(
            $currentItems,
            count($articlesData['articles'] ?? []),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        $chartData = $this->prepareChartData($articlesData['cited_by']['graph'] ?? []);
        $h_index = $articlesData['cited_by']['table'][1]['h_index']['all'] ?? 0;
        $i10_index = $articlesData['cited_by']['table'][2]['i10_index']['all'] ?? 0;

        return view('author.show', compact('author', 'articles', 'chartData', 'h_index', 'i10_index'));
    }

    protected function fetchOrCreateAuthor($authorId)
    {
            $token =env('SERPAPI_KEY');
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
                throw new \Exception('Author data not found in API response');
            }

            $author = Author::updateOrCreate(
                   ['author_id' => $authorId],
                   [
                       'user_id' => auth()->user()->id,
                       'name' => $data['author']['name'] ?? 'Unknown',
                       'affiliation' => $data['author']['affiliations'] ?? null,
                       'email' => $data['author']['email'] ?? null,
                       'interests' => isset($data['author']['interests']) ? json_encode($data['author']['interests']) : null,
                       'cited_by' => $data['cited_by']['table'][0]['citations']['all'] ?? 0,
                   ]
               );
            User::updateOrCreate(
            ['id'=>auth()->user()->id],
            [
            'citations' => $data['cited_by']['table'][0]['citations']['all'] ?? 0,
            'hirsh' => $data['cited_by']['table'][1]['h_index']['all'] ?? 0,
            ]
            );

        return [
            'author' => $author,
            'data' => $data ?? []
        ];
    }



    protected function prepareChartData($citedByGraph)
    {
        $years = [];
        $citations = [];

        foreach ($citedByGraph as $item) {
            $years[] = $item['year'];
            $citations[] = $item['citations'];
        }

        return [
            'years' => $years,
            'citations' => $citations
        ];
    }







        protected function findAuthor(Request $request)
        {
            $mauthors = $request->input('mauthors');
            $token =env('SERPAPI_KEY');
            if(!!auth()->user()->author && !!auth()->user()->author->google_key){
                $token=auth()->user()->author->google_key;
            }

            $client = new Client();
            $response = $client->get("https://serpapi.com/search.json", [
                'query' => [
                    'engine' => 'google_scholar_profiles',
                    'mauthors' => $mauthors,
                    'api_key' => $token,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            $authors=$data['profiles'];


        return view('author.findAccount', compact('authors'));
    }

    public function select(Request $request)
    {
        $request->validate([
            'author_id' => 'required|string'
        ]);
        $selectedAuthor = Author::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['author_id' => $request->author_id]
        );

        return response()->json([
            'success' => true,
            'selected' => $selectedAuthor
        ]);
    }
}
