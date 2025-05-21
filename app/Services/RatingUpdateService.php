<?php

namespace App\Services;

use App\Models\User;
use App\Models\FormEntry;
use App\Models\PenaltyPoints;
use Illuminate\Support\Carbon;

class RatingUpdateService
{
    public function recalculateForUser(int $user_id): int
    {
        // Получаем текущие границы квартала
        $startOfQuarter = $this->getCurrentQuarterStart();
        $endOfQuarter = $this->getCurrentQuarterEnd();

        // Получаем все достижения пользователя за текущий квартал и статусом "approved"
        $approvedEntries = FormEntry::where('user_id', $user_id)
            ->where('status', 'approved')
            ->whereBetween('date_achievement', [$startOfQuarter, $endOfQuarter])
            ->with('form') // Чтобы получить баллы из связанной формы
            ->get();

        // Суммируем баллы за достижения
        $totalPoints = $approvedEntries->sum(function ($entry) {
            return (int) optional($entry->form)->points ?? 0;
        });

        // Суммируем штрафные баллы за текущий квартал
        $penaltyPoints = PenaltyPoints::where('user_id', $user_id)
            ->whereBetween('date', [$startOfQuarter, $endOfQuarter])
            ->sum('penalty_points');

        // Вычисляем итоговый рейтинг
        $rating = max(0, $totalPoints - $penaltyPoints); // Рейтинг не может быть ниже 0

        // Обновляем рейтинг в таблице users
        User::where('id', $user_id)->update([
            'rating' => $rating
        ]);

        return $rating;
    }

    /**
     * Начало текущего квартала
     */
    private function getCurrentQuarterStart(): Carbon
    {
        $month = now()->month;
        $year = now()->year;

        $quarterStartMonth = match (true) {
            $month <= 3 => 1,
            $month <= 6 => 4,
            $month <= 9 => 7,
            default     => 10,
        };

        return Carbon::create($year, $quarterStartMonth, 1)->startOfDay();
    }

    /**
     * Конец текущего квартала
     */
    private function getCurrentQuarterEnd(): Carbon
    {
        return $this->getCurrentQuarterStart()->copy()->addMonths(3)->subDay()->endOfDay();
    }
}
