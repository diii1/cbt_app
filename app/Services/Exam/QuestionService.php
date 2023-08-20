<?php

namespace App\Services\Exam;

use App\Models\Question;
use App\Types\Entities\QuestionEntity;
use Illuminate\Support\Facades\DB;
use App\Services\Service;
use Illuminate\Support\Collection;

class QuestionService extends Service
{
    public function getQuestions(): Collection
    {
        try {
            return DB::table('questions')->get();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getQuestions", $th);
            return new Collection();
        }
    }

    public function getQuestionByExamID(int $id): Collection
    {
        try {
            return DB::table('questions')->where('exam_id', $id)->get();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getQuestionByExamID", $th);
            return new Collection();
        }
    }

    public function getQuestionByID(int $id): Question | Collection
    {
        try {
            return Question::where('id', $id)->with('exam', 'subject')->first();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getQuestionByID", $th);
            return new Collection();
        }
    }

    public function getMappedQuestionByExamID(int $id): Collection
    {
        try {
            return DB::table('questions')->where('exam_id', $id)->get()->map(function ($data){
                return (object)[
                    "id" => $data->id,
                    "question" => $data->question,
                    "options" => [
                        (object)[
                            "option" => "A",
                            "value" => $data->option_a
                        ],
                        (object)[
                            "option" => "B",
                            "value" => $data->option_b
                        ],
                        (object)[
                            "option" => "C",
                            "value" => $data->option_c
                        ],
                        (object)[
                            "option" => "D",
                            "value" => $data->option_d
                        ],
                        (object)[
                            "option" => "E",
                            "value" => $data->option_e
                        ]
                    ]
                ];
            });
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getMappedQuestionByExamID", $th);
            return new Collection();
        }
    }

    public function getMappedQuestionByID(int $id): object
    {
        try {
            $data = DB::table('questions')->where('id', $id)->first();

            return (object)[
                "id" => $data->id,
                "question" => $data->question,
                "options" => [
                    (object)[
                        "option" => "A",
                        "value" => $data->option_a
                    ],
                    (object)[
                        "option" => "B",
                        "value" => $data->option_b
                    ],
                    (object)[
                        "option" => "C",
                        "value" => $data->option_c
                    ],
                    (object)[
                        "option" => "D",
                        "value" => $data->option_d
                    ],
                    (object)[
                        "option" => "E",
                        "value" => $data->option_e
                    ]
                ]
            ];
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getMappedQuestionByID", $th);
            return new Collection();
        }
    }

    public function getShuffledQuestions(object $data): Collection
    {
        try {
            $shuffledQuestions = $this->fisherYatesShuffle($data);

            foreach ($shuffledQuestions as $data) {
                $data->options = $this->fisherYatesShuffle($data->options);
            }

            return $shuffledQuestions;
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::getShuffledQuestions", $th);
            return new Collection();
        }
    }

    public function shuffleOption(array $data): array
    {
        return $this->fisherYatesShuffle($data);
    }

    public function fisherYatesShuffle($array)
    {
        $count = count($array);

        for ($i = $count - 1; $i > 0; $i--) {
            // random number pos in array with Mersenne Twister
            $j = mt_rand(0, $i);

            // set pos to new random number
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }

        return $array;
    }

    public function insertQuestion(QuestionEntity $request):bool | Collection
    {
        try {
            return DB::table('questions')->insert([
                'exam_id' => $request->exam_id,
                'subject_id' => $request->subject_id,
                'exam_title' => $request->exam_title,
                'subject_name' => $request->subject_name,
                'question' => $request->question,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'option_e' => $request->option_e,
                'answer' => $request->answer,
                'created_by' => $request->created_by,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::insertQuestion", $th);
            return new Collection();
        }
    }

    public function updateQuestion(QuestionEntity $request, int $id):bool | Collection
    {
        try {
            return DB::table('questions')->where('id', $id)->update([
                'question' => $request->question,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'option_e' => $request->option_e,
                'answer' => $request->answer,
            ]);
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::updateQuestion", $th);
            return new Collection();
        }
    }

    public function deleteQuestion(int $id):bool | Collection
    {
        try {
            return DB::table('questions')->where('id', $id)->delete();
        } catch (\Throwable $th) {
            $this->writeLog("QuestionService::deleteQuestion", $th);
            return new Collection();
        }
    }
}
