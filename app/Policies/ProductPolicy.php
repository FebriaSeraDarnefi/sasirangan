<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function view(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    private function ownsProduct(
        User $user,
        Product $product
    ): bool {
        return $user->role === 'umkm'
            && $user->umkm !== null
            && $user->umkm->id === $product->umkm_id;
    }
}
