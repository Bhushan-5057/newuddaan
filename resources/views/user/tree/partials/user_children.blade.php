{{--
    In-place expand under a clicked left/right member.
    Opens config(tree.drilldown_levels) levels on THAT side only (onlyPosition).
    The opposite slot still shows Null/Register so the binary layout stays.
--}}
@php
    $treeNodes = $treeNodes ?? [];
    $treeBv = $treeBv ?? [];
    $treeChildren = $treeChildren ?? [];
    $treeMaxLevel = max(1, (int) ($treeMaxLevel ?? 3));
    $childMaxLevel = max(0, $treeMaxLevel - 1);
    $onlyPosition = isset($onlyPosition) ? (int) $onlyPosition : null;
    if ($onlyPosition !== 1 && $onlyPosition !== 2) {
        $onlyPosition = null;
    }
    $slots = $treeChildren[(int) $parent->id] ?? [];
    $leftChild = isset($slots[1]) ? ($treeNodes[$slots[1]] ?? null) : null;
    $rightChild = isset($slots[2]) ? ($treeNodes[$slots[2]] ?? null) : null;
    $rootUsername = auth()->user()->username;
@endphp
<ul class="is-loaded active">
    {{-- Always draw both slots; onlyPosition data means the other side is Null. --}}
    <li>
        @if ($leftChild && ($onlyPosition === null || $onlyPosition === 1))
            {{-- onlyPosition null in the node so both L/R slots draw; data is
                 already filtered to the clicked side, so the other slot is Null. --}}
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
        @if ($rightChild && ($onlyPosition === null || $onlyPosition === 2))
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
