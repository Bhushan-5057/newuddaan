<li>
    <div class="tree-node {{ strtolower($node->active_status) }}">
        <div class="info">
            <div class="name">{{ $node->name }} ({{ $node->username }})</div>
            <div class="meta">Level: {{ $node->level }}</div>
            <div class="meta">Status: {{ $node->active_status }}</div>
            <div class="meta">Self: {{ company_info('currency', '$') . number_format($node->total_amount, 2) }}</div>
            <div class="meta">Team: {{ company_info('currency', '$') . number_format($node->current_business, 2) }}</div>
        </div>
    </div>


    @if(!empty($node->children) && $node->children->isNotEmpty())
        <ul>
            @foreach($node->children as $child)
                @include('user.genealogy.partials.tree-branch', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>

