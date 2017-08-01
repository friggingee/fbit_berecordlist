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
 * Module
 */
class Module extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * signature
     *
     * @var string
     * @validate NotEmpty
     */
    protected $signature = '';

    /**
     * icon
     *
     * @var int
     * @validate NotEmpty
     */
    protected $icon = 0;

    /**
     * labels
     *
     * @var string
     * @validate NotEmpty
     */
    protected $labels = '';

    /**
     * storagePid
     *
     * @var int
     * @validate NotEmpty
     */
    protected $storagePid = 0;

    /**
     * mainModule
     *
     * @var string
     * @validate NotEmpty
     */
    protected $mainModule = '';

    /**
     * modulelayoutHeaderEnabled
     *
     * @var bool
     * @validate NotEmpty
     */
    protected $modulelayoutHeaderEnabled = false;

    /**
     * modulelayoutHeaderMenuShowoneoptionpertable
     *
     * @var bool
     */
    protected $modulelayoutHeaderMenuShowoneoptionpertable = false;

    /**
     * modulelayoutHeaderMenuShowoneoptionperrecordtype
     *
     * @var bool
     */
    protected $modulelayoutHeaderMenuShowoneoptionperrecordtype = false;

    /**
     * modulelayoutHeaderPagepath
     *
     * @var bool
     */
    protected $modulelayoutHeaderPagepath = false;

    /**
     * modulelayoutHeaderButtonsEnabled
     *
     * @var bool
     */
    protected $modulelayoutHeaderButtonsEnabled = false;

    /**
     * modulelayoutHeaderButtonsShowonenewrecordbuttonpertable
     *
     * @var bool
     */
    protected $modulelayoutHeaderButtonsShowonenewrecordbuttonpertable = false;

    /**
     * modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype
     *
     * @var bool
     */
    protected $modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype = false;

    /**
     * modulelayoutFooterEnabled
     *
     * @var bool
     */
    protected $modulelayoutFooterEnabled = false;

    /**
     * moduleylayoutFooterFieldselection
     *
     * @var bool
     */
    protected $moduleylayoutFooterFieldselection = false;

    /**
     * modulelayoutFooterListoptionsExtendedview
     *
     * @var bool
     */
    protected $modulelayoutFooterListoptionsExtendedview = false;

    /**
     * modulelayoutFooterListoptionsClipboard
     *
     * @var bool
     */
    protected $modulelayoutFooterListoptionsClipboard = false;

    /**
     * modulelayoutFooterListoptionsLocalization
     *
     * @var bool
     */
    protected $modulelayoutFooterListoptionsLocalization = false;

    /**
     * tables
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Table>
     * @cascade remove
     */
    protected $tables = null;

    /**
     * modulelayoutHeaderButtonsLeft
     *
     * @var string
     */
    protected $modulelayoutHeaderButtonsLeft = null;

    /**
     * modulelayoutHeaderButtonsLeftOverride
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Button>
     * @cascade remove
     */
    protected $modulelayoutHeaderButtonsLeftOverride = null;

    /**
     * modulelayoutHeaderButtonsRightOverride
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Button>
     * @cascade remove
     */
    protected $modulelayoutHeaderButtonsRightOverride = null;

    /**
     * modulelayoutHeaderButtonsRight
     *
     * @var string
     */
    protected $modulelayoutHeaderButtonsRight = null;

    /**
     * Returns the signature
     *
     * @return string $signature
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Sets the signature
     *
     * @param string $signature
     * @return void
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * Returns the icon
     *
     * @return int $icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the icon
     *
     * @param int $icon
     * @return void
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Returns the labels
     *
     * @return string $labels
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Sets the labels
     *
     * @param string $labels
     * @return void
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    /**
     * Returns the storagePid
     *
     * @return int $storagePid
     */
    public function getStoragePid()
    {
        return $this->storagePid;
    }

    /**
     * Sets the storagePid
     *
     * @param int $storagePid
     * @return void
     */
    public function setStoragePid($storagePid)
    {
        $this->storagePid = $storagePid;
    }

    /**
     * Returns the mainModule
     *
     * @return string $mainModule
     */
    public function getMainModule()
    {
        return $this->mainModule;
    }

    /**
     * Sets the mainModule
     *
     * @param string $mainModule
     * @return void
     */
    public function setMainModule($mainModule)
    {
        $this->mainModule = $mainModule;
    }

    /**
     * Returns the modulelayoutHeaderEnabled
     *
     * @return bool $modulelayoutHeaderEnabled
     */
    public function getModulelayoutHeaderEnabled()
    {
        return $this->modulelayoutHeaderEnabled;
    }

    /**
     * Sets the modulelayoutHeaderEnabled
     *
     * @param bool $modulelayoutHeaderEnabled
     * @return void
     */
    public function setModulelayoutHeaderEnabled($modulelayoutHeaderEnabled)
    {
        $this->modulelayoutHeaderEnabled = $modulelayoutHeaderEnabled;
    }

    /**
     * Returns the boolean state of modulelayoutHeaderEnabled
     *
     * @return bool
     */
    public function isModulelayoutHeaderEnabled()
    {
        return $this->modulelayoutHeaderEnabled;
    }

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->tables = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->modulelayoutHeaderButtonsLeft = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->modulelayoutHeaderButtonsRight = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the modulelayoutHeaderMenuShowoneoptionpertable
     *
     * @return bool $modulelayoutHeaderMenuShowoneoptionpertable
     */
    public function getModulelayoutHeaderMenuShowoneoptionpertable()
    {
        return $this->modulelayoutHeaderMenuShowoneoptionpertable;
    }

    /**
     * Sets the modulelayoutHeaderMenuShowoneoptionpertable
     *
     * @param bool $modulelayoutHeaderMenuShowoneoptionpertable
     * @return void
     */
    public function setModulelayoutHeaderMenuShowoneoptionpertable($modulelayoutHeaderMenuShowoneoptionpertable)
    {
        $this->modulelayoutHeaderMenuShowoneoptionpertable = $modulelayoutHeaderMenuShowoneoptionpertable;
    }

    /**
     * Returns the boolean state of modulelayoutHeaderMenuShowoneoptionpertable
     *
     * @return bool
     */
    public function isModulelayoutHeaderMenuShowoneoptionpertable()
    {
        return $this->modulelayoutHeaderMenuShowoneoptionpertable;
    }

    /**
     * Returns the modulelayoutHeaderMenuShowoneoptionperrecordtype
     *
     * @return bool $modulelayoutHeaderMenuShowoneoptionperrecordtype
     */
    public function getModulelayoutHeaderMenuShowoneoptionperrecordtype()
    {
        return $this->modulelayoutHeaderMenuShowoneoptionperrecordtype;
    }

    /**
     * Sets the modulelayoutHeaderMenuShowoneoptionperrecordtype
     *
     * @param bool $modulelayoutHeaderMenuShowoneoptionperrecordtype
     * @return void
     */
    public function setModulelayoutHeaderMenuShowoneoptionperrecordtype($modulelayoutHeaderMenuShowoneoptionperrecordtype)
    {
        $this->modulelayoutHeaderMenuShowoneoptionperrecordtype = $modulelayoutHeaderMenuShowoneoptionperrecordtype;
    }

    /**
     * Returns the boolean state of modulelayoutHeaderMenuShowoneoptionperrecordtype
     *
     * @return bool
     */
    public function isModulelayoutHeaderMenuShowoneoptionperrecordtype()
    {
        return $this->modulelayoutHeaderMenuShowoneoptionperrecordtype;
    }

    /**
     * Returns the modulelayoutHeaderPagepath
     *
     * @return bool $modulelayoutHeaderPagepath
     */
    public function getModulelayoutHeaderPagepath()
    {
        return $this->modulelayoutHeaderPagepath;
    }

    /**
     * Sets the modulelayoutHeaderPagepath
     *
     * @param bool $modulelayoutHeaderPagepath
     * @return void
     */
    public function setModulelayoutHeaderPagepath($modulelayoutHeaderPagepath)
    {
        $this->modulelayoutHeaderPagepath = $modulelayoutHeaderPagepath;
    }

    /**
     * Returns the boolean state of modulelayoutHeaderPagepath
     *
     * @return bool
     */
    public function isModulelayoutHeaderPagepath()
    {
        return $this->modulelayoutHeaderPagepath;
    }

    /**
     * Returns the modulelayoutHeaderButtonsEnabled
     *
     * @return bool $modulelayoutHeaderButtonsEnabled
     */
    public function getModulelayoutHeaderButtonsEnabled()
    {
        return $this->modulelayoutHeaderButtonsEnabled;
    }

    /**
     * Sets the modulelayoutHeaderButtonsEnabled
     *
     * @param bool $modulelayoutHeaderButtonsEnabled
     * @return void
     */
    public function setModulelayoutHeaderButtonsEnabled($modulelayoutHeaderButtonsEnabled)
    {
        $this->modulelayoutHeaderButtonsEnabled = $modulelayoutHeaderButtonsEnabled;
    }

    /**
     * Returns the boolean state of modulelayoutHeaderButtonsEnabled
     *
     * @return bool
     */
    public function isModulelayoutHeaderButtonsEnabled()
    {
        return $this->modulelayoutHeaderButtonsEnabled;
    }

    /**
     * Returns the modulelayoutHeaderButtonsShowonenewrecordbuttonpertable
     *
     * @return bool $modulelayoutHeaderButtonsShowonenewrecordbuttonpertable
     */
    public function getModulelayoutHeaderButtonsShowonenewrecordbuttonpertable()
    {
        return $this->modulelayoutHeaderButtonsShowonenewrecordbuttonpertable;
    }

    /**
     * Sets the modulelayoutHeaderButtonsShowonenewrecordbuttonpertable
     *
     * @param bool $modulelayoutHeaderButtonsShowonenewrecordbuttonpertable
     * @return void
     */
    public function setModulelayoutHeaderButtonsShowonenewrecordbuttonpertable($modulelayoutHeaderButtonsShowonenewrecordbuttonpertable)
    {
        $this->modulelayoutHeaderButtonsShowonenewrecordbuttonpertable = $modulelayoutHeaderButtonsShowonenewrecordbuttonpertable;
    }

    /**
     * Returns the boolean state of
     * modulelayoutHeaderButtonsShowonenewrecordbuttonpertable
     *
     * @return bool
     */
    public function isModulelayoutHeaderButtonsShowonenewrecordbuttonpertable()
    {
        return $this->modulelayoutHeaderButtonsShowonenewrecordbuttonpertable;
    }

    /**
     * Returns the modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype
     *
     * @return bool $modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype
     */
    public function getModulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype()
    {
        return $this->modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype;
    }

    /**
     * Sets the modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype
     *
     * @param bool $modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype
     * @return void
     */
    public function setModulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype($modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype)
    {
        $this->modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype = $modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype;
    }

    /**
     * Returns the boolean state of
     * modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype
     *
     * @return bool
     */
    public function isModulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype()
    {
        return $this->modulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype;
    }

    /**
     * Returns the modulelayoutFooterEnabled
     *
     * @return bool $modulelayoutFooterEnabled
     */
    public function getModulelayoutFooterEnabled()
    {
        return $this->modulelayoutFooterEnabled;
    }

    /**
     * Sets the modulelayoutFooterEnabled
     *
     * @param bool $modulelayoutFooterEnabled
     * @return void
     */
    public function setModulelayoutFooterEnabled($modulelayoutFooterEnabled)
    {
        $this->modulelayoutFooterEnabled = $modulelayoutFooterEnabled;
    }

    /**
     * Returns the boolean state of modulelayoutFooterEnabled
     *
     * @return bool
     */
    public function isModulelayoutFooterEnabled()
    {
        return $this->modulelayoutFooterEnabled;
    }

    /**
     * Returns the moduleylayoutFooterFieldselection
     *
     * @return bool $moduleylayoutFooterFieldselection
     */
    public function getModuleylayoutFooterFieldselection()
    {
        return $this->moduleylayoutFooterFieldselection;
    }

    /**
     * Sets the moduleylayoutFooterFieldselection
     *
     * @param bool $moduleylayoutFooterFieldselection
     * @return void
     */
    public function setModuleylayoutFooterFieldselection($moduleylayoutFooterFieldselection)
    {
        $this->moduleylayoutFooterFieldselection = $moduleylayoutFooterFieldselection;
    }

    /**
     * Returns the boolean state of moduleylayoutFooterFieldselection
     *
     * @return bool
     */
    public function isModuleylayoutFooterFieldselection()
    {
        return $this->moduleylayoutFooterFieldselection;
    }

    /**
     * Returns the modulelayoutFooterListoptionsExtendedview
     *
     * @return bool $modulelayoutFooterListoptionsExtendedview
     */
    public function getModulelayoutFooterListoptionsExtendedview()
    {
        return $this->modulelayoutFooterListoptionsExtendedview;
    }

    /**
     * Sets the modulelayoutFooterListoptionsExtendedview
     *
     * @param bool $modulelayoutFooterListoptionsExtendedview
     * @return void
     */
    public function setModulelayoutFooterListoptionsExtendedview($modulelayoutFooterListoptionsExtendedview)
    {
        $this->modulelayoutFooterListoptionsExtendedview = $modulelayoutFooterListoptionsExtendedview;
    }

    /**
     * Returns the boolean state of modulelayoutFooterListoptionsExtendedview
     *
     * @return bool
     */
    public function isModulelayoutFooterListoptionsExtendedview()
    {
        return $this->modulelayoutFooterListoptionsExtendedview;
    }

    /**
     * Returns the modulelayoutFooterListoptionsClipboard
     *
     * @return bool $modulelayoutFooterListoptionsClipboard
     */
    public function getModulelayoutFooterListoptionsClipboard()
    {
        return $this->modulelayoutFooterListoptionsClipboard;
    }

    /**
     * Sets the modulelayoutFooterListoptionsClipboard
     *
     * @param bool $modulelayoutFooterListoptionsClipboard
     * @return void
     */
    public function setModulelayoutFooterListoptionsClipboard($modulelayoutFooterListoptionsClipboard)
    {
        $this->modulelayoutFooterListoptionsClipboard = $modulelayoutFooterListoptionsClipboard;
    }

    /**
     * Returns the boolean state of modulelayoutFooterListoptionsClipboard
     *
     * @return bool
     */
    public function isModulelayoutFooterListoptionsClipboard()
    {
        return $this->modulelayoutFooterListoptionsClipboard;
    }

    /**
     * Returns the modulelayoutFooterListoptionsLocalization
     *
     * @return bool $modulelayoutFooterListoptionsLocalization
     */
    public function getModulelayoutFooterListoptionsLocalization()
    {
        return $this->modulelayoutFooterListoptionsLocalization;
    }

    /**
     * Sets the modulelayoutFooterListoptionsLocalization
     *
     * @param bool $modulelayoutFooterListoptionsLocalization
     * @return void
     */
    public function setModulelayoutFooterListoptionsLocalization($modulelayoutFooterListoptionsLocalization)
    {
        $this->modulelayoutFooterListoptionsLocalization = $modulelayoutFooterListoptionsLocalization;
    }

    /**
     * Returns the boolean state of modulelayoutFooterListoptionsLocalization
     *
     * @return bool
     */
    public function isModulelayoutFooterListoptionsLocalization()
    {
        return $this->modulelayoutFooterListoptionsLocalization;
    }

    /**
     * Adds a Table
     *
     * @param \FBIT\BeRecordList\Domain\Model\Table $table
     * @return void
     */
    public function addTable(\FBIT\BeRecordList\Domain\Model\Table $table)
    {
        $this->tables->attach($table);
    }

    /**
     * Removes a Table
     *
     * @param \FBIT\BeRecordList\Domain\Model\Table $tableToRemove The Table to be removed
     * @return void
     */
    public function removeTable(\FBIT\BeRecordList\Domain\Model\Table $tableToRemove)
    {
        $this->tables->detach($tableToRemove);
    }

    /**
     * Returns the tables
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Table> $tables
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Sets the tables
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Table> $tables
     * @return void
     */
    public function setTables(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tables)
    {
        $this->tables = $tables;
    }

    /**
     * Adds a Button
     *
     * @param \FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsLeftOverride
     * @return void
     */
    public function addModulelayoutHeaderButtonsLeftOverride(\FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsLeftOverride)
    {
        $this->modulelayoutHeaderButtonsLeftOverride->attach($modulelayoutHeaderButtonsLeftOverride);
    }

    /**
     * Removes a Button
     *
     * @param \FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsLeftOverrideToRemove The Button to be removed
     * @return void
     */
    public function removeModulelayoutHeaderButtonsLeftOverride(\FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsLeftOverrideToRemove)
    {
        $this->modulelayoutHeaderButtonsLeftOverride->detach($modulelayoutHeaderButtonsLeftOverrideToRemove);
    }

    /**
     * Returns the modulelayoutHeaderButtonsLeftOverride
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Button> $modulelayoutHeaderButtonsLeftOverride
     */
    public function getModulelayoutHeaderButtonsLeftOverride()
    {
        return $this->modulelayoutHeaderButtonsLeftOverride;
    }

    /**
     * Sets the modulelayoutHeaderButtonsLeftOverride
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Button> $modulelayoutHeaderButtonsLeftOverride
     * @return void
     */
    public function setModulelayoutHeaderButtonsLeftOverride(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $modulelayoutHeaderButtonsLeftOverride)
    {
        $this->modulelayoutHeaderButtonsLeftOverride = $modulelayoutHeaderButtonsLeftOverride;
    }

    /**
     * Adds a Button
     *
     * @param \FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsRightOverride
     * @return void
     */
    public function addModulelayoutHeaderButtonsRightOverride(\FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsRightOverride)
    {
        $this->modulelayoutHeaderButtonsRightOverride->attach($modulelayoutHeaderButtonsRightOverride);
    }

    /**
     * Removes a Button
     *
     * @param \FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsRightOverrideToRemove The Button to be removed
     * @return void
     */
    public function removeModulelayoutHeaderButtonsRightOverride(\FBIT\BeRecordList\Domain\Model\Button $modulelayoutHeaderButtonsRightOverrideToRemove)
    {
        $this->modulelayoutHeaderButtonsRightOverride->detach($modulelayoutHeaderButtonsRightOverrideToRemove);
    }

    /**
     * Returns the modulelayoutHeaderButtonsRightOverride
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Button> $modulelayoutHeaderButtonsRightOverride
     */
    public function getModulelayoutHeaderButtonsRightOverride()
    {
        return $this->modulelayoutHeaderButtonsRightOverride;
    }

    /**
     * Sets the modulelayoutHeaderButtonsRight
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FBIT\BeRecordList\Domain\Model\Button> $modulelayoutHeaderButtonsRightOverride
     * @return void
     */
    public function setModulelayoutHeaderButtonsRightOverride(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $modulelayoutHeaderButtonsRightOverride)
    {
        $this->modulelayoutHeaderButtonsRightOverride = $modulelayoutHeaderButtonsRightOverride;
    }

    /**
     * @return string
     */
    public function getModulelayoutHeaderButtonsLeft(): string
    {
        return $this->modulelayoutHeaderButtonsLeft;
    }

    /**
     * @param string $modulelayoutHeaderButtonsLeft
     */
    public function setModulelayoutHeaderButtonsLeft(string $modulelayoutHeaderButtonsLeft)
    {
        $this->modulelayoutHeaderButtonsLeft = $modulelayoutHeaderButtonsLeft;
    }

    /**
     * @return string
     */
    public function getModulelayoutHeaderButtonsRight(): string
    {
        return $this->modulelayoutHeaderButtonsRight;
    }

    /**
     * @param string $modulelayoutHeaderButtonsRight
     */
    public function setModulelayoutHeaderButtonsRight(string $modulelayoutHeaderButtonsRight)
    {
        $this->modulelayoutHeaderButtonsRight = $modulelayoutHeaderButtonsRight;
    }
}
