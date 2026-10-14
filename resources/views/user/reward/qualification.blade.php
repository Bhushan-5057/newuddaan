@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-4">

    @php
        $ranks = \App\Helpers\RankRewardHelper::getRanks();
        $user = auth()->user();

        // ૧. યુઝરનો લાઈફટાઈમ ટોટલ બિઝનેસ (આ શરૂઆતમાં ફિક્સ રહેશે)
        $remainingLeft = (\App\Helpers\UserHelper::teamBusinessVolumeByType($user->id, 1, 'topup') ?? 0) +
            (\App\Models\AdminPowerBusiness::where('user_id', $user->id)->where('matching_type', 'topup')->where('position', 1)->sum('bv_amount') ?? 0);

        $remainingRight = (\App\Helpers\UserHelper::teamBusinessVolumeByType($user->id, 2, 'topup') ?? 0) +
            (\App\Models\AdminPowerBusiness::where('user_id', $user->id)->where('matching_type', 'topup')->where('position', 2)->sum('bv_amount') ?? 0);

        $accumulatedLeftRequired = 0;
        $accumulatedRightRequired = 0;
        $foundRunning = false;
    @endphp

    <div class="row g-4">
        @foreach($ranks as $index => $rank)
            @php
                $alreadyAchieved = \App\Models\RankHistory::where('user_id', $user->id)
                    ->where('rank', $rank['name'])
                    ->exists();

                // ક્યુમ્યુલેટિવ રિક્વાયર્ડ ટાર્ગેટ જે એડમિન મુજબ ચાલશે
                $accumulatedLeftRequired += $rank['left'];
                $accumulatedRightRequired += $rank['right'];

                // --- મુખ્ય ગણતરી (તમારા નિયમ મુજબ માઇનસ અને કેરી ફોરવર્ડ લોજિક) ---
                if ($alreadyAchieved) {
                    $status = 'Completed';
                    
                    // જો રેન્ક પૂરો થઈ ગયો હોય, તો ડાયરેક્ટ ટાર્ગેટ જેટલા જ BV બતાવો
                    $currentLeftAchieved = $rank['left'];
                    $currentRightAchieved = $rank['right'];
                    
                    // આગલા કાર્ડ માટે અત્યારના ટાર્ગેટ જેટલું વોલ્યુમ ઓરિજિનલ બેલેન્સમાંથી માઇનસ કરી દો
                    $remainingLeft = max(0, $remainingLeft - $rank['left']);
                    $remainingRight = max(0, $remainingRight - $rank['right']);
                } else {
                    // જો હજી અચીવ નથી થયો, તો કાં તો એ Running હશે અથવા Pending
                    if (!$foundRunning) {
                        $status = 'Running';
                        $foundRunning = true;
                    } else {
                        $status = 'Pending';
                    }

                    // જે બચેલો બિઝનેસ આગળથી આવ્યો છે, તેમાંથી આ કરન્ટ સ્ટેપમાં કેટલો વપરાય છે તે ચેક કરો
                    // તે ક્યારેય તેના ટાર્ગેટ ($rank['left']) થી વધવો ન જોઈએ
                    $currentLeftAchieved = min($remainingLeft, $rank['left']);
                    $currentRightAchieved = min($remainingRight, $rank['right']);

                    // આ સ્ટેપમાં વપરાયેલો બિઝનેસ મેઈન બેલેન્સમાંથી માઇનસ કરો જેથી આગળના કાર્ડ માટે વધેલો જ જાય
                    $remainingLeft = max(0, $remainingLeft - $currentLeftAchieved);
                    $remainingRight = max(0, $remainingRight - $currentRightAchieved);
                }

                // પ્રોગ્રેસ બાર પર્સન્ટેજ (ફક્ત આ કરન્ટ કાર્ડના ટાર્ગેટ મુજબ)
                $leftPercent = $rank['left'] > 0 ? min(100, ($currentLeftAchieved / $rank['left']) * 100) : 0;
                $rightPercent = $rank['right'] > 0 ? min(100, ($currentRightAchieved / $rank['right']) * 100) : 0;

                // ક્લીન ટેક્સ્ટ ફોર્મેટિંગ
                $cleanReward =  $rank['reward'];
                $cleanBonanza =  $rank['bonanza'];
            @endphp

            <div class="col-xl-4 col-lg-6">
                <div class="card h-100 border shadow-sm rounded-3">
                    
                    <div class="card-header border-bottom p-3">
                        <h4 class="mb-0 h5 fw-bold text-primary text-center">
                            {{ $rank['name'] }}
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        <table class="table table-striped align-middle border small mb-4">
                            <tbody>
                                <tr>
                                    <th width="50%" class="text-secondary fw-semibold">Required Left</th>
                                    <td class="fw-bold text-dark">{{ number_format($accumulatedLeftRequired) }} BV</td>
                                </tr>
                                <tr>
                                    <th class="text-secondary fw-semibold">Required Right</th>
                                    <td class="fw-bold text-dark">{{ number_format($accumulatedRightRequired) }} BV</td>
                                </tr>
                                <tr>
                                    <th class="text-secondary fw-semibold">Achieved Left</th>
                                    <td class="fw-bold text-dark">{{ number_format($currentLeftAchieved) }} BV</td>
                                </tr>
                                <tr>
                                    <th class="text-secondary fw-semibold">Achieved Right</th>
                                    <td class="fw-bold text-dark">{{ number_format($currentRightAchieved) }} BV</td>
                                </tr>
                                @if($rank['bonus'] > 0)
                                <tr>
                                    <th class="text-secondary fw-semibold">Bonus Wallet</th>
                                    <td class="fw-bold text-success">₹ {{ number_format($rank['bonus']) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="text-secondary fw-semibold">Reward Tier</th>
                                    <td class="text-dark fw-bold text-uppercase">{{ $cleanReward }}</td>
                                </tr>
                                <tr>
                                    <th class="text-secondary fw-semibold">Bonanza Attribute</th>
                                    <td class="text-dark fw-medium">{{ $cleanBonanza }}</td>
                                </tr>
                                <tr>
                                    <th class="text-secondary fw-semibold">Duration Window</th>
                                    <td class="text-muted">{{ $rank['duration'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-secondary fw-semibold">Current Status</th>
                                    <td>
                                        @if($status == 'Completed')
                                            <span class="badge bg-success px-2.5 py-1.5 text-uppercase small">Completed</span>
                                        @elseif($status == 'Running')
                                            <span class="badge bg-warning text-dark px-2.5 py-1.5 text-uppercase small">Running</span>
                                        @else
                                            <span class="badge bg-danger px-2.5 py-1.5 text-uppercase small">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="fw-semibold text-secondary">Left Node Progress</span>
                                <span class="fw-bold text-dark">{{ number_format($currentLeftAchieved) }} / {{ number_format($rank['left']) }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 16px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: {{ $leftPercent }}%; font-size: 10px;" 
                                     aria-valuenow="{{ $leftPercent }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($leftPercent, 0) }}%
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="fw-semibold text-secondary">Right Node Progress</span>
                                <span class="fw-bold text-dark">{{ number_format($currentRightAchieved) }} / {{ number_format($rank['right']) }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 16px;">
                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: {{ $rightPercent }}%; font-size: 10px;" 
                                     aria-valuenow="{{ $rightPercent }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($rightPercent, 0) }}%
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection