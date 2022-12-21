<?php

declare(strict_types=1);

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
        $schemaManager = $this->connection->getSchemaManager();

        if (!$schemaManager->tablesExist(['tl_survey_question'])) {
            return false;
        }

        if (0 !== $this->connection->executeQuery("SELECT id FROM tl_survey_question WHERE choices LIKE '%a:1:{s:6:\"choice\"%'")->rowCount()) {
            return true;
        }

        return false;
    }

    public function run(): MigrationResult
    {
        $result = $this->connection->executeQuery('SELECT id, choices FROM tl_survey_question WHERE choices IS NOT NULL;');

        if ($result->rowCount() > 0) {
            $stmt = $this->connection->prepare('UPDATE tl_survey_question SET choices=? WHERE id=?');

            foreach ($result as $row) {
                $groups = [];
                $choices = StringUtil::deserialize($row['choices']);

                if (isset($choices['choice'])) {
                    continue;
                }
                $i = 0;

                foreach ($choices as $choice) {
                    ++$i;
                    $groups[$i] = ['choice' => $choice];
                }
                $stmt->executeQuery([serialize($groups), $row['id']]);
            }
        }

        return new MigrationResult(true, 'Migrated Choices field!');
    }
}
