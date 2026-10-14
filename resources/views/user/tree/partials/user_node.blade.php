{{--
    Renders one node of the binary tree, then recurses into its children.

    Expects:
      $user         - the User for this node
      $level        - 0 for the root
      $treeMaxLevel - deepest level to render (recursion stops here)
      $treeNodes    - [id => User] for every node down to $treeMaxLevel
      $treeBv       - [tx_user_id => total package BV]

    Children come from $treeNodes and BV from $treeBv, so this partial issues no
    queries of its own. It used to run a SUM plus two lazy relation loads per
    node, and recursed to the bottom of the tree.
--}}
@php
    $treeMaxLevel = $treeMaxLevel ?? 4;
    $treeNodes = $treeNodes ?? [];
    $treeBv = $treeBv ?? [];

    $totalBV = $treeBv[(string) $user->id] ?? 0;

    // BV Badge Color
    if ($totalBV >= 100) {
        $badgeColor = '#28a745'; // Green
    } elseif ($totalBV > 0) {
        $badgeColor = '#ffc107'; // Yellow
    } else {
        $badgeColor = '#ff4108'; // Grey
    }

    // User Status
    if ($user->status == 0) {
        $text = 'Inactive';
        $color = '#dc3545';
    } elseif ($totalBV >= 100) {
        $text = 'Active';
        $color = '#28a745';
    } elseif ($totalBV > 0) {
        $text = 'Active';
        $color = '#ffc107';
    } else {
        $text = 'Pending';
        $color = '#6c757d';
    }

    // Children come from the parent_id/position map built by the controller,
    // not from left_id/right_id. Position 1 is the left slot, 2 the right; an
    // empty slot renders the Register placeholder.
    $treeChildren = $treeChildren ?? [];
    $slots = $treeChildren[(int) $user->id] ?? [];

    $leftChild = isset($slots[1]) ? ($treeNodes[$slots[1]] ?? null) : null;
    $rightChild = isset($slots[2]) ? ($treeNodes[$slots[2]] ?? null) : null;

    // At the depth limit, stop recursing. A child that exists but is not
    // rendered must not be drawn as an empty "Register" slot.
    $atMaxLevel = $level >= $treeMaxLevel;
    $rootUsername = auth()->user()->username;
@endphp

<a href="javascript:void(0);" title="{{ $user->name }} - {{ $user->username }}">
    {{-- data-user-id is what the drill-down handler reads to re-root here. --}}
    <div class="member-view-box" data-user-id="{{ $user->id }}">

               @if($user->is_free_id == 1)

                       <div class="{{ $user->status == 1 ? 'member-image' : 'member-image-inactive' }}"
             style="border:3px solid #0d6efd; border-radius:50%; ">
            <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ $user->name }}">
        </div>
               @else

        <div class="{{ $user->status == 1 ? 'member-image' : 'member-image-inactive' }}"
             style="border:3px solid {{ $badgeColor }}; border-radius:50%; ">
            <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ $user->name }}">
        </div>
        @endif




        <div class="member-footer">

            {{-- Name, then the member's chosen unique name, then the ID. --}}
            <div class="member-name" title="{{ $user->name }}">{{ $user->name }}</div>
            @if (!empty($user->unique_name))
                <div class="member-unique" title="{{ $user->unique_name }}">{{ $user->unique_name }}</div>
            @endif

             <div class="name">
                <span>{{ $user->username }}</span>

                <i class="fas fa-copy copy-username"
                   data-username="{{ $user->username }}"
                   title="Copy Username"></i>


                <span class="tree-status-badge"
                      style="background-color: {{ $badgeColor }};"
                      title="Total BV : {{ $totalBV }}">
                </span>

                @if($user->is_free_id == 1)
                    <span class="compensation-badge-tree">
                        <i class="fas fa-star" style="color:#0d6efd;"></i>
                    </span>
                @endif
            </div>

            <div style="color: {{ $color }}; font-weight:600;">
                <span>{{ $text }}</span>
            </div>

        </div>
    </div>
</a>

@unless ($atMaxLevel)
<ul class="{{ $level === 0 ? 'active' : '' }}">
    <li>
        @if ($leftChild)

            @include('user.tree.partials.user_node', [
                'user' => $leftChild,
                'level' => $level + 1,
                'treeMaxLevel' => $treeMaxLevel,
                'treeNodes' => $treeNodes,
                'treeBv' => $treeBv,
                'treeChildren' => $treeChildren,
            ])

        @else

            @php
                $leftLink = url('/register?ref=' . $rootUsername .
                    '&side=left&parent=' . $user->username);
            @endphp

            <div class="member-image-inactive">
                <img src="{{ asset('assets/images/1.png') }}" alt="Null">
            </div>

            <div class="member-footer">
                <div class="name">
                    <span>Null</span>
                </div>

                <button class="btn btn-register register-link"
                        data-link="{{ $leftLink }}">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </div>

        @endif
    </li>

    <li>
        @if ($rightChild)

            @include('user.tree.partials.user_node', [
                'user' => $rightChild,
                'level' => $level + 1,
                'treeMaxLevel' => $treeMaxLevel,
                'treeNodes' => $treeNodes,
                'treeBv' => $treeBv,
                'treeChildren' => $treeChildren,
            ])

        @else

            @php
                $rightLink = url('/register?ref=' . $rootUsername .
                    '&side=right&parent=' . $user->username);
            @endphp

            <div class="member-image-inactive">
                <img src="{{ asset('assets/images/1.png') }}" alt="Null">
            </div>

            <div class="member-footer">
                <div class="name">
                    <span>Null</span>
                </div>

                <button class="btn btn-register register-link"
                        data-link="{{ $rightLink }}">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </div>

        @endif
    </li>
</ul>
@endunless
