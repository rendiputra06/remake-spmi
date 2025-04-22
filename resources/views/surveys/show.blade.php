<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $survey->title }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">{{ $survey->title }}</h1>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <div class="mb-4">
                            <p>{{ $survey->description }}</p>
                            @if($survey->end_date)
                            <div class="alert alert-info">
                                Survei ini berakhir pada: {{ \Carbon\Carbon::parse($survey->end_date)->format('d M Y') }}
                            </div>
                            @endif
                        </div>

                        <form action="{{ route('surveys.submit', $survey->id) }}" method="POST">
                            @csrf

                            @if($questions->isEmpty())
                            <div class="alert alert-warning">
                                Belum ada pertanyaan untuk survei ini.
                            </div>
                            @else
                            @foreach($questions as $question)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>{{ $loop->iteration }}. {{ $question->question }}</strong>
                                    @if($question->is_required)
                                    <span class="text-danger">*</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($question->description)
                                    <p class="text-muted mb-3">{{ $question->description }}</p>
                                    @endif

                                    @if($errors->has("answers.{$question->id}"))
                                    <div class="alert alert-danger">
                                        {{ $errors->first("answers.{$question->id}") }}
                                    </div>
                                    @endif

                                    @switch($question->type)
                                    @case('text')
                                    <input type="text" name="answers[{{ $question->id }}]" value="{{ old("answers.{$question->id}") }}" class="form-control" @if($question->is_required) required @endif>
                                    @break

                                    @case('textarea')
                                    <textarea name="answers[{{ $question->id }}]" rows="3" class="form-control" @if($question->is_required) required @endif>{{ old("answers.{$question->id}") }}</textarea>
                                    @break

                                    @case('number')
                                    <input type="number" name="answers[{{ $question->id }}]" value="{{ old("answers.{$question->id}") }}" class="form-control" @if($question->is_required) required @endif>
                                    @break

                                    @case('email')
                                    <input type="email" name="answers[{{ $question->id }}]" value="{{ old("answers.{$question->id}") }}" class="form-control" @if($question->is_required) required @endif>
                                    @break

                                    @case('date')
                                    <input type="date" name="answers[{{ $question->id }}]" value="{{ old("answers.{$question->id}") }}" class="form-control" @if($question->is_required) required @endif>
                                    @break

                                    @case('select')
                                    <select name="answers[{{ $question->id }}]" class="form-control" @if($question->is_required) required @endif>
                                        <option value="">-- Pilih Jawaban --</option>
                                        @foreach(json_decode($question->options) as $option)
                                        <option value="{{ $option }}" {{ old("answers.{$question->id}") == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @break

                                    @case('radio')
                                    @foreach(json_decode($question->options) as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option{{ $question->id }}_{{ $loop->index }}" value="{{ $option }}" {{ old("answers.{$question->id}") == $option ? 'checked' : '' }} @if($question->is_required) required @endif>
                                        <label class="form-check-label" for="option{{ $question->id }}_{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                    @break

                                    @case('checkbox')
                                    @foreach(json_decode($question->options) as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" id="option{{ $question->id }}_{{ $loop->index }}" value="{{ $option }}" {{ is_array(old("answers.{$question->id}")) && in_array($option, old("answers.{$question->id}")) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="option{{ $question->id }}_{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                    @break

                                    @case('rating')
                                    <div class="btn-group" role="group">
                                        @php
                                        $max = $question->options ? intval($question->options) : 5;
                                        @endphp
                                        @for($i = 1; $i <= $max; $i++)
                                            <input type="radio" class="btn-check" name="answers[{{ $question->id }}]" id="rating{{ $question->id }}_{{ $i }}" value="{{ $i }}" {{ old("answers.{$question->id}") == $i ? 'checked' : '' }} @if($question->is_required) required @endif>
                                            <label class="btn btn-outline-primary" for="rating{{ $question->id }}_{{ $i }}">{{ $i }}</label>
                                            @endfor
                                    </div>
                                    @break

                                    @default
                                    <input type="text" name="answers[{{ $question->id }}]" value="{{ old("answers.{$question->id}") }}" class="form-control" @if($question->is_required) required @endif>
                                    @endswitch
                                </div>
                            </div>
                            @endforeach

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                                <a href="{{ route('surveys.index') }}" class="btn btn-secondary">Kembali ke Daftar Survei</a>
                            </div>
                            @endif
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="small mb-0">
                            &copy; {{ date('Y') }} Sistem Penjaminan Mutu Internal (SPMI)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>