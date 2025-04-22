<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    /**
     * Tampilkan daftar survei yang aktif
     */
    public function index()
    {
        $surveys = Survey::where('status', '=', 'active')
            ->where('visibility', '=', 'public')
            ->latest()
            ->paginate(10);

        return view('surveys.index', compact('surveys'));
    }

    /**
     * Tampilkan form survei
     */
    public function show($id)
    {
        $survey = Survey::where('status', '=', 'active')
            ->where('visibility', '=', 'public')
            ->findOrFail($id);

        $questions = $survey->questions()->orderBy('order')->get();

        return view('surveys.show', compact('survey', 'questions'));
    }

    /**
     * Simpan jawaban survei
     */
    public function submit(Request $request, $id)
    {
        $survey = Survey::where('status', 'active')
            ->where('visibility', 'public')
            ->findOrFail($id);

        $questions = $survey->questions;

        // Validasi data berdasarkan tipe pertanyaan
        $rules = [];
        $messages = [];

        foreach ($questions as $question) {
            if ($question->is_required) {
                $rules["answers.{$question->id}"] = 'required';
                $messages["answers.{$question->id}.required"] = "Pertanyaan '{$question->question}' wajib dijawab.";
            }

            // Tambahkan validasi khusus berdasarkan tipe pertanyaan
            if ($question->type === 'number') {
                $rules["answers.{$question->id}"] .= '|numeric';
                $messages["answers.{$question->id}.numeric"] = "Jawaban harus berupa angka.";
            } elseif ($question->type === 'email') {
                $rules["answers.{$question->id}"] .= '|email';
                $messages["answers.{$question->id}.email"] = "Format email tidak valid.";
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Buat response survey
            $response = new SurveyResponse();
            $response->survey_id = $survey->id;
            $response->ip_address = $request->ip();
            $response->user_agent = $request->userAgent();
            $response->is_completed = true;
            $response->save();

            // Simpan jawaban
            foreach ($questions as $question) {
                if (isset($request->answers[$question->id])) {
                    $answer = $request->answers[$question->id];

                    // Konversi array menjadi string jika jawaban berupa multiple choice
                    if (is_array($answer)) {
                        $answer = json_encode($answer);
                    }

                    $response->answers()->create([
                        'survey_question_id' => $question->id,
                        'answer' => $answer
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('surveys.thank-you', $survey->id)
                ->with('success', 'Terima kasih atas partisipasi Anda!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan jawaban. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Tampilkan halaman terima kasih
     */
    public function thankYou($id)
    {
        $survey = Survey::findOrFail($id);

        return view('surveys.thank-you', compact('survey'));
    }
}
