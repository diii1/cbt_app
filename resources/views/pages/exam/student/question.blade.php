@extends('layouts.student.app')

@push('css')
    <style>
        .list-question-number{
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-5 mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row px-2">
                            <div class="col-md-6 d-flex justify-content-start">
                                <h5>No Soal : {{ $index }}</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <h5>Sisa Waktu : <span id="sessionEnd"></span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row px-2">
                            <div class="col-md-12">
                                <span>
                                    {!! $data['question']->question !!}
                                </span>
                            </div>
                            <div class="col-md-1 text-center">
                                <input class="form-check-input mr-3" type="radio" name="answer" value="{{ $data['question']->options[0]->value }}"
                                    aria-label="Radio button for following text input" {{ $data['answer']->answer == $data['question']->options[0]->value ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-11">
                                <span>{!! $data['question']->options[0]->value !!}</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <input class="form-check-input mr-3" type="radio" name="answer" value="{{ $data['question']->options[1]->value }}"
                                    aria-label="Radio button for following text input" {{ $data['answer']->answer == $data['question']->options[1]->value ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-11">
                                <span>{!! $data['question']->options[1]->value !!}</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <input class="form-check-input mr-3" type="radio" name="answer" value="{{ $data['question']->options[2]->value }}"
                                    aria-label="Radio button for following text input" {{ $data['answer']->answer == $data['question']->options[2]->value ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-11">
                                <span>{!! $data['question']->options[2]->value !!}</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <input class="form-check-input mr-3" type="radio" name="answer" value="{{ $data['question']->options[3]->value }}"
                                    aria-label="Radio button for following text input" {{ $data['answer']->answer == $data['question']->options[3]->value ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-11">
                                <span>{!! $data['question']->options[3]->value !!}</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <input class="form-check-input mr-3" type="radio" name="answer" value="{{ $data['question']->options[4]->value }}"
                                    aria-label="Radio button for following text input" {{ $data['answer']->answer == $data['question']->options[4]->value ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-11">
                                <span>{!! $data['question']->options[4]->value !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row py-2">
                            <div class="col-md-4 d-flex justify-content-start">
                                <button class="btn btn-info" data-index="{{ $index }}" id="previous"><i class="ti-arrow-left"></i>&nbsp; Sebelumnya</button>
                            </div>
                            <div class="col-md-4 d-flex justify-content-center">
                                <span class="me-2"><i class="ti-flag-alt"></i></span>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="doubtful" value="true" id="doubtfulCheckbox" {{ $data['answer']->doubtful_answer ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doubtfulCheckbox"><span>Ragu - Ragu</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex justify-content-end">
                                <button class="btn btn-info" data-index="{{ $index }}" id="next">Selanjutnya &nbsp;<i class="ti-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span class="text-center"><h5>Daftar Soal</h5></span>
                    </div>
                    <div class="card-body list-question-number p-3">
                        @foreach ($data['answers'] as $answers)
                            <div class="row d-flex justify-content-center align-item-center">
                                @foreach ($answers as $answer)
                                    @php
                                        $isLocked = false;
                                        foreach ($data['possible_step'] as $value) {
                                            if($answer->number > $value && $data['step'] != $value){
                                                $isLocked = true;
                                            }

                                            if($data['step'] > $answer->number){
                                                $isLocked = true;
                                            }
                                        }
                                    @endphp
                                    <div class="col-md-3">
                                        <a style="text-decoration: none" href="{{ route('api.exam.get_question', [$data['exam']->code, $answer->number]) }}">
                                            <div class="card {{ ($answer->number == $index || $answer->answer != null) ? 'text-white bg-info' : 'text-secondary' }} {{ $isLocked ? 'bg-light' : '' }}">
                                                <div class="card-header p-0 p-2">
                                                    <div class="row">
                                                        <div class="col-md-6 text-start" id="lock-number">
                                                            @if ($isLocked && $answer->number != null)
                                                                <i class="ti-lock"></i>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 text-end">
                                                            <i class="ti-flag-alt {{ $answer->doubtful_answer ? 'text-danger' : '' }}"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-2">
                                                    <h5 class="text-center">{{ $answer->number }}</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        {{-- @foreach ($data['question_numbers'] as $numbers)
                            <div class="row d-flex justify-content-center align-item-center">
                                @foreach ($numbers as $number)
                                    @php
                                        $isLocked = false;
                                        foreach ($data['possible_step'] as $value) {
                                            if($number > $value){
                                                $isLocked = true;
                                            }
                                        }
                                    @endphp
                                    <div class="col-md-3">
                                        <a style="text-decoration: none" href="{{ route('api.exam.get_question', [$data['exam']->code, $number]) }}">
                                            <div class="card {{ $number == $index ? 'text-white bg-info' : 'text-secondary' }} {{ $isLocked ? 'bg-light' : '' }}">
                                                <div class="card-header p-0 p-2">
                                                    <div class="row">
                                                        <div class="col-md-6 text-start" id="lock-number">
                                                            @if ($isLocked)
                                                                <i class="ti-lock"></i>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 text-end">
                                                            <i class="ti-flag-alt"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-2">
                                                    <h5 class="text-center">{{ $number }}</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        const sessionEnd = "{{ $data['sessionEnd'] }}";
        const parts = sessionEnd.split(':');

        let sessionEndHour = parseInt(parts[0]);
        let sessionEndMinutes = parseInt(parts[1]);

        let intervalTime;

        function updateTime(){
            let now = new Date();
            let timeEnd = new Date();
            timeEnd.setHours(sessionEndHour);
            timeEnd.setMinutes(sessionEndMinutes);
            timeEnd.setSeconds(0);

            if(now >= timeEnd){
                clearInterval(intervalTime);
                // postAnswer('url');
                return;
            }

            let timeDiff = timeEnd - now;

            let hours = Math.floor(timeDiff / (1000 * 60 * 60));
            let minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            let formattedTime = ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + ':' + ("0" + seconds).slice(-2);

            $('#sessionEnd').html(formattedTime);
        }

        const interval = 1000; // Update every second
        intervalTime = setInterval(updateTime, interval);
    </script>

    <script type="text/javascript">
        $('input[type="radio"]').on('change', function() {
            const answer = $('input[name="answer"]:checked').val();

            $.ajax({
                url: `{{ route('api.exam.set_answer') }}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    answer,
                    index: "{{ $index }}",
                    question: "{{ $data['question']->id }}",
                    exam: "{{ $data['exam']->id }}"
                },
                success: function(res) {
                    Toast.fire({
                        icon: res.status,
                        title: res.message
                    });
                }
            })
        });

        $('input[type="checkbox"]').on('change', function() {
            const doubtful = [];
            $('input[name="doubtful"]:checked').each(function() {
                doubtful.push($(this).val());
            });

            $.ajax({
                url: `{{ route('api.exam.set_doubtful_answer') }}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    doubtful,
                    index: "{{ $index }}",
                    question: "{{ $data['question']->id }}",
                    exam: "{{ $data['exam']->id }}"
                },
                success: function(res) {
                    Toast.fire({
                        icon: res.status,
                        title: res.message
                    });
                }
            })
        });

        $('#next').on('click', function() {
            const data = $(this).data();
            const nextIndex = data.index + 1;

            window.location.href = "{{ route('api.exam.get_question', [$data['exam']->code, ':index']) }}".replace(':index', nextIndex);
        });

        $('#previous').on('click', function() {
            const data = $(this).data();
            const previousIndex = data.index - 1;

            window.location.href = "{{ route('api.exam.get_question', [$data['exam']->code, ':index']) }}".replace(':index', previousIndex);
        });
    </script>
@endpush
