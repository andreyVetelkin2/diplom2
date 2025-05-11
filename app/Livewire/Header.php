<?php

namespace App\Livewire;
use App\Livewire\Actions\Logout;
use App\Models\FormEntry;
use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Livewire\Component;

class Header extends Component
{

    public $links;
    public $username;
    public $reviewFormsCount;

    public function mount($links = [])
    {
        $this->links = $links;
        $this->username = optional(auth()->user())->name ?? 'Guest';
        $this->getReviewFormsCount();

    }

    public function getReviewFormsCount(){
        $permission  = Permission::where('slug', 'review-forms')->first();
        $user = auth()->user();
        if ($user->hasPermissionTo($permission)){

            $this->reviewFormsCount = FormEntry::where('status', 'review')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->count();
        }
    }

    public function render()
    {
        return view('livewire.header');
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}
