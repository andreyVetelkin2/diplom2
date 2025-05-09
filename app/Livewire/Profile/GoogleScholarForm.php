<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Author;

class GoogleScholarForm extends Component
{
    public $author_id = '';
    public $google_key = '';

    public function mount()
    {
        $author = Author::where('user_id', auth()->id())->first();

        if ($author) {
            $this->author_id = $author->author_id ?? '';
            $this->google_key = $author->google_key ?? '';
        }
    }

    public function updateScholarData()
    {
        $validatedData = $this->validate([
            'author_id' => ['nullable', 'string', 'max:255'],
            'google_key' => ['nullable', 'string', 'max:255'],
        ]);

        Author::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'author_id' => !empty($validatedData['author_id']) ? $validatedData['author_id'] : null,
                'google_key' => !empty($validatedData['google_key']) ? $validatedData['google_key'] : null
            ]
        );

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.profile.google-scholar-form');
    }
}
