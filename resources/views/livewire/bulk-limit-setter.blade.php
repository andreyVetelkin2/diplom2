<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('message') }}
        </div>
    @endif
    <div class="mb-3">
        <label for="limitInput" class="form-label">Максимум баллов за квартал</label>
        <input
            type="number"
            id="limitInput"
            class="form-control"
            wire:model.defer="limit_ballov_na_kvartal"
            min="0"
        >
        @error('limit_ballov_na_kvartal')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Отмена</button>
        <button wire:click="updateAll" class="btn btn-primary">Применить ко всем</button>
    </div>
</div>

