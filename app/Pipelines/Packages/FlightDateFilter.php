<?php

namespace App\Pipelines\Packages;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FlightDateFilter
{
    public function __construct(private Request $request) {}

    public function handle(Builder $query, Closure $next)
    {
        $dateStr = $this->request->input('date'); // "2025-09-10"
        if (!filled($dateStr)) {
            return $next($query);
        }

        $tolerance = max(0, (int)$this->request->input('date_tolerance_days', 5));
        $target    = Carbon::createFromFormat('Y-m-d', $dateStr)->startOfDay();

        $start = $target->copy()->subDays($tolerance);
        $end   = $target->copy()->addDays($tolerance)->endOfDay();


        $query->whereBetween('start_date', [$start, $end]);

        return $next($query);
    }
}
