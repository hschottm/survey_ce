<?php

namespace Hschottm\SurveyBundle\Migration;

use Contao\CoreBundle\Migration\MigrationInterface;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

class ChoicesFieldMigration implements MigrationInterface
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return 'Contao Survey Choices Field Migration';
    }

    public function shouldRun(): bool
    {
        if (!$this->connection->executeQuery("SELECT id FROM tl_survey_question WHERE choices LIKE '%a:1:{s:6:\"choice\"%'")->rowCount()) {
            return true;
        }

        return false;
    }

    public function run(): MigrationResult
    {
        $result = $this->connection->executeQuery("SELECT id, choices FROM tl_survey_question WHERE choices IS NOT NULL;");
        if ($result->rowCount() > 0) {
            $stmt = $this->connection->prepare("UPDATE tl_survey_question SET choices=? WHERE id=?");
            foreach ($result as $row) {
                $groups = [];
                $choices = StringUtil::deserialize($row['choices']);
                if (isset($choices['choice'])) {
                    continue;
                }
                $i = 0;
                foreach ($choices as $choice) {
                    $i++;
                    $groups[$i] = ['choice' => $choice];
                }
                $stmt->executeQuery([serialize($groups), $row['id']]);
            }
        }
        return new MigrationResult(true, "Migrated Choices field!");
    }
}