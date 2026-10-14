<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class UserIdCardController extends Controller
{
    /**
     * Member ID card page with Download (PDF) and Print buttons.
     */
    public function show()
    {
        return view('user.idcard.show', $this->cardData());
    }

    /**
     * The same card as an A6 PDF download.
     */
    public function download()
    {
        $data = $this->cardData();

        $pdf = Pdf::loadView('user.idcard.pdf', $data)->setPaper('a6', 'portrait');

        $fileName = 'ID-Card-' . preg_replace('/[^A-Za-z0-9_-]/', '', $data['user']->username) . '.pdf';

        return $pdf->download($fileName);
    }

    private function cardData(): array
    {
        $user = Auth::user();

        // Address comes from Edit Profile; mobile and state fall back to KYC when blank.
        $kyc = $user->kycDetail;

        $details = [
            'Email'   => $user->email,
            'Mobile'  => $user->mobile ?: ($kyc->mobile ?? null),
            'Address' => $user->address,
            'City'    => $user->city,
            'State'   => $user->state ?: ($kyc->state ?? null),
            'Pincode' => $user->pin_code,
        ];

        $photo = $this->imageDataUri($this->safePath(storage_path('app/public'), $user->profile_image), 300, 375, true)
            ?? $this->imageDataUri($this->safePath(public_path('storage'), $user->profile_image), 300, 375, true)
            ?? $this->imageDataUri(public_path('assets/img/avatars/3.png'), 300, 375, true);

        return [
            'user'        => $user,
            'details'     => $details,
            'companyName' => company_info('company_name', config('app.name')),
            'logoSrc'     => $this->imageDataUri($this->safePath(public_path('uploads'), company_info('logo')), 240, 240),
            'photoSrc'    => $photo,
        ];
    }

    /**
     * Resolve a stored relative path, refusing anything that escapes $base.
     */
    private function safePath(string $base, ?string $relative): ?string
    {
        if (!$relative) {
            return null;
        }

        $root = realpath($base);
        $path = realpath($base . DIRECTORY_SEPARATOR . $relative);

        if (!$root || !$path || !str_starts_with($path, $root . DIRECTORY_SEPARATOR) || !is_file($path)) {
            return null;
        }

        return $path;
    }

    /**
     * Inline an image as a data URI so the card renders identically on the
     * page, in the print frame and inside dompdf (which cannot read storage/
     * through its chroot). Uploads are scaled down first - a raw phone photo
     * would otherwise push the PDF into megabytes.
     *
     * $crop centre-crops to the exact box, since dompdf has no object-fit.
     */
    private function imageDataUri(?string $path, int $width, int $height, bool $crop = false): ?string
    {
        if (!$path || !is_file($path) || !($info = @getimagesize($path))) {
            return null;
        }

        $mime = $info['mime'];
        $raw = file_get_contents($path);

        if (!function_exists('imagecreatefromstring') || !($src = @imagecreatefromstring($raw))) {
            return 'data:' . $mime . ';base64,' . base64_encode($raw);
        }

        // GD drops EXIF, so apply the camera rotation before resampling.
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $orientation = (int) (@exif_read_data($path)['Orientation'] ?? 1);
            $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;
            if ($angle) {
                $src = imagerotate($src, $angle, 0);
            }
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        if ($crop) {
            $scale = max($width / $srcW, $height / $srcH);
            $cropW = (int) round($width / $scale);
            $cropH = (int) round($height / $scale);
            $srcX = (int) (($srcW - $cropW) / 2);
            $srcY = (int) (($srcH - $cropH) / 2);
            $dstW = $width;
            $dstH = $height;
        } else {
            $scale = min(1, $width / $srcW, $height / $srcH);
            $cropW = $srcW;
            $cropH = $srcH;
            $srcX = 0;
            $srcY = 0;
            $dstW = max(1, (int) round($srcW * $scale));
            $dstH = max(1, (int) round($srcH * $scale));
        }

        // Logos keep PNG transparency; photos are always JPEG on white (far smaller).
        $png = $mime === 'image/png' && !$crop;
        $dst = imagecreatetruecolor($dstW, $dstH);

        if ($png) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        } else {
            imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        }

        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $dstW, $dstH, $cropW, $cropH);

        ob_start();
        $png ? imagepng($dst, null, 9) : imagejpeg($dst, null, 85);
        $bytes = ob_get_clean();

        return 'data:image/' . ($png ? 'png' : 'jpeg') . ';base64,' . base64_encode($bytes);
    }
}
