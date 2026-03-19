{{--
    Reusable placeholder partial for nurse chart sections.
    Usage:
      @include('filament.nurse.pages.partials.placeholder', [
          'icon'  => '💊',
          'title' => 'Medication Administration Record (MAR)',
          'desc'  => 'Description text...',
          'full'  => true,   // optional: spans full width if in a grid
      ])
--}}
<div class="sec-head">
    <h2 class="sec-title">{{ $title }}</h2>
</div>

<div style="{{ ($full ?? false) ? '' : '' }} background:#fff; border:1.5px dashed #e5e7eb; border-radius:12px; padding:56px 40px; text-align:center; max-width:520px; margin:0 auto;">

    <div class="dark:.bg-gray-800" style="font-size:3rem; margin-bottom:14px; line-height:1;">
        {{ $icon }}
    </div>

    <h3 style="font-size:1rem; font-weight:700; color:#374151; margin-bottom:8px;">
        {{ $title }}
    </h3>

    <p style="font-size:.85rem; color:#6b7280; line-height:1.7; margin-bottom:20px; max-width:380px; margin-left:auto; margin-right:auto;">
        {{ $desc }}
    </p>

    <div style="display:inline-flex; align-items:center; gap:8px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:8px 18px; margin-bottom:18px;">
        <span style="font-size:.8rem;">🚧</span>
        <span style="font-size:.8rem; font-weight:700; color:#dc2626;">Under Development</span>
    </div>

    <br>

    <button disabled
            style="background:#f3f4f6; color:#9ca3af; border:1px solid #e5e7eb; border-radius:7px; padding:9px 22px; font-size:.85rem; font-weight:600; cursor:not-allowed; display:inline-flex; align-items:center; gap:6px;">
        📄 View Document
    </button>

</div>