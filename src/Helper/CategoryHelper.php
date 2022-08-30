<?php

namespace Hschottm\SurveyBundle\Helper;

use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Hschottm\SurveyBundle\Exception\NoUserResultException;
use Hschottm\SurveyBundle\Exception\SurveyNotFoundException;
use Hschottm\SurveyBundle\SurveyModel;
use Hschottm\SurveyBundle\SurveyQuestionModel;
use Hschottm\SurveyBundle\SurveyQuestionMultiplechoice;

class CategoryHelper
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getUserMainCategory(string $userPin, int $surveyId): int
    {
        $surveyModel = SurveyModel::findByPk($surveyId);
        if (!$surveyModel) {
            throw new SurveyNotFoundException("No survey with id $surveyId could be found!");
        }

        $results = $this->connection->executeQuery(
            "SELECT id,qid,result FROM tl_survey_result WHERE pid=? AND pin=?",
            [$surveyId, $userPin]
        );

        $userCategories = [];

        foreach ($results->fetchAllAssociative() as $answer) {
            $question = SurveyQuestionModel::findByPk($answer['qid']);
            if (!$question || SurveyQuestionMultiplechoice::TYPE !== $question->questiontype) {
                continue;
            }


            $result = StringUtil::deserialize($answer['result'] ?? '', true)['value'] ?? null;
            if (!$result) {
                continue;
            }

            $categoryId = $question->getCategoryByChoice((int)$answer['result']);
            if ($categoryId) {
                $userCategories[$categoryId] = (($userCategories[$categoryId] ?? 0) + 1);
            }
        }

//        return $userCategories
//
//        if (!$results->rowCount()) {
//            throw new NoUserResultException("No results could be fount for user with pin $userPin!");
//        }
//
//        while ($row = $results->fetchAssociative()) {
//
//        }

        return 0;
    }

    public function getSurveyMainCategories(int $surveyId): array
    {

    }

    public function getCategoryByChoices(int $choice, SurveyQuestionModel $questionModel, SurveyModel $surveyModel = null): array
    {
        if (!$surveyModel) {

        }

        if (is_int($question)) {
            $questionModel = SurveyModel::findByPk($survey);
        } elseif ($survey instanceof SurveyModel) {
            $questionModel = $survey;
        }
        if (empty($questionModel)) {
            throw new \Exception("Parameter survey need to be an SurveyModel instance or an integer!");
        }


    }


}