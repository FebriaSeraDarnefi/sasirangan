<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductQrCodeController extends Controller
{
    /**
     * Menampilkan gambar QR Code secara publik.
     */
    public function image(Product $product): Response
    {
        $product->loadMissing('umkm');

        abort_unless(
            $product->status === 'active'
            && $product->umkm
            && $product->umkm->verification_status === 'active',
            404
        );

        $result = $this->generateQrCode($product);

        return response(
            $result->getString(),
            200,
            [
                'Content-Type' => $result->getMimeType(),
                'Content-Disposition' => 'inline; filename="qr-'.$product->slug.'.svg"',
                'Cache-Control' => 'public, max-age=86400',
            ]
        );
    }

    /**
     * Halaman cetak QR Code milik UMKM.
     */
    public function show(Product $product): View
    {
        Gate::authorize('view', $product);

        $product->loadMissing('umkm');

        $productUrl = $this->productUrl($product);

        return view('umkm.products.qr', compact(
            'product',
            'productUrl'
        ));
    }

    /**
     * Mengunduh QR Code dalam format SVG.
     */
    public function download(Product $product): StreamedResponse
    {
        Gate::authorize('view', $product);

        $result = $this->generateQrCode($product);

        $filename = Str::slug($product->name)
            .'-'
            .$product->upc
            .'-qr.svg';

        return response()->streamDownload(
            function () use ($result): void {
                echo $result->getString();
            },
            $filename,
            [
                'Content-Type' => $result->getMimeType(),
            ]
        );
    }

    /**
     * Membuat QR Code dari URL detail produk.
     */
    private function generateQrCode(Product $product)
    {
        $writer = new SvgWriter;

        $qrCode = new QrCode(
            data: $this->productUrl($product),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 360,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(15, 23, 42),
            backgroundColor: new Color(255, 255, 255)
        );

        return $writer->write($qrCode);
    }

    /**
     * URL yang dibuka saat QR Code dipindai.
     */
    private function productUrl(Product $product): string
    {
        return route('store.product.show', [
            'product' => $product->slug,
        ]);
    }
}
