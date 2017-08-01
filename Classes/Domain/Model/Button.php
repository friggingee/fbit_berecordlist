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
 * Button
 */
class Button extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * identifier
     *
     * @var string
     * @validate NotEmpty
     */
    protected $identifier = 0;

    /**
     * overrideIdentifier
     *
     * @var string
     */
    protected $overrideIdentifier = 0;

    /**
     * headerSide
     *
     * @var string
     */
    protected $headerSide = 0;

    /**
     * Returns the identifier
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier
     *
     * @param string $identifier
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Returns the overrideIdentifier
     *
     * @return string $overrideIdentifier
     */
    public function getOverrideIdentifier()
    {
        return $this->overrideIdentifier;
    }

    /**
     * Sets the overrideIdentifier
     *
     * @param string $overrideIdentifier
     * @return void
     */
    public function setOverrideIdentifier($overrideIdentifier)
    {
        $this->overrideIdentifier = $overrideIdentifier;
    }

    /**
     * Returns the headerSide
     *
     * @return string $headerSide
     */
    public function getHeaderSide()
    {
        return $this->headerSide;
    }

    /**
     * Sets the headerSide
     *
     * @param string $headerSide
     * @return void
     */
    public function setHeaderSide($headerSide)
    {
        $this->headerSide = $headerSide;
    }
}
