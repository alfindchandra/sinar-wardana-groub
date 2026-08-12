<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Super admin & owner selalu boleh melakukan apa saja.
     * (Dipanggil otomatis oleh Gate sebelum method lain di bawah,
     * jadi tidak perlu mengulang pengecekan role di tiap method.)
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole(['super_admin', 'owner']) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view_products');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->can('view_products');
    }

    public function create(User $user): bool
    {
        return $user->can('create_products');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('update_products');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('delete_products');
    }
}
