<div class="container my-4 p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title ">Фильтр по дате</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="applyFilters">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="startDate_{{ $uniqueId }}" class="form-label">Начальная дата</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>
                            <input
                                type="date"
                                class="form-control"
                                id="startDate_{{ $uniqueId }}"
                                wire:model="startDate"
                            >
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="endDate_{{ $uniqueId }}" class="form-label">Конечная дата</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>
                            <input
                                type="date"
                                class="form-control"
                                id="endDate_{{ $uniqueId }}"
                                wire:model="endDate"
                            >
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-funnel me-2"></i>Применить
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary" wire:click="setQuickDate('week')">
                                Неделя
                            </button>
                            <button type="button" class="btn btn-outline-secondary" wire:click="setQuickDate('month')">
                                Месяц
                            </button>
                            <button type="button" class="btn btn-outline-secondary" wire:click="setQuickDate('year')">
                                Год
                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                wire:click="setQuarter(1)"
                            >
                                I квартал
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                wire:click="setQuarter(2)"
                            >
                                II квартал
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                wire:click="setQuarter(3)"
                            >
                                III квартал
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                wire:click="setQuarter(4)"
                            >
                                IV квартал
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
