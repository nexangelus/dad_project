<?php


namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;


class UserFilter extends Filter {

    public function name(string $value = null): Builder {
        return $this->builder->where('name', 'like', "%{$value}%");
    }

    public function email(string $value = null): Builder {
        return $this->builder->where('email', 'like', "%{$value}%");
    }

    public function type(string $value = null): Builder {
        return $this->builder->where('type', $value);
    }

    public function customers(): Builder {
        return $this->type("C");
    }

    public function employees(): Builder {
        return $this->builder->whereIn('type', ['EM', 'EC', 'ED']);
    }

    public function blocked(string $value = '1'): Builder {
        return $this->builder->where('blocked', $value);
    }

    public function logged_at(string $value): Builder {
        if($value === "1") {
            return $this->builder->whereNotNull('logged_at');
        }
        return $this->builder;
    }

    public function sort(array $value = []): Builder {
        if (isset($value['by']) && !Schema::hasColumn('users', $value['by'])) {
            return $this->builder;
        }

        return $this->builder->orderBy($value['by'] ?? 'created_at', $value['order'] ?? 'desc');
    }
}
