<?php

namespace App\Services;

use App\Models\Spt;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Element\Section;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function __construct()
    {
        Settings::setCompatibility(false);
        Settings::setOutputEscapingEnabled(true);
    }

    public function exportToWord(Spt $spt)
    {
        try {
            $phpWord = new PhpWord();
            
            // Document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('Sistem SPT');
            $properties->setCompany('Diskominfo Siak');
            $properties->setTitle('Surat Perintah Tugas');
            
            // Default font settings
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(12);

            // Section settings (A4 paper)
            $section = $phpWord->addSection([
                'orientation' => 'portrait',
                'marginTop' => 567,    // 1 cm
                'marginRight' => 1134, // 2 cm
                'marginBottom' => 567, // 1 cm
                'marginLeft' => 1134,  // 2 cm
                'pageSize' => 'A4',
            ]);

            // Header table for logo and text
            $table = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'white',
                'cellMargin' => 0,
                'spaceAfter' => 0
            ]);

            // Single row with two cells
            $row = $table->addRow();

            // Logo cell (left)
            $cell1 = $row->addCell(1500);
            $cell1->addImage(
                public_path('images/logo-siak.png'),
                [
                    'width' => 80,
                    'height' => 80,
                    'alignment' => Jc::START,
                    'wrappingStyle' => 'inline'
                ]
            );

            // Text cell (right)
            $cell2 = $row->addCell(8500);
            $cell2->addText(
                'PEMERINTAH KABUPATEN SIAK',
                ['bold' => true, 'size' => 16],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );
            $cell2->addText(
                'DINAS KOMUNIKASI DAN INFORMATIKA',
                ['bold' => true, 'size' => 16],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );
            $cell2->addText(
                'Komplek Perkantoran Tanjung Agung',
                ['size' => 11],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );
            $cell2->addText(
                'Kecamatan Mempura Kabupaten Siak Provinsi Riau',
                ['size' => 11],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );
            $cell2->addText(
                'email : kominfo@siakkab.go.id',
                ['size' => 11],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            // Garis pemisah ganda
            $section->addText(
                str_repeat('_', 65),
                ['bold' => true],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            // Add minimal space after the line
            $section->addTextBreak(1);

            // Judul Surat
            $section->addText(
                'SURAT PERINTAH TUGAS',
                ['bold' => true, 'size' => 12],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            $section->addText(
                'Nomor : ' . $spt->nomor_surat,
                ['size' => 12],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 120]
            );

            // Dasar
            $section->addText(
                'Dasar :',
                ['bold' => true],
                ['spaceAfter' => 0]
            );

            // Format dasar dengan indentasi yang minimal
            $textrun = $section->addTextRun(['indent' => 1.25, 'spaceAfter' => 120]);
            $textrun->addText('1. ', ['bold' => true]);
            $textrun->addText(strip_tags($spt->dasar));

            // Memerintahkan
            $section->addText(
                'MEMERINTAHKAN :',
                ['bold' => true],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 120]
            );

            // Kepada
            $section->addText(
                'Kepada :',
                ['bold' => true],
                ['spaceAfter' => 60]
            );

            // Data pegawai dengan spacing minimal
            foreach($spt->users as $index => $user) {
                $textrun = $section->addTextRun([
                    'spaceAfter' => 0,
                    'indent' => 1.25
                ]);
                $textrun->addText(($index + 1) . '. ', ['bold' => true]);

                // Nama
                $textrun = $section->addTextRun(['indent' => 2.0, 'spaceAfter' => 0]);
                $textrun->addText('Nama', ['spaceAfter' => 0]);
                $textrun->addText(str_repeat(' ', 4) . ': ' . $user->nama);

                // NIP
                $textrun = $section->addTextRun(['indent' => 2.0, 'spaceAfter' => 0]);
                $textrun->addText('NIP', ['spaceAfter' => 0]);
                $textrun->addText(str_repeat(' ', 5) . ': ' . $user->nip);

                // Pangkat
                $textrun = $section->addTextRun(['indent' => 2.0, 'spaceAfter' => 0]);
                $textrun->addText('Pangkat/Golongan', ['spaceAfter' => 0]);
                $textrun->addText(': ' . $user->pangkat);

                // Jabatan
                $textrun = $section->addTextRun(['indent' => 2.0, 'spaceAfter' => 0]);
                $textrun->addText('Jabatan', ['spaceAfter' => 0]);
                $textrun->addText(str_repeat(' ', 2) . ': ' . $user->jabatan);
            }

            // Untuk dengan spacing minimal
            $section->addTextBreak(1);
            $section->addText(
                'Untuk :',
                ['bold' => true],
                ['spaceAfter' => 0]
            );

            // Format tujuan dengan indentasi minimal
            $tujuanArray = explode("\n", strip_tags($spt->tujuan));
            foreach($tujuanArray as $index => $tujuan) {
                if (!empty(trim($tujuan))) {
                    $textrun = $section->addTextRun([
                        'lineHeight' => 1.0,
                        'spaceAfter' => 0,
                        'indent' => 1.25
                    ]);
                    $textrun->addText(($index + 1) . '. ', ['bold' => true]);
                    $textrun->addText(trim($tujuan));
                }
            }

            // Minimal space before signature
            $section->addTextBreak(1);

            // Tempat dan tanggal dengan spacing minimal
            $section->addText(
                'Ditetapkan di Siak Sri Indrapura',
                [],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            $section->addText(
                'Pada tanggal ' . $spt->tanggal_pengajuan->isoFormat('D MMMM Y'),
                [],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            // Jabatan penandatangan
            $section->addText(
                'KEPALA DINAS KOMUNIKASI DAN',
                [],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            $section->addText(
                'INFORMATIKA KABUPATEN SIAK',
                [],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            // Space untuk tanda tangan (minimal)
            $section->addTextBreak(1);

            // Placeholder tanda tangan
            $section->addText(
                '${ttd_pengirim}',
                [],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            // Nama dan NIP penandatangan
            $section->addText(
                'ROMY LESMANA DERMAWAN, AP, M.Si',
                ['bold' => true, 'underline' => 'single'],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            $section->addText(
                'Pembina Utama Muda (IV/c)',
                [],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
            );

            $section->addText(
                'NIP.197402021993031004',
                [],
                ['alignment' => Jc::CENTER]
            );

            // Save document
            $filename = 'SPT-' . str_replace(['/', '\\'], '-', $spt->nomor_surat) . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'spt_') . '.docx';
            
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($tempFile);
            
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            \Log::error('Error creating Word document: ' . $e->getMessage());
            if (isset($tempFile) && file_exists($tempFile)) {
                @unlink($tempFile);
            }
            throw new \Exception('Gagal membuat dokumen Word: ' . $e->getMessage());
        }
    }

    private function addFormattedText($section, $html)
    {
        $text = strip_tags($html);
        $section->addText($text, null, ['alignment' => Jc::BOTH, 'spaceAfter' => 0]);
    }

    public function exportToPdf(Spt $spt)
    {
        $pdf = \PDF::loadView('spts.print', compact('spt'));
        return $pdf->stream('SPT-' . str_replace(['/', '\\'], '-', $spt->nomor_surat) . '.pdf');
    }
}