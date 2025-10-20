<?php

namespace App\Pipelines\Packages;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PriceRangeFilter
{
    public function __construct(private Request $request)
    {
    }

    public function handle(Builder $query, Closure $next)
    {
        $range = $this->request->input('price');    // "1000-1500" or "2000+"
        $currency = $this->request->input('currency'); // "usd" or "dinar"

        if (!filled($range) || !filled($currency)) {
            return $next($query);
        }

        [$min, $max] = $this->parseRange($range);

        // Scope packages by package currency and by any related room price matching the range
        $priceCol = $currency === 'usd' ? 'total_price_usd' : 'total_price_dinar';

        $query->where(function (Builder $q) use ($currency, $priceCol, $min, $max) {
            // keep package currency consistent if packages still have currency column
            $q->where('currency', $currency)
                ->whereHas('rooms', function (Builder $rq) use ($priceCol, $min, $max) {
                    if (!is_null($min)) {
                        $rq->where($priceCol, '>=', $min);
                    }
                    if (!is_null($max)) {
                        $rq->where($priceCol, '<=', $max);
                    }
                });
        });

        $query->distinct();

        return $next($query);
    }


    private function parseRange(string $range): array
    {
        $range = str_replace(' ', '', $range);

        // "2000+"
        if (str_ends_with($range, '+')) {
            $min = (int) rtrim($range, '+');
            return [$min, null];
        }

        // "1000-1500"
        if (preg_match('/^(\d+)-(\d+)$/', $range, $m)) {
            $min = (int) $m[1];
            $max = (int) $m[2];
            if ($min > $max)
                [$min, $max] = [$max, $min];
            return [$min, $max];
        }

        // fallback (ignore invalid)
        return [null, null];
    }
}
