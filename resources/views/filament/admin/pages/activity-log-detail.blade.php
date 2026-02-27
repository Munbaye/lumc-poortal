@php
    // Filament passes the RAW database value in modalContent — not the Eloquent-cast
    // result. We normalise both old_values and new_values to plain PHP arrays here.
    $decode = function (mixed $v): array {
        if (is_array($v))  return $v;
        if (!$v)           return [];
        if (is_string($v)) { $d = json_decode($v, true); return is_array($d) ? $d : []; }
        return [];
    };

    $old     = $decode($log->old_values);
    $new     = $decode($log->new_values);
    $allKeys = collect(array_keys($old + $new))->unique()->sort()->values();
@endphp

<div style="font-size:.83rem;color:#111827;" class="dark:text-gray-100">

    {{-- ── Metadata row ──────────────────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;
                padding:12px 14px;background:#f9fafb;border-radius:8px;margin-bottom:16px;
                border:1px solid #e5e7eb;"
         class="dark:bg-gray-800 dark:border-gray-700">
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.05em;
                      color:#9ca3af;margin-bottom:2px;">When</p>
            <p style="font-weight:600;">{{ $log->created_at->format('M d, Y H:i:s') }}</p>
            <p style="font-size:.72rem;color:#9ca3af;">{{ $log->created_at->diffForHumans() }}</p>
        </div>
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.05em;
                      color:#9ca3af;margin-bottom:2px;">Who</p>
            <p style="font-weight:600;">{{ $log->user?->name ?? 'System / Guest' }}</p>
            <p style="font-size:.72rem;color:#9ca3af;">{{ $log->ip_address }}</p>
        </div>
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.05em;
                      color:#9ca3af;margin-bottom:2px;">Action</p>
            <p style="font-weight:600;">
                {{ match ($log->action) {
                    'login'               => 'Logged In',
                    'logout'              => 'Logged Out',
                    'login_failed'        => 'Login Failed',
                    'created_patient'     => 'Patient Created',
                    'updated_patient'     => 'Patient Updated',
                    'recorded_vitals'     => 'Vitals Recorded',
                    'assessed_patient'    => 'Assessment Saved',
                    'admitted_patient'    => 'Patient Admitted',
                    'discharged_patient'  => 'Patient Discharged',
                    'created_user'        => 'User Created',
                    'updated_user'        => 'User Updated',
                    'deleted_user'        => 'User Deleted',
                    default               => ucwords(str_replace('_', ' ', $log->action))
                } }}
            </p>
            <p style="font-size:.72rem;color:#9ca3af;">
                {{ ucfirst($log->panel ?? '—') }} panel
                @if($log->subject_type)
                    · {{ $log->subject_type }} #{{ $log->subject_id }}
                @endif
            </p>
        </div>
    </div>

    @if($log->subject_label)
    <div style="padding:8px 14px;background:#eff6ff;border-radius:6px;border:1px solid #bfdbfe;
                margin-bottom:14px;font-weight:600;color:#1d4ed8;"
         class="dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300">
        📋 {{ $log->subject_label }}
    </div>
    @endif

    {{-- ── New only (create / login / vitals / etc.) ────────────────────────── --}}
    @if(!empty($new) && empty($old))
    <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
              color:#374151;margin-bottom:8px;" class="dark:text-gray-300">Data</p>
    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
        @foreach($new as $key => $val)
        <tr style="border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
            <td style="padding:5px 10px;font-weight:600;color:#6b7280;width:35%;vertical-align:top;"
                class="dark:text-gray-400">
                {{ ucwords(str_replace('_', ' ', $key)) }}
            </td>
            <td style="padding:5px 10px;color:#111827;" class="dark:text-gray-100">
                @if(is_array($val))
                    <code style="font-size:.78rem;background:#f3f4f6;padding:1px 6px;border-radius:4px;"
                          class="dark:bg-gray-700">{{ json_encode($val, JSON_PRETTY_PRINT) }}</code>
                @elseif($val === null || $val === '')
                    <span style="color:#d1d5db;">—</span>
                @else
                    {{ $val }}
                @endif
            </td>
        </tr>
        @endforeach
    </table>

    {{-- ── Both old + new (updates / re-assessments) ────────────────────────── --}}
    @elseif(!empty($old) && !empty($new))
    <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
              color:#374151;margin-bottom:8px;" class="dark:text-gray-300">
        Changes (Before → After)
    </p>
    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
        <thead>
            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;"
                class="dark:bg-gray-800 dark:border-gray-700">
                <th style="padding:6px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;
                           letter-spacing:.04em;color:#9ca3af;width:30%;">Field</th>
                <th style="padding:6px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;
                           letter-spacing:.04em;color:#dc2626;width:35%;">Before</th>
                <th style="padding:6px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;
                           letter-spacing:.04em;color:#16a34a;width:35%;">After</th>
            </tr>
        </thead>
        <tbody>
        @foreach($allKeys as $key)
        @php
            $before  = $old[$key] ?? null;
            $after   = $new[$key] ?? null;
            $changed = $before !== $after;
        @endphp
        <tr style="border-bottom:1px solid #f3f4f6;{{ $changed ? 'background:#fffbeb;' : '' }}"
            class="{{ $changed ? 'dark:bg-yellow-900/10' : '' }} dark:border-gray-700">
            <td style="padding:5px 10px;font-weight:{{ $changed ? '700' : '400' }};
                       color:#6b7280;vertical-align:top;" class="dark:text-gray-400">
                {{ ucwords(str_replace('_', ' ', $key)) }}
                @if($changed)<span style="color:#d97706;margin-left:3px;">●</span>@endif
            </td>
            <td style="padding:5px 10px;color:{{ $before ? '#374151' : '#d1d5db' }};
                       vertical-align:top;" class="dark:text-gray-300">
                @if(is_array($before))
                    <code style="font-size:.76rem;">{{ json_encode($before) }}</code>
                @else
                    {{ $before ?? '—' }}
                @endif
            </td>
            <td style="padding:5px 10px;
                       font-weight:{{ $changed ? '600' : '400' }};
                       color:{{ ($after && $changed) ? '#065f46' : ($after ? '#374151' : '#d1d5db') }};
                       vertical-align:top;"
                class="{{ ($after && $changed) ? 'dark:text-green-400' : 'dark:text-gray-300' }}">
                @if(is_array($after))
                    <code style="font-size:.76rem;">{{ json_encode($after) }}</code>
                @else
                    {{ $after ?? '—' }}
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    @else
    <p style="color:#9ca3af;font-style:italic;">No additional data recorded for this event.</p>
    @endif

    {{-- ── User agent ────────────────────────────────────────────────────────── --}}
    @if($log->user_agent)
    <details style="margin-top:14px;">
        <summary style="font-size:.72rem;color:#9ca3af;cursor:pointer;">
            Browser / User Agent
        </summary>
        <p style="font-size:.72rem;color:#6b7280;margin-top:4px;word-break:break-all;
                  padding:6px;background:#f9fafb;border-radius:4px;"
           class="dark:bg-gray-800 dark:text-gray-400">
            {{ $log->user_agent }}
        </p>
    </details>
    @endif

</div>