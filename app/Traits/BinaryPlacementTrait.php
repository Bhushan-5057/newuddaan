<?php

namespace App\Traits;

use App\Models\User;

trait BinaryPlacementTrait
{
    public function getBinaryPlacement(User $sponsor, $side = 'left')
    {
        $position = $side === 'right' ? 2 : 1;

        return $this->findPlacement($sponsor, $position);
    }

    private function findPlacement(User $parent, $position)
    {
        $child = User::where('parent_id', $parent->id)
            ->where('position', $position)
            ->first();

        if (!$child) {
            return [
                'parent' => $parent,
                'position' => $position
            ];
        }

        return $this->findPlacement($child, $position);
    }
}