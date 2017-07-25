<?php

namespace FBIT\BeRecordList\Hooks;

use TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

class RecordListGetTableHook implements RecordListGetTableHookInterface
{
    /**
     * @param string $table
     * @param int $pageId
     * @param string $additionalWhereClause
     * @param string $selectedFieldsList
     * @param \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList $parentObject
     */
    public function getDBlistQuery($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
    {
        $this->redirectIfBackFromLocalization();

        $extensionName = GeneralUtility::_GP('extension');
        $recordType = GeneralUtility::_GP('recordtype');
        $recordTypeColumn = GeneralUtility::_GP('recordtypecolumn');

        if (
            !empty($extensionName)
            && $recordType !== null
            && !empty($recordTypeColumn)
        ) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $parentObject->getQueryBuilder($table, $pageId);
            $addWhere = (string)$queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq(
                    $recordTypeColumn,
                    '"' . $recordType . '"'
                )
            );

            $additionalWhereClause = (string)$queryBuilder->expr()->andX($addWhere, $additionalWhereClause);
        }
    }

    protected function redirectIfBackFromLocalization(): void
    {
        $returnUrl = GeneralUtility::_GET('returnUrl');
        if (!empty($returnUrl)) {
            $redirect = false;
            $redirectUrl = '';

            $returnUrlParts = explode('?', $returnUrl);
            $returnUrlParameters = explode('&', $returnUrlParts[1]);

            foreach ($returnUrlParameters as $returnUrlKey => $returnUrlParameter) {
                $parameterData = explode('=', $returnUrlParameter);
                if (
                    $parameterData[0] === 'backFromLocalization'
                    && (int)$parameterData[1] === 1
                ) {
                    $redirect = true;
                    unset($returnUrlParameters[$returnUrlKey]);
                }
            }

            $redirectUrlParts = implode('&', $returnUrlParameters);
            $redirectUrl = implode('?', [$returnUrlParts[0], $redirectUrlParts]);

            if (
                $redirect
                && !empty($redirectUrl)
            ) {
                HttpUtility::redirect($redirectUrl);
            }
        }
    }
}