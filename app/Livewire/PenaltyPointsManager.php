<?php
namespace App\Livewire;


use App\Services\RatingUpdateService;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\PenaltyPoints;

class PenaltyPointsManager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'penalty_points';
    public string $sortDirection = 'desc';

    public bool $showEditModal = false;
    public ?int $userId = null;
    public int $points = 0;


    public $ratingService;

    protected $rules = [
        'points' => 'required|integer|min:0|max:100',
    ];

    public function getSelectedUserProperty()
    {
        return $this->userId ? User::find($this->userId) : null;
    }

    public function updatingSearch() { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';

        $this->sortField = $field;
    }

    public function edit(int $userId): void
    {
        $this->userId = $userId;
        $this->points = PenaltyPoints::where('user_id', $userId)->value('penalty_points') ?? 0;
        $this->showEditModal = true;
    }

    public function save(): void
    {
        $this->validate();


        PenaltyPoints::create([
            'user_id' => $this->userId,
            'penalty_points' => $this->points,
        ]);

        $this->resetModal();
        $this->ratingService = app(RatingUpdateService::class);
        $this->ratingService->recalculateForUser($this->userId);
        session()->flash('message', 'Штрафные баллы успешно добавлены!');
    }

    public function resetModal(): void
    {
        $this->reset(['showEditModal', 'userId', 'points', ]);
        $this->resetErrorBag();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->with('penaltyPoints')
            ->leftJoin('penalty_points', 'users.id', '=', 'penalty_points.user_id')
            ->select('users.*', 'penalty_points.penalty_points')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('users.name', 'like', "%{$this->search}%")
                        ->orWhere('users.email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.penalty-points-manager', [
            'users' => $this->users,
            'selectedUser' => $this->selectedUser,
        ]);
    }
}
