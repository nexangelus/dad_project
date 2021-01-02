<?php


namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class OrderFilter extends Filter {

    public function status(string $value = null): Builder {
        return $this->builder->whereIn('status', $value);
    }

    public function customer(string $value = null): Builder {
        return $this->builder->where('customer_id', '=', $value);
    }

    public function deliverer(string $value = null): Builder {
        return $this->builder->where('delivered_by', '=', $value);
    }

    public function cooker(string $value = null): Builder {
        return $this->builder->where('prepared_by', '=', $value);
    }

    public function sort(array $value = []): Builder {
        if (isset($value['by']) && !Schema::hasColumn('orders', $value['by'])) {
            return $this->builder;
        }

        return $this->builder->orderBy($value['by'] ?? 'created_at', $value['order'] ?? 'desc');
    }
}
