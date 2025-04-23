<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SurveyAnalyticsController extends Controller
{
    /**
     * Menampilkan halaman analisis untuk survei tertentu
     */
    public function show($id)
    {
        $survey = Survey::findOrFail($id);
        $questions = $survey->questions()->orderBy('order')->get();
        $responses = $survey->responses()->where('is_completed', true)->get();

        // Hitung statistik dasar
        $totalResponses = $responses->count();
        $completionRate = $survey->responses()->count() > 0
            ? ($totalResponses / $survey->responses()->count()) * 100
            : 0;

        // Analisis jawaban per pertanyaan
        $questionStats = [];

        foreach ($questions as $question) {
            $stats = $this->analyzeQuestion($question, $responses);
            $questionStats[$question->id] = $stats;
        }

        return view('surveys.analytics', compact(
            'survey',
            'questions',
            'totalResponses',
            'completionRate',
            'questionStats'
        ));
    }

    /**
     * Menganalisis jawaban untuk satu pertanyaan
     */
    private function analyzeQuestion(SurveyQuestion $question, $responses)
    {
        $stats = [
            'question' => $question,
            'total_answers' => 0,
            'data' => [],
        ];

        // Dapatkan semua jawaban untuk pertanyaan ini
        $answers = SurveyAnswer::whereIn('survey_response_id', $responses->pluck('id'))
            ->where('survey_question_id', $question->id)
            ->get();

        $stats['total_answers'] = $answers->count();

        // Analisis berdasarkan tipe pertanyaan
        switch ($question->type) {
            case 'scale':
                $stats['type'] = 'bar';
                $stats['labels'] = range($question->min_value, $question->max_value);
                $stats['data'] = $this->analyzeScaleAnswers($answers, $question);
                $stats['average'] = $answers->avg('answer') ?? 0;
                break;

            case 'multiple_choice':
            case 'dropdown':
                $stats['type'] = 'pie';
                $options = json_decode($question->options, true);
                $stats['labels'] = $options;
                $stats['data'] = $this->analyzeChoiceAnswers($answers, $options);
                break;

            case 'checkbox':
                $stats['type'] = 'horizontalBar';
                $options = json_decode($question->options, true);
                $stats['labels'] = $options;
                $stats['data'] = $this->analyzeCheckboxAnswers($answers, $options);
                break;

            case 'text':
            case 'textarea':
                $stats['type'] = 'text';
                $stats['answers'] = $answers->map(function ($answer) {
                    // Jika jawaban adalah type array, decode JSON
                    if ($answer->answer_type === 'array') {
                        $decoded = json_decode($answer->answer, true);
                        return is_array($decoded) ? implode(', ', $decoded) : $answer->answer;
                    }
                    return $answer->answer;
                })->toArray();
                break;

            default:
                $stats['type'] = 'unsupported';
                break;
        }

        return $stats;
    }

    /**
     * Menganalisis jawaban skala
     */
    private function analyzeScaleAnswers($answers, $question)
    {
        $counts = array_fill(0, $question->max_value - $question->min_value + 1, 0);

        foreach ($answers as $answer) {
            // Konversi ke integer jika perlu
            $value = $answer->answer_type === 'integer' ?
                (int) $answer->answer :
                (int) $answer->answer;

            if ($value >= $question->min_value && $value <= $question->max_value) {
                $counts[$value - $question->min_value]++;
            }
        }

        return $counts;
    }

    /**
     * Menganalisis jawaban pilihan tunggal
     */
    private function analyzeChoiceAnswers($answers, $options)
    {
        $counts = array_fill(0, count($options), 0);

        foreach ($answers as $answer) {
            // Gunakan typed_answer jika answer_type adalah 'array'
            $answerValue = $answer->answer_type === 'array' ?
                json_decode($answer->answer, true) :
                $answer->answer;

            if (is_array($answerValue)) {
                // Jika jawaban adalah array, hitung setiap opsi yang dipilih
                foreach ($answerValue as $selected) {
                    $optionIndex = array_search($selected, $options);
                    if ($optionIndex !== false) {
                        $counts[$optionIndex]++;
                    }
                }
            } else {
                // Jika jawaban adalah string tunggal
                $optionIndex = array_search($answerValue, $options);
                if ($optionIndex !== false) {
                    $counts[$optionIndex]++;
                }
            }
        }

        return $counts;
    }

    /**
     * Menganalisis jawaban pilihan ganda
     */
    private function analyzeCheckboxAnswers($answers, $options)
    {
        $counts = array_fill(0, count($options), 0);

        foreach ($answers as $answer) {
            $selectedOptions = $answer->answer_type === 'array' ?
                json_decode($answer->answer, true) :
                $answer->answer;

            if (is_array($selectedOptions)) {
                foreach ($selectedOptions as $selected) {
                    $optionIndex = array_search($selected, $options);
                    if ($optionIndex !== false) {
                        $counts[$optionIndex]++;
                    }
                }
            }
        }

        return $counts;
    }

    /**
     * Export data survei ke Excel
     */
    public function exportExcel($id)
    {
        $survey = Survey::findOrFail($id);
        $questions = $survey->questions()->orderBy('order')->get();
        $responses = $survey->responses()->where('is_completed', true)->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul dan informasi survei
        $sheet->setCellValue('A1', $survey->title);
        $sheet->setCellValue('A2', 'Deskripsi: ' . $survey->description);
        $sheet->setCellValue('A3', 'Total Responden: ' . $responses->count());
        $sheet->setCellValue('A4', 'Periode: ' . $survey->start_date->format('d/m/Y') . ' - ' .
            ($survey->end_date ? $survey->end_date->format('d/m/Y') : 'Sekarang'));

        // Header untuk data respons
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'Waktu Pengisian');

        // Tambahkan header untuk setiap pertanyaan
        $col = 'C';
        foreach ($questions as $question) {
            $sheet->setCellValue($col . '6', $question->question);
            $col++;
        }

        // Isi data respons
        $row = 7;
        foreach ($responses as $index => $response) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i') : '-');

            // Isi jawaban untuk setiap pertanyaan
            $col = 'C';
            foreach ($questions as $question) {
                $answer = $response->answers()->where('survey_question_id', $question->id)->first();

                if ($answer) {
                    $answerValue = $answer->answer;

                    // Format jawaban sesuai tipe jawaban
                    if ($answer->answer_type === 'array' && $answerValue) {
                        $options = json_decode($answerValue, true);
                        $answerValue = is_array($options) ? implode(', ', $options) : $answerValue;
                    } else if ($answer->answer_type === 'integer' || $answer->answer_type === 'number') {
                        // Pastikan angka ditampilkan dengan benar
                        $answerValue = (int) $answerValue;
                    }

                    $sheet->setCellValue($col . $row, $answerValue);
                } else {
                    $sheet->setCellValue($col . $row, '-');
                }

                $col++;
            }

            $row++;
        }

        // Auto-size kolom
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Buat file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'hasil_survei_' . $survey->id . '_' . date('Ymd_His') . '.xlsx';
        $path = 'exports/' . $filename;

        // Simpan file
        $tempFile = tempnam(sys_get_temp_dir(), 'survey_export');
        $writer->save($tempFile);

        // Return file untuk didownload
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Generate dan download laporan PDF
     */
    public function exportPdf($id)
    {
        $survey = Survey::findOrFail($id);
        $questions = $survey->questions()->orderBy('order')->get();
        $responses = $survey->responses()->where('is_completed', true)->get();

        // Hitung statistik dasar
        $totalResponses = $responses->count();
        $completionRate = $survey->responses()->count() > 0
            ? ($totalResponses / $survey->responses()->count()) * 100
            : 0;

        // Analisis jawaban per pertanyaan
        $questionStats = [];

        foreach ($questions as $question) {
            $stats = $this->analyzeQuestion($question, $responses);
            $questionStats[$question->id] = $stats;
        }

        // Generate PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('surveys.report-pdf', compact(
            'survey',
            'questions',
            'totalResponses',
            'completionRate',
            'questionStats'
        ));

        return $pdf->download('laporan_survei_' . $survey->id . '_' . date('Ymd_His') . '.pdf');
    }
}
