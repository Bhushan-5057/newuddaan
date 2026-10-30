{{--
    In-place expand under a clicked member.
    Opens config(tree.drilldown_levels) levels on BOTH left and right.
    Null/Register only when that side has no real child.
--}}
@php
    $treeNodes = $treeNodes ?? [];
    $treeBv = $treeBv ?? [];
    $treeChildren = $treeChildren ?? [];
    $treeMaxLevel = max(1, (int) ($treeMaxLevel ?? 3));
    $childMaxLevel = max(0, $treeMaxLevel - 1);
    $slots = $treeChildren[(int) $parent->id] ?? [];
    $leftChild = isset($slots[1]) ? ($treeNodes[$slots[1]] ?? null) : null;
    $rightChild = isset($slots[2]) ? ($treeNodes[$slots[2]] ?? null) : null;
    $rootUsername = auth()->user()->username;
@endphp
<ul class="is-loaded active">
    <li>
        @if ($leftChild)
            @include('user.tree.partials.user_node', [
                'user' => $leftChild,
                'level' => 0,
                'treeMaxLevel' => $childMaxLevel,
                'treeNodes' => $treeNodes,
                'treeBv' => $treeBv,
                'treeChildren' => $treeChildren,
                'onlyPosition' => null,
                'nodeSide' => 1,
            ])
        @else
            @php
                $leftLink = url('/register?ref=' . $rootUsername .
                    '&side=left&parent=' . $parent->username);
            @endphp
            <div class="member-image-inactive">
                <img src="{{ asset('assets/images/1.png') }}" alt="Null">
            </div>
            <div class="member-footer">
                <div class="name"><span>Null</span></div>
                <button class="btn btn-register register-link" data-link="{{ $leftLink }}">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </div>
        @endif
    </li>
    <li>
        @if ($rightChild)
            @include('user.tree.partials.user_node', [
                'user' => $rightChild,
                'level' => 0,
                'treeMaxLevel' => $childMaxLevel,
                'treeNodes' => $treeNodes,
                'treeBv' => $treeBv,
                'treeChildren' => $treeChildren,
                'onlyPosition' => null,
                'nodeSide' => 2,
            ])
        @else
            @php
                $rightLink = url('/register?ref=' . $rootUsername .
                    '&side=right&parent=' . $parent->username);
            @endphp
            <div class="member-image-inactive">
                <img src="{{ asset('assets/images/1.png') }}" alt="Null">
            </div>
            <div class="member-footer">
                <div class="name"><span>Null</span></div>
                <button class="btn btn-register register-link" data-link="{{ $rightLink }}">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </div>
        @endif
    </li>
</ul>
