@extends('user.layouts.app')

@section('title', 'Generation')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0 fw-bold">Generation</h4>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <!-- Filter Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 d-flex align-items-center gap-2">
                            <i class="fas fa-filter text-primary"></i> Filter Users
                        </h5>
                        <form method="GET" action="{{ route('user.team.users') }}">
                            <div class="row g-3">
                                @if (Advance_info('register_name', 'yes') === 'yes')
                                    <div class="col-12 col-md-2">
                                        <label class="form-label small fw-semibold text-muted">Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ request('name') }}" placeholder="Search by Name">
                                    </div>
                                @endif

                                <div class="col-12 col-md-2">
                                    <label class="form-label small fw-semibold text-muted">Username</label>
                                    <input type="text" name="user_id" class="form-control"
                                        value="{{ request('user_id') }}" placeholder="Search by Username">
                                </div>

                                <div class="col-12 col-sm-6 col-md-2">
                                    <label class="form-label small fw-semibold text-muted">From Date</label>
                                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                                </div>

                                <div class="col-12 col-sm-6 col-md-2">
                                    <label class="form-label small fw-semibold text-muted">To Date</label>
                                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                                </div>

                                <div class="col-12 col-md-2">
                                    <label class="form-label small fw-semibold text-muted">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label class="form-label small fw-semibold text-muted">Level</label>
                                    <select name="level" class="form-select">
                                        <option value="">-- Level --</option>
                                        @for ($i = 1; $i <= 11; $i++)
                                            <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>
                                                Level {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-4 me-2">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                    <a href="{{ route('user.genealogy.direct-users') }}" class="btn btn-outline-danger px-4">
                                        <i class="fas fa-undo me-2"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users Section Container -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom p-4 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Generation List</h5>
                        <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $users->count() }} Users Found</span>
                    </div>

                    <div class="card-body p-0">
                        @if($users->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-user-friends fa-3x mb-3 text-secondary-subtle"></i>
                                <h5 class="fw-semibold text-dark">No Users Found</h5>
                                <p class="mb-0 mx-auto" style="max-width: 400px;">You currently don't have any users. When you refer someone, they will appear here.</p>
                            </div>
                        @else
                            <!-- DESKTOP VIEW: Table -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Sr No.</th>
                                            @if (Advance_info('register_name', 'yes') === 'yes')
                                                <th>Name</th>
                                            @endif
                                            <th>Username</th>
                                            <th>Position</th>
                                            <th>Join Date</th>
                                            <th>Status</th>
                                            <th>Level</th>
                                            <th>Sponsor</th>
                                            <th class="pe-4">Package</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $key => $user)
                                            <tr>
                                                <td class="ps-4">{{ $key + 1 }}</td>
                                                @if (Advance_info('register_name', 'yes') === 'yes')
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; min-width: 38px;">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold text-nowrap">
                                                                    {{ $user->name }}
                                                                    @if(isset($user->show_compensation_badge) && $user->show_compensation_badge)
                                                                        <span class="badge bg-warning text-dark ms-1 small">
                                                                            <i class="fas fa-star me-1"></i>Compensation
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif
                                                <td class="text-nowrap">{{ $user->username }}</td>
                                                <td>
                                                    @if ($user->position == 1)
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5">
                                                            <i class="fas fa-arrow-left me-1"></i> Left
                                                        </span>
                                                    @elseif ($user->position == 2)
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5">
                                                            <i class="fas fa-arrow-right me-1"></i> Right
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5">
                                                            <i class="fas fa-question-circle me-1"></i> Unknown
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ $user->created_at->format('Y-m-d') }}</span>
                                                        <small class="text-muted">{{ $user->created_at->format('h:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($user->status == 1)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fs-7">
                                                            <i class="fas fa-circle me-1 small"></i> Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fs-7">
                                                            <i class="fas fa-circle me-1 small"></i> Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark px-2.5 py-1.5">Level {{ $user->level }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-dark">{{ $user->sponsor_id }}</span>
                                                        <small class="text-muted">({{ $user->sponsor->name ?? '-' }})</small>
                                                    </div>
                                                </td>
                                                <td class="pe-4">
                                                    <span class="badge  border px-2.5 py-1.5">
                                                        <i class="fas fa-box-open text-muted me-1"></i>
                                                        {{ company_info('currency', '$') }}{{ number_format($user->total_amount, 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- MOBILE VIEW: Cards Layout -->
                            <div class="d-md-none p-3">
                                <div class="row g-3">
                                    @foreach($users as $key => $user)
                                        <div class="col-12">
                                            <div class="card border  shadow-sm rounded-3">
                                                <div class="card-body p-3">
                                                    <!-- Top row: Avatar, Name/Username & Status -->
                                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="text-muted fw-bold small">#{{ $key + 1 }}</span>
                                                            @if (Advance_info('register_name', 'yes') === 'yes')
                                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold small" style="width: 32px; height: 32px;">
                                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                </div>
                                                                <div class="fw-bold text-dark small">
                                                                    {{ $user->name }}
                                                                    @if(isset($user->show_compensation_badge) && $user->show_compensation_badge)
                                                                        <span class="badge bg-warning text-dark d-block mt-0.5" style="font-size: 0.65rem;">
                                                                            <i class="fas fa-star me-1"></i>Comp.
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="fw-bold text-dark small">{{ $user->username }}</div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            @if ($user->status == 1)
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2" style="font-size: 0.75rem;">Active</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2" style="font-size: 0.75rem;">Inactive</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Details Rows -->
                                                    @if (Advance_info('register_name', 'yes') === 'yes')
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="small text-muted fw-medium">Username:</span>
                                                            <span class="small fw-semibold text-dark">{{ $user->username }}</span>
                                                        </div>
                                                    @endif

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="small text-muted fw-medium">Position:</span>
                                                        @if ($user->position == 1)
                                                            <span class="badge bg-info-subtle text-info border border-info-subtle py-1 px-2" style="font-size: 0.75rem;">Left</span>
                                                        @elseif ($user->position == 2)
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2" style="font-size: 0.75rem;">Right</span>
                                                        @else
                                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-1 px-2" style="font-size: 0.75rem;">Unknown</span>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="small text-muted fw-medium">Join Date:</span>
                                                        <span class="small text-dark">{{ $user->created_at->format('Y-m-d h:i A') }}</span>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="small text-muted fw-medium">Level:</span>
                                                        <span class="badge bg-dark py-1 px-2" style="font-size: 0.75rem;">Level {{ $user->level }}</span>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="small text-muted fw-medium">Sponsor:</span>
                                                        <span class="small text-dark fw-medium">{{ $user->sponsor_id }} <span class="text-muted">({{ $user->sponsor->name ?? '-' }})</span></span>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="small text-muted fw-medium">Package:</span>
                                                        <span class="badge  border py-1 px-2" style="font-size: 0.8rem;">
                                                            {{ company_info('currency', '$') }}{{ number_format($user->total_amount, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection