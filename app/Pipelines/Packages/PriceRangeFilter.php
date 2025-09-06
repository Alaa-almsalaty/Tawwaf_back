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
        $currency = $this->request->input('currency'); // "usd" or "lyd"

        if (!filled($range) || !filled($currency)) {
            return $next($query);
        }

        $query->where(function (Builder $q) use ($currency, $range) {
            $q->where('currency', $currency);
            $priceCol = $currency === 'usd' ? 'total_price_usd' : 'total_price_dinar';
            [$min, $max] = $this->parseRange($range);
            if (!is_null($min)) {
                $q->where($priceCol, '>=', $min);
            }
            if (!is_null($max)) {
                $q->where($priceCol, '<=', $max);
            }
        });

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
