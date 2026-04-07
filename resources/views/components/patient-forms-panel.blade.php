{{--
    Shared Patient Forms Panel — rendered by PatientFormsPanel component.
    Used by Clerk (view-visit), Nurse (nurse-chart forms tab), Doctor (patient-chart profile tab).
    To add a new form, edit PatientFormsPanel::buildForms() only.
--}}

<style>
.pfp-section { margin-bottom:32px; }
.pfp-header { display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap; }
.pfp-icon { font-size:1rem;flex-shrink:0; }
.pfp-label { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap; }
.pfp-line { flex:1;border-top:1px solid #e5e7eb;min-width:20px; }
.dark .pfp-line { border-top-color:#374151; }
.pfp-badge { font-size:.65rem;font-weight:700;padding:2px 10px;border-radius:9999px;white-space:nowrap;flex-shrink:0; }
.pfp-print-btn {
    display:inline-flex;align-items:center;gap:4px;
    font-size:.72rem;font-weight:700;text-decoration:none;
    padding:3px 10px;border-radius:5px;white-space:nowrap;flex-shrink:0;
    background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;
    transition:background .12s;
}
.pfp-print-btn:hover { background:#dbeafe; }
.pfp-iframe-wrap {
    border:1px solid #e5e7eb;border-radius:8px;
    overflow:hidden;background:#fff;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.dark .pfp-iframe-wrap { border-color:#374151; }
.pfp-iframe-wrap iframe { display:block;width:100%;border:none; }
.pfp-placeholder {
    background:#fff;border:1.5px dashed #e5e7eb;
    border-radius:8px;padding:28px;text-align:center;
}
.dark .pfp-placeholder { background:#1f2937;border-color:#374151; }
.pfp-placeholder p { font-size:.82rem;color:#9ca3af;margin:0; }
</style>

@foreach($forms as $form)
@php
    $showIf    = is_callable($form['show_if'])    ? ($form['show_if'])()    : true;
    $showIframe= is_callable($form['show_iframe']) ? ($form['show_iframe'])() : true;
    $badge     = is_callable($form['badge'])       ? ($form['badge'])()       : ($form['badge'] ?? '');
    $badgeStyle= is_callable($form['badge_style']) ? ($form['badge_style'])() : ($form['badge_style'] ?? '');
    $url       = is_callable($form['url'])         ? ($form['url'])()         : $form['url'];
    $printUrl  = is_callable($form['print_url'])   ? ($form['print_url'])()   : ($form['print_url'] ?? $url);
    $height    = $form['height'] ?? 900;
    $placeholder = $form['placeholder'] ?? 'No data yet.';
@endphp

@if($showIf)
<div class="pfp-section" id="form-{{ $form['key'] }}">

    <div class="pfp-header">
        <span class="pfp-icon">{{ $form['icon'] }}</span>
        <span class="pfp-label">{!! $form['label'] !!}</span>
        <div class="pfp-line"></div>
        <span class="pfp-badge" style="{{ $badgeStyle }}">{{ $badge }}</span>
        <a href="{{ $printUrl }}" target="_blank" rel="noopener" class="pfp-print-btn">
            🖨️ Open / Print
        </a>
    </div>

    @if($showIframe)
    <div class="pfp-iframe-wrap">
        <iframe
            src="{{ $url }}"
            title="{{ strip_tags($form['label']) }}"
            style="width:100%;min-height:{{ $height }}px;border:none;display:block;"
            loading="lazy">
        </iframe>
    </div>
    @else
    <div class="pfp-placeholder">
        <p>{!! $placeholder !!}</p>
    </div>
    @endif

</div>
@endif
@endforeach