{{--
    Member ID card, shared by the on-screen page, its print frame and the
    dompdf download, so all three look the same. Tables only - dompdf has no flexbox.

    Expects: $user, $details (label => value), $companyName, $logoSrc, $photoSrc.
    Images arrive as data URIs, so nothing here depends on /storage being reachable.
--}}
<style>
    .idc-card {
        position: relative;
        width: 105mm;
        max-width: 100%;
        margin: 0 auto;
        background: #ffffff;
        color: #1f2937;
        font-family: Helvetica, Arial, sans-serif;
        border: 1px solid #d6dbe6;
        border-radius: 3mm;
        overflow: hidden;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .idc-card table { width: 100%; border-collapse: collapse; }
    .idc-card td { padding: 0; vertical-align: middle; }

    .idc-head { background: #0d2c6c; color: #ffffff; }
    .idc-head td { padding: 3.5mm 4mm; }
    .idc-head td.idc-logo-cell { width: 14mm; padding-right: 0; }
    .idc-logo { width: 14mm; height: 14mm; background: #ffffff; border-radius: 2mm; display: block; }
    .idc-company { font-size: 10pt; font-weight: bold; text-transform: uppercase; line-height: 1.25; }
    .idc-title { font-size: 6.5pt; letter-spacing: 1.5pt; color: #ffd08a; margin-top: 1mm; }
    .idc-strip { height: 1.5mm; background: #f7931e; }

    .idc-profile td { padding: 4mm 4mm 3mm; }
    .idc-profile td.idc-photo-cell { width: 25mm; padding-right: 0; }
    .idc-photo { width: 25mm; height: 31.25mm; border: 0.6mm solid #0d2c6c; border-radius: 1.5mm; display: block; }
    .idc-name { font-size: 12pt; font-weight: bold; color: #0d2c6c; text-transform: uppercase; line-height: 1.2; }
    .idc-id-label { font-size: 6.5pt; color: #6b7280; letter-spacing: 1pt; text-transform: uppercase; margin-top: 2.5mm; }
    .idc-id { display: inline-block; margin-top: 1mm; padding: 1mm 2.5mm; background: #fff4e5; color: #b45309; border: 0.3mm solid #f7931e; border-radius: 1.5mm; font-size: 10pt; font-weight: bold; letter-spacing: 0.5pt; }

    .idc-body { padding: 0 4mm 4mm; }
    .idc-details td { padding: 1.6mm 0; border-bottom: 0.2mm solid #e5e7eb; font-size: 8pt; vertical-align: top; }
    .idc-details tr.idc-last td { border-bottom: 0; }
    .idc-details td.idc-label { width: 17mm; color: #6b7280; }
    .idc-details td.idc-colon { width: 3mm; color: #9ca3af; }
    .idc-value { font-weight: bold; color: #111827; word-wrap: break-word; overflow-wrap: anywhere; }
    .idc-empty { color: #9ca3af; font-weight: normal; }

    .idc-foot { background: #0d2c6c; color: #ffffff; text-align: center; font-size: 6.5pt; padding: 2.2mm 4mm; line-height: 1.35; }

    /* PDF: the card is the whole A6 page, footer pinned to the bottom. */
    .idc-pdf .idc-card { width: 105mm; height: 147.5mm; border: 0; border-radius: 0; }
    .idc-pdf .idc-foot { position: absolute; left: 0; right: 0; bottom: 0; }
    .idc-pdf .idc-photo { width: 28mm; height: 35mm; }
    .idc-pdf .idc-profile td.idc-photo-cell { width: 28mm; }
    .idc-pdf .idc-name { font-size: 14pt; }
    .idc-pdf .idc-details td { padding: 2.7mm 0; font-size: 9pt; }
    .idc-pdf .idc-details td.idc-label { width: 19mm; }
</style>

<div class="idc-card">
    <table class="idc-head">
        <tr>
            @if ($logoSrc)
                <td class="idc-logo-cell"><img src="{{ $logoSrc }}" alt="Logo" class="idc-logo"></td>
            @endif
            <td>
                <div class="idc-company">{{ $companyName }}</div>
                <div class="idc-title">MEMBER IDENTITY CARD</div>
            </td>
        </tr>
    </table>
    <div class="idc-strip"></div>

    <table class="idc-profile">
        <tr>
            <td class="idc-photo-cell">
                @if ($photoSrc)
                    <img src="{{ $photoSrc }}" alt="Photo" class="idc-photo">
                @endif
            </td>
            <td>
                <div class="idc-name">{{ $user->name ?: $user->username }}</div>
                <div class="idc-id-label">ID No.</div>
                <div class="idc-id">{{ $user->username }}</div>
            </td>
        </tr>
    </table>

    <div class="idc-body">
        <table class="idc-details">
            @foreach ($details as $label => $value)
                <tr class="{{ $loop->last ? 'idc-last' : '' }}">
                    <td class="idc-label">{{ $label }}</td>
                    <td class="idc-colon">:</td>
                    @if ($value === null || $value === '')
                        <td class="idc-value idc-empty">—</td>
                    @else
                        <td class="idc-value">{{ $value }}</td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>

    <div class="idc-foot">
        This card is the property of {{ $companyName }}.<br>
        If found, please return it to the company.
    </div>
</div>
