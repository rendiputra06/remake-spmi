<?php

namespace App\Exports;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;
use App\Models\SurveyAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SurveyExport implements FromCollection, WithHeadings, WithStyles
{
    protected $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $responses = SurveyResponse::where('survey_id', $this->survey->id)
            ->where('is_completed', true)
            ->get();

        $questions = SurveyQuestion::where('survey_id', $this->survey->id)
            ->orderBy('order')
            ->get();

        $data = [];

        foreach ($responses as $response) {
            $row = [
                'ID Respons' => $response->id,
                'Tanggal Diisi' => $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i') : '-',
                'Jenis Responden' => $response->respondent_type ?? '-',
            ];

            // Tambahkan jawaban dari setiap pertanyaan
            foreach ($questions as $question) {
                $answer = SurveyAnswer::where('survey_response_id', $response->id)
                    ->where('survey_question_id', $question->id)
                    ->first();

                $answerText = '-';
                if ($answer) {
                    if ($question->type === 'checkbox' && $answer->answer) {
                        $selectedOptions = json_decode($answer->answer, true);
                        $answerText = implode(', ', $selectedOptions);
                    } else {
                        $answerText = $answer->answer;
                    }
                }

                $row[$question->question] = $answerText;
            }

            $data[] = $row;
        }

        return new Collection($data);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $questions = SurveyQuestion::where('survey_id', $this->survey->id)
            ->orderBy('order')
            ->get();

        $headings = [
            'ID Respons',
            'Tanggal Diisi',
            'Jenis Responden',
        ];

        foreach ($questions as $question) {
            $headings[] = $question->question;
        }

        return $headings;
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
