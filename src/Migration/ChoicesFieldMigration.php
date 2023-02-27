<?php

declare(strict_types=1);

namespace Hschottm\SurveyBundle\Migration;

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	       https://github.com/hschottm/survey_ce
 *
 * forked by pdir
 * @author     Mathias Arzberger <develop@pdir.de>
 * @link       https://github.com/pdir/contao-survey
 */


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

    private $schemaManager;

    // a sql statement that returns only the records that can be migrated
    private string $strSqlCondition = 'SELECT id, choices FROM tl_survey_question WHERE (choices IS NOT NULL) AND (LENGTH(choices) > 0)';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        if (method_exists($connection, 'createSchemaManager')) {
            $this->schemaManager = $connection->createSchemaManager();
        } else {
            $this->schemaManager = $connection->getSchemaManager();
        }
    }

    public function getName(): string
    {
        return 'Contao Survey `choices`-Field Migration';
    }

    public function shouldRun(): bool
    {
        if (!$this->schemaManager->tablesExist(['tl_survey_question'])) {
            return false;
        }

        if ($this->hasOldFormat()) {
            return true;
        }

        return false;
    }

    public function run(): MigrationResult
    {
        // there is a strange behavior here, a field of type blob can be null or empty, if it does not contain the value null it can still contain an empty string
        $result = $this->connection->executeQuery($this->strSqlCondition);

        if ($result->rowCount() > 0) {
            $stmt = $this->connection->prepare('UPDATE tl_survey_question SET choices=? WHERE id=?');

            foreach ($result as $record) {
                $groups = [];
                $arrChoices = StringUtil::deserialize($record['choices']);
                $i = 1;

                foreach ($arrChoices as $oldChoice) {
                    $groups[$i] = ['choice' => $oldChoice];
                    ++$i;
                }
                $stmt->executeQuery([serialize($groups), $record['id']]);
            }
        }

        return new MigrationResult(true, 'The `choices` field was migrated successfully.');
    }

    /**
     * checks if the old structure is present.
     */
    private function hasOldFormat(): bool
    {
        $validRecords = $this->connection->executeQuery($this->strSqlCondition);
        if ($validRecords) {
            foreach ($validRecords as $record) {
                $arrChoices = StringUtil::deserialize($record['choices']);
                foreach ($arrChoices as $choice) {
                    if ('string' === \gettype($choice)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
