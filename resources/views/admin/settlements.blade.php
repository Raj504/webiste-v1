<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Settlements – GymPass Admin</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>

<body>
    <div style="max-width:1200px;margin:0 auto;padding:32px 20px">

        <div class="flex items-center justify-between mb-20 flex-wrap gap-12">
            <div>
                <div class="t-display" style="font-size:24px;margin-bottom:4px">Settlements</div>
                <div class="t-muted" style="font-size:13px">
                    {{ $pendingCount }} pending · ₹{{ number_format($pendingAmount) }} to pay out
                </div>
            </div>
            <div class="flex gap-8">
                <a href="{{ route('admin.settlements') }}" class="chip {{ $status === null ? 'is-active' : '' }}">All</a>
                <a href="{{ route('admin.settlements', ['status' => 'pending']) }}" class="chip {{ $status === 'pending' ? 'is-active' : '' }}">Pending</a>
                <a href="{{ route('admin.settlements', ['status' => 'paid']) }}" class="chip {{ $status === 'paid' ? 'is-active' : '' }}">Paid</a>
            </div>
        </div>

        @if (session('status'))
            <div class="callout callout--green mb-20">
                <span class="callout__icon">✅</span>
                {{ session('status') }}
            </div>
        @endif

        <div class="panel">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Traveler</th>
                        <th>Gym</th>
                        <th>Pay To</th>
                        <th>Plan</th>
                        <th>Gross</th>
                        <th>Commission</th>
                        <th>Payout</th>
                        <th>Status</th>
                        <th>Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($settlements as $settlement)
                        @php $owner = $settlement->gym->owner; @endphp
                        <tr>
                            <td style="font-family:var(--font-mono);font-size:12px">{{ $settlement->booking->booking_ref ?? '—' }}</td>
                            <td>
                                {{ $settlement->booking->user->name ?? '—' }}<br>
                                <span class="t-muted" style="font-size:11px">{{ $settlement->booking->user->phone ?? '' }}</span>
                            </td>
                            <td>{{ $settlement->gym->name ?? '—' }}</td>
                            <td>
                                {{ $owner->name ?? '—' }}<br>
                                <span class="t-muted" style="font-size:11px">
                                    {{ $settlement->gym_upi_id ?: 'no UPI on file' }}
                                    @if ($owner && $owner->phone)
                                        · {{ $owner->phone }}
                                    @endif
                                </span>
                            </td>
                            <td>{{ $settlement->booking->plan->name ?? '—' }}</td>
                            <td>₹{{ number_format($settlement->gross_amount) }}</td>
                            <td class="t-muted">₹{{ number_format($settlement->commission_amount) }} ({{ $settlement->commission_pct }}%)</td>
                            <td class="t-brand" style="font-weight:700">₹{{ number_format($settlement->payout_amount) }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.settlements.toggle-paid', $settlement) }}">
                                    @csrf
                                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                                        <input type="checkbox" onchange="this.form.submit()" {{ $settlement->payout_status === 'paid' ? 'checked' : '' }}>
                                        <span class="pill {{ $settlement->payout_status === 'paid' ? 'pill--green' : 'pill--grey' }}">
                                            {{ ucfirst($settlement->payout_status) }}
                                        </span>
                                    </label>
                                </form>
                            </td>
                            <td class="t-muted" style="font-size:12px">
                                {{ $settlement->paid_at ? $settlement->paid_at->format('d M Y, h:i A') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="t-muted text-center" style="padding:32px">No settlements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-20">
            {{ $settlements->links() }}
        </div>

    </div>
</body>

</html>
