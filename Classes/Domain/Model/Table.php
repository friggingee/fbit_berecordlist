<?php
namespace FBIT\BeRecordList\Domain\Model;

/***
 *
 * This file is part of the "Backend RecordList Manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017
 *
 ***/

/**
 * Table
 */
class Table extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * tablename
     *
     * @var string
     * @validate NotEmpty
     */
    protected $tablename = 0;

    /**
     * allowedRecordTypes
     *
     * @var string
     */
    protected $allowedRecordTypes = 0;

    /**
     * Returns the tablename
     *
     * @return string $tablename
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    /**
     * Sets the tablename
     *
     * @param string $tablename
     * @return void
     */
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Returns the allowedRecordTypes
     *
     * @return string $allowedRecordTypes
     */
    public function getAllowedRecordTypes()
    {
        return $this->allowedRecordTypes;
    }

    /**
     * Sets the allowedRecordTypes
     *
     * @param string $allowedRecordTypes
     * @return void
     */
    public function setAllowedRecordTypes($allowedRecordTypes)
    {
        $this->allowedRecordTypes = $allowedRecordTypes;
    }
}
