<?php

namespace App\Livewire;

use App\Interfaces\BreadcrumbsFromUrlInterface;
use Livewire\Component;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str; // Для форматирования текста



class Breadcrumbs extends Component
{
    public $breadcrumbs = [];
    protected BreadcrumbsFromUrlInterface $breadcrumbsService;

    public function mount(BreadcrumbsFromUrlInterface $breadcrumbsService)
    {
        $this->breadcrumbsService = $breadcrumbsService;
        $this->breadcrumbs = $this->breadcrumbsService->generate();
    }

    public function render()
    {
        return view('livewire.breadcrumbs');
    }
}
