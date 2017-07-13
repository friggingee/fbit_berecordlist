module.tx_fbitberecordlist {
    settings {
        modules {
            sbb_pois {
                icon = EXT:sbb_pois/Resources/Public/Icons/sbahn_logo.svg
                labels = LLL:EXT:sbb_pois/Resources/Private/Language/locallang.xlf
                storagePid = 17
                tables {
                    tx_news_domain_model_news = 1
                    tx_sbbpois_domain_model_poi = 1
                }
            }
            sbb_tickets {
                icon = EXT:sbb_tickets/Resources/Public/Icons/sbahn_icon_typo3_be_256x256.png
                labels = LLL:EXT:sbb_tickets/Resources/Private/Language/locallang_ticketmanagement.xlf
                storagePid = 287
                tables {
                    tx_sbbtickets_domain_model_ticket = 1
                    tx_sbbtickets_domain_model_property = 1
                }
            }
        }
    }
}