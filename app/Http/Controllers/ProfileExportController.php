<?php

namespace App\Http\Controllers;

use App\Models\User;
use Mpdf\Mpdf;

class ProfileExportController extends Controller
{
    /**
     * Get mPDF instance with Bangla font support
     */
    private function getMpdfInstance(): Mpdf
    {
        $fontDirs = (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
            'default_font' => 'kalpurush',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 20,
            'margin_footer' => 5,
            'tempDir' => storage_path('app/mpdf'),
            'fontDir' => array_merge($fontDirs, [
                storage_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'kalpurush' => [
                    'R' => 'Kalpurush-Regular.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
        ]);

        // Set footer for every page
        $generatedDate = now()->format('d M, Y h:i A');
        $mpdf->SetHTMLFooter('
            <div style="text-align: center; font-size: 9pt; color: #666; border-top: 1px solid #ddd; padding-top: 5px;">
                <span style="color: #E33183; font-weight: bold;">Engineer\'s Matrimony</span> | Page {PAGENO} of {nbpg}<br>
                <span style="font-size: 8pt; color: #999;">Generated on: ' . $generatedDate . '</span>
            </div>
        ');

        return $mpdf;
    }

    /**
     * Export user profile as PDF
     *
     * @param int $profileId
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($profileId)
    {
        // Get the user with all profile relationships
        $user = User::with([
            'basicInfo',
            'location',
            'education',
            'physical_attr',
            'hobby',
            'language',
            'personal',
            'spiritualSocial',
            'lifestyle',
            'partnerExpectation',
            'family',
            'parmanent',
            'siblingInfo'
        ])->where('is_admin', false)->findOrFail($profileId);

        // Check if authenticated user can export this profile
        $authUser = auth()->user();
        $isOwner = $authUser->id === $user->id;
        $isConnected = $authUser->isConnected($user->id);

        // Only allow export if:
        // 1. User is exporting their own profile, OR
        // 2. User is connected to the profile owner
        if (!$isOwner && !$isConnected) {
            return redirect()->back()->with('error', 'You must be connected to export this profile.');
        }

        // Prepare data for PDF
        $data = [
            'user' => $user,
            'isOwner' => $isOwner,
            'isConnected' => $isConnected,
        ];

        // Create temp directory if not exists
        if (!is_dir(storage_path('app/mpdf'))) {
            mkdir(storage_path('app/mpdf'), 0755, true);
        }

        // Get mPDF instance
        $mpdf = $this->getMpdfInstance();

        // Render the view to HTML
        $html = view('pdf.profile', $data)->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Generate filename
        $filename = 'profile_' . $user->id . '_' . now()->format('Ymd_His') . '.pdf';

        // Return PDF download
        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Preview profile PDF in browser
     *
     * @param int $profileId
     * @return \Illuminate\Http\Response
     */
    public function previewPdf($profileId)
    {
        // Get the user with all profile relationships
        $user = User::with([
            'basicInfo',
            'location',
            'education',
            'physical_attr',
            'hobby',
            'language',
            'personal',
            'spiritualSocial',
            'lifestyle',
            'partnerExpectation',
            'family',
            'parmanent',
            'siblingInfo'
        ])->where('is_admin', false)->findOrFail($profileId);

        // Check if authenticated user can export this profile
        $authUser = auth()->user();
        $isOwner = $authUser->id === $user->id;
        $isConnected = $authUser->isConnected($user->id);

        // Only allow preview if:
        // 1. User is previewing their own profile, OR
        // 2. User is connected to the profile owner
        if (!$isOwner && !$isConnected) {
            return redirect()->back()->with('error', 'You must be connected to view this profile PDF.');
        }

        // Prepare data for PDF
        $data = [
            'user' => $user,
            'isOwner' => $isOwner,
            'isConnected' => $isConnected,
        ];

        // Create temp directory if not exists
        if (!is_dir(storage_path('app/mpdf'))) {
            mkdir(storage_path('app/mpdf'), 0755, true);
        }

        // Get mPDF instance
        $mpdf = $this->getMpdfInstance();

        // Render the view to HTML
        $html = view('pdf.profile', $data)->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Generate filename
        $filename = 'profile_' . $user->id . '.pdf';

        // Return PDF inline (view in browser)
        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
