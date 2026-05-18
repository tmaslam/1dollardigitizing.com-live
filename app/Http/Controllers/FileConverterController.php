<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FileConverterController extends Controller
{
    protected function estimatorUrl(): string
    {
        return rtrim(env('ESTIMATOR_API_URL', 'http://localhost:8001'), '/');
    }

    public function showForm()
    {
        return view('tools.file-converter');
    }

    public function convert(Request $request)
    {
        $validated = $request->validate([
            'file'          => 'required|file|max:10240',
            'target_format' => 'required|string|in:dst,pes,jef,exp,vp3,xxx,emb,vip,hus,svg,csv',
        ]);

        try {
            $uploadedFile = $request->file('file');
            $targetFormat = $validated['target_format'];

            $response = Http::timeout(60)
                ->attach(
                    'file',
                    file_get_contents($uploadedFile->getRealPath()),
                    $uploadedFile->getClientOriginalName()
                )
                ->post($this->estimatorUrl() . '/convert', [
                    'target_format' => $targetFormat,
                ]);

            if (! $response->successful()) {
                $errorMsg = 'Conversion failed.';
                try {
                    $errorData = $response->json();
                    $errorMsg = $errorData['detail'] ?? $errorData['message'] ?? $errorMsg;
                } catch (\Exception $e) {}

                Log::warning('FastAPI conversion failed', [
                    'status'  => $response->status(),
                    'message' => $errorMsg,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], $response->status());
            }

            $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFileName  = $originalName . '.' . $targetFormat;
            $body         = $response->body();

            return response($body)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $newFileName . '"')
                ->header('Content-Length', strlen($body));

        } catch (\Exception $e) {
            Log::error('File conversion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Conversion failed. Please try again or contact support.',
            ], 500);
        }
    }
}
