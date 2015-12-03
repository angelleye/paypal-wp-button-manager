<?php

function get_paypal_button_currency_symbol($currency = '') {
    if (!$currency) {
        $currency = get_paypal_button_currency();
    }

    switch ($currency) {
        case 'AED' :
            $currency_symbol = '?.?';
            break;
        case 'BDT':
            $currency_symbol = '&#2547;&nbsp;';
            break;
        case 'BRL' :
            $currency_symbol = '&#82;&#36;';
            break;
        case 'BGN' :
            $currency_symbol = '&#1083;&#1074;.';
            break;
        case 'AUD' :
        case 'CAD' :
        case 'CLP' :
        case 'MXN' :
        case 'NZD' :
        case 'HKD' :
        case 'SGD' :
        case 'USD' :
            $currency_symbol = '&#36;';
            break;
        case 'EUR' :
            $currency_symbol = '&euro;';
            break;
        case 'CNY' :
        case 'RMB' :
        case 'JPY' :
            $currency_symbol = '&yen;';
            break;
        case 'RUB' :
            $currency_symbol = '&#1088;&#1091;&#1073;.';
            break;
        case 'KRW' : $currency_symbol = '&#8361;';
            break;
        case 'TRY' : $currency_symbol = '&#8378;';
            break;
        case 'NOK' : $currency_symbol = '&#107;&#114;';
            break;
        case 'ZAR' : $currency_symbol = '&#82;';
            break;
        case 'CZK' : $currency_symbol = '&#75;&#269;';
            break;
        case 'MYR' : $currency_symbol = '&#82;&#77;';
            break;
        case 'DKK' : $currency_symbol = 'kr.';
            break;
        case 'HUF' : $currency_symbol = '&#70;&#116;';
            break;
        case 'IDR' : $currency_symbol = 'Rp';
            break;
        case 'INR' : $currency_symbol = 'Rs.';
            break;
        case 'ISK' : $currency_symbol = 'Kr.';
            break;
        case 'ILS' : $currency_symbol = '&#8362;';
            break;
        case 'PHP' : $currency_symbol = '&#8369;';
            break;
        case 'PLN' : $currency_symbol = '&#122;&#322;';
            break;
        case 'SEK' : $currency_symbol = '&#107;&#114;';
            break;
        case 'CHF' : $currency_symbol = '&#67;&#72;&#70;';
            break;
        case 'TWD' : $currency_symbol = '&#78;&#84;&#36;';
            break;
        case 'THB' : $currency_symbol = '&#3647;';
            break;
        case 'GBP' : $currency_symbol = '&pound;';
            break;
        case 'RON' : $currency_symbol = 'lei';
            break;
        case 'VND' : $currency_symbol = '&#8363;';
            break;
        case 'NGN' : $currency_symbol = '&#8358;';
            break;
        case 'HRK' : $currency_symbol = 'Kn';
            break;
        default : $currency_symbol = '';
            break;
    }

    return apply_filters('paypal_button_currency_symbol', $currency_symbol, $currency);
}

function get_paypal_button_currency_with_symbole() {

    $paypal_button_currency_with_symbole = array(
        "USD" => "&#36;",
        "AUD" => "&#36;",
        "BRL" => "&#82;&#36;",
        "GBP" => "&pound;",
        "CAD" => "&#36;",
        "CZK" => "&#75;&#269;",
        "DKK" => "DKK",
        "EUR" => "&euro;",
        "HKD" => "&#36;",
        "HUF" => "&#70;&#116;",
        "ILS" => "&#8362;",
        "JPY" => "&yen;",
        "MXN" => "&#36;",
        "MYR" => "&#82;&#77;",
        "TWD" => "&#78;&#84;&#36;",
        "NZD" => "&#36;",
        "NOK" => "&#107;&#114;",
        "PHP" => "&#8369;",
        "PLN" => "&#122;&#322;",
        "RUB" => "&#1088;&#1091;&#1073;.",
        "SGD" => "&#36;",
        "SEK" => "&#107;&#114;",
        "CHF" => "&#67;&#72;&#70;",
        "THB" => "&#3647;",
		"TRY" => "&#8378;"
    );
    return $paypal_button_currency_with_symbole;
}

function get_paypal_button_options() {
    $paypal_button_options = ( array(
        "products" => "Shopping Cart",
        "services" => "Buy Now",
        "donations" => "Donation",
        "gift_certs" => "Gift Certificate",
        "subscriptions" => "Subscription"
            ));

    return array_unique($paypal_button_options);
}

function get_paypal_button_currency() {

    $paypal_button_currency = ( array(
        "USD" => "USD",
        "AUD" => "AUD",
        "BRL" => "BRL",
        "GBP" => "GBP",
        "CAD" => "CAD",
        "CZK" => "CZK",
        "DKK" => "DKK",
        "EUR" => "EUR",
        "HKD" => "HKD",
        "HUF" => "HUF",
        "ILS" => "ILS",
        "JPY" => "JPY",
        "MXN" => "MXN",
        "MYR" =>"MYR",
        "TWD" => "TWD",
        "NZD" => "NZD",
        "NOK" => "NOK",
        "PHP" => "PHP",
        "PLN" => "PLN",
        "RUB" => "RUB",
        "SGD" => "SGD",
        "SEK" => "SEK",
        "CHF" => "CHF",
        "THB" => "THB",
        "TRY" => "TRY",
        
        
            ));
    return array_unique($paypal_button_currency);
}

function get_paypal_button_subscriptions() {
    $paypal_button_subscriptions = ( array(
        "D" => "Daily",
        "W" => "Weekly",
        "M" => "Monthly",
        "Y" => "Yearly"));
    return array_unique($paypal_button_subscriptions);
}

function get_paypal_button_subscriptions_cycle() {
    $paypal_button_subscriptions_cycle = ( array(
        "D" => "day (s)",
        "W" => "week (s)",
        "M" => "month (s)",
        "Y" => "years (s)"));
    return array_unique($paypal_button_subscriptions_cycle);
}

function get_paypal_button_gcBackgroundColor() {
    $paypal_button_gcBackgroundColor = ( array(
        "BLU" => "Blue",
        "ORG" => "Orange",
        "GRN" => "Green",
        "PPL" => "Purple"));
    return array_unique($paypal_button_gcBackgroundColor);
}

function get_paypal_button_gcBackgroundTheme() {

    $paypal_button_gcBackgroundTheme = ( array(
        "BD" => "Birthday",
        "WT" => "Winter",
        "WA" => "Wedding/Anniversary",
        "BB" => "New Baby - Blue",
        "BP" => "New Baby - Pink",
        "BY" => "New Baby - Yellow",
        "BD" => "Birthday",
        "WT" => "Winter",
        "WA" => "Wedding/Anniversary",
        "PPL" => "Purple"));
    return array_unique($paypal_button_gcBackgroundTheme);
}

function get_paypal_button_subscriptions_cycle_billing_limit() {
    $paypal_button_subscriptions_cycle_billing_limit = ( array(
        "1" => "1",
        "2" => "2",
        "3" => "3",
        "4" => "4",
        "5" => "5",
        "6" => "6",
        "7" => "7",
        "8" => "8",
        "9" => "9",
        "10" => "10",
        "11" => "11",
        "12" => "12",
        "13" => "13",
        "14" => "14",
        "15" => "15",
        "16" => "16",
        "17" => "17",
        "18" => "18",
        "19" => "19",
        "20" => "20",
        "21" => "21",
        "22" => "22",
        "23" => "23",
        "24" => "24",
        "25" => "25",
        "26" => "26",
        "27" => "27",
        "28" => "28",
        "29" => "29",
        "30" => "30"));
    return array_unique($paypal_button_subscriptions_cycle_billing_limit);
}

function get_paypal_button_subscription_trial_duration() {

    $paypal_button_subscription_trial_duration = ( array(
        "1" => "1",
        "2" => "2",
        "3" => "3",
        "4" => "4",
        "5" => "5",
        "6" => "6",
        "7" => "7",
        "8" => "8",
        "9" => "9",
        "10" => "10",
        "11" => "11",
        "12" => "12",
        "13" => "13",
        "14" => "14",
        "15" => "15",
        "16" => "16",
        "17" => "17",
        "18" => "18",
        "19" => "19",
        "20" => "20",
        "21" => "21",
        "22" => "22",
        "23" => "23",
        "24" => "24",
        "25" => "25",
        "26" => "26",
        "27" => "27",
        "28" => "28",
        "29" => "29",
        "30" => "30",
        "31" => "31",
        "32" => "32",
        "33" => "33",
        "34" => "34",
        "35" => "35",
        "36" => "36",
        "37" => "37",
        "38" => "38",
        "39" => "39",
        "40" => "40",
        "41" => "41",
        "42" => "42",
        "43" => "43",
        "44" => "44",
        "45" => "45",
        "46" => "46",
        "47" => "47",
        "48" => "48",
        "49" => "49",
        "50" => "50",
        "51" => "51",
        "52" => "52"));
    return array_unique($paypal_button_subscription_trial_duration);
}

function get_paypal_button_languages() {

    $paypal_button_language = ( array(
        "en_AL" => "Albania - English",
        "en_DZ" => "Algeria - English",
        "en_AD" => "Andorra - English",
        "en_AO" => "Angola - English",
        "en_AI" => "Anguilla - English",
        "en_AG" => "Antigua and Barbuda - English",
        "en_AR" => "Argentina - English",
        "en_AM" => "Armenia - English",
        "en_AW" => "Aruba - English",
        "en_AU" => "Australia - Australian English",
        "de_AT" => "Austria - German",
        "en_AT" => "Austria - English",
        "en_AZ" => "Azerbaijan Republic - English",
        "en_BS" => "Bahamas - English",
        "en_BH" => "Bahrain - English",
        "en_BB" => "Barbados - English",
        "en_BY" => "Belarus - English",
        "en_BE" => "Belgium - English",
        "nl_BE" => "Belgium - Dutch",
        "fr_BE" => "Belgium - French",
        "en_BZ" => "Belize - English",
        "en_BJ" => "Benin - English",
        "en_BM" => "Bermuda - English",
        "en_BT" => "Bhutan - English",
        "en_BO" => "Bolivia - English",
        "en_BA" => "Bosnia and Herzegovina - English",
        "en_BW" => "Botswana - English",
        "pt_BR" => "Brazil - Portuguese",
        "en_BR" => "Brazil - English",
        "en_BN" => "Brunei - English",
        "en_BG" => "Bulgaria - English",
        "en_BF" => "Burkina Faso - English",
        "en_BI" => "Burundi - English",
        "en_KH" => "Cambodia - English",
        "en_CM" => "Cameroon - English",
        "en_CA" => "Canada - English",
        "fr_CA" => "Canada - French",
        "en_CV" => "Cape Verde - English",
        "en_KY" => "Cayman Islands - English",
        "en_TD" => "Chad - English",
        "en_CL" => "Chile - English",
        "en_C2" => "China - English",
        "zh_C2" => "China - Simplified Chinese",
        "en_CO" => "Colombia - English",
        "en_KM" => "Comoros - English",
        "en_CK" => "Cook Islands - English",
        "en_CR" => "Costa Rica - English",
        "en_CI" => "Cote D'Ivoire - English",
        "en_HR" => "Croatia - English",
        "en_CY" => "Cyprus - English",
        "en_CZ" => "Czech Republic - English",
        "en_CD" => "Democratic Republic of the Congo - English",
        "da_DK" => "Denmark - Danish",
        "en_DK" => "Denmark - English",
        "en_DJ" => "Djibouti - English",
        "en_DM" => "Dominica - English",
        "en_DO" => "Dominican Republic - English",
        "en_EC" => "Ecuador - English",
        "en_EG" => "Egypt - English",
        "en_SV" => "El Salvador - English",
        "en_ER" => "Eritrea - English",
        "en_EE" => "Estonia - English",
        "ru_EE" => "Estonia - Russian",
        "fr_EE" => "Estonia - French",
        "es_EE" => "Estonia - Spanish",
        "zh_EE" => "Estonia - Simplified Chinese",
        "en_ET" => "Ethiopia - English",
        "en_FK" => "Falkland Islands - English",
        "en_FO" => "Faroe Islands - English",
        "en_FJ" => "Fiji - English",
        "en_FI" => "Finland - English",
        "fr_FR" => "France - French",
        "en_FR" => "France - English",
        "en_GF" => "French Guiana - English",
        "en_PF" => "French Polynesia - English",
        "en_GA" => "Gabon Republic - English",
        "en_GM" => "Gambia - English",
        "en_GE" => "Georgia - English",
        "de_DE" => "Germany - German",
        "en_DE" => "Germany - English",
        "en_GI" => "Gibraltar - English",
        "en_GR" => "Greece - English",
        "en_GL" => "Greenland - English",
        "en_GD" => "Grenada - English",
        "en_GP" => "Guadeloupe - English",
        "en_GT" => "Guatemala - English",
        "en_GN" => "Guinea - English",
        "en_GW" => "Guinea Bissau - English",
        "en_GY" => "Guyana - English",
        "en_HN" => "Honduras - English",
        "en_HK" => "Hong Kong - English",
        "zh_HK" => "Hong Kong - Traditional Chinese",
        "en_HU" => "Hungary - English",
        "en_IS" => "Iceland - English",
        "en_IN" => "India - English",
        "en_ID" => "Indonesia - English",
        "en_IE" => "Ireland - English",
        "en_IL" => "Israel - English",
        "he_IL" => "Israel - Hebrew",
        "it_IT" => "Italy - Italian",
        "en_IT" => "Italy - English",
        "en_JM" => "Jamaica - English",
        "ja_JP" => "Japan - Japanese",
        "en_JP" => "Japan - English",
        "en_JO" => "Jordan - English",
        "en_KZ" => "Kazakhstan - English",
        "en_KE" => "Kenya - English",
        "en_KI" => "Kiribati - English",
        "en_KW" => "Kuwait - English",
        "en_KG" => "Kyrgyzstan - English",
        "en_LA" => "Laos - English",
        "en_LV" => "Latvia - English",
        "en_LS" => "Lesotho - English",
        "en_LI" => "Liechtenstein - English",
        "en_LT" => "Lithuania - English",
        "en_LU" => "Luxembourg - English",
        "en_MK" => "Macedonia - English",
        "en_MG" => "Madagascar - English",
        "en_MW" => "Malawi - English",
        "en_MY" => "Malaysia - English",
        "en_MV" => "Maldives - English",
        "en_ML" => "Mali - English",
        "en_MT" => "Malta - English",
        "en_MH" => "Marshall Islands - English",
        "en_MQ" => "Martinique - English",
        "en_MR" => "Mauritania - English",
        "en_MU" => "Mauritius - English",
        "en_YT" => "Mayotte - English",
        "es_MX" => "Mexico - Spanish",
        "en_MX" => "Mexico - English",
        "en_FM" => "Micronesia - English",
        "en_MD" => "Moldova - English",
        "en_MC" => "Monaco - English",
        "en_MN" => "Mongolia - English",
        "en_ME" => "Montenegro - English",
        "en_MS" => "Montserrat - English",
        "en_MA" => "Morocco - English",
        "en_MZ" => "Mozambique - English",
        "en_NA" => "Namibia - English",
        "en_NR" => "Nauru - English",
        "en_NP" => "Nepal - English",
        "nl_NL" => "Netherlands - Dutch",
        "en_NL" => "Netherlands - English",
        "en_AN" => "Netherlands Antilles - English",
        "en_NC" => "New Caledonia - English",
        "en_NZ" => "New Zealand - English",
        "en_NI" => "Nicaragua - English",
        "en_NE" => "Niger - English",
        "en_NG" => "Nigeria - English",
        "en_NU" => "Niue - English",
        "en_NF" => "Norfolk Island - English",
        "no_NO" => "Norway - Norwegian",
        "en_NO" => "Norway - English",
        "en_OM" => "Oman - English",
        "en_PW" => "Palau - English",
        "en_PA" => "Panama - English",
        "en_PG" => "Papua New Guinea - English",
        "en_PY" => "Paraguay - English",
        "en_PE" => "Peru - English",
        "en_PH" => "Philippines - English",
        "en_PN" => "Pitcairn Islands - English",
        "pl_PL" => "Poland - Polish",
        "en_PL" => "Poland - English",
        "en_PT" => "Portugal - English",
        "pt_PT" => "Portugal - Portuguese",
        "fr_PT" => "Portugal - French",
        "es_PT" => "Portugal - Spanish",
        "zh_PT" => "Portugal - Simplified Chinese",
        "en_QA" => "Qatar - English",
        "en_CG" => "Republic of the Congo - English",
        "en_RE" => "Reunion - English",
        "en_RO" => "Romania - English",
        "ru_RU" => "Russia - Russian",
        "en_RU" => "Russia - English",
        "en_RW" => "Rwanda - English",
        "en_KN" => "Saint Kitts and Nevis Anguilla - English",
        "en_PM" => "Saint Pierre and Miquelon - English",
        "en_VC" => "Saint Vincent and Grenadines - English",
        "en_WS" => "Samoa - English",
        "en_SM" => "San Marino - English",
        "en_ST" => "São Tomé and Príncipe - English",
        "en_SA" => "Saudi Arabia - English",
        "en_SN" => "Senegal - English",
        "en_RS" => "Serbia - English",
        "en_SC" => "Seychelles - English",
        "en_SL" => "Sierra Leone - English",
        "en_SG" => "Singapore - English",
        "en_SK" => "Slovakia - English",
        "en_SI" => "Slovenia - English",
        "en_SB" => "Solomon Islands - English",
        "en_SO" => "Somalia - English",
        "en_ZA" => "South Africa - English",
        "en_KR" => "South Korea - English",
        "es_ES" => "Spain - Spanish",
        "en_ES" => "Spain - English",
        "en_LK" => "Sri Lanka - English",
        "en_SH" => "St. Helena - English",
        "en_LC" => "St. Lucia - English",
        "en_SR" => "Suriname - English",
        "en_SJ" => "Svalbard and Jan Mayen Islands - English",
        "en_SZ" => "Swaziland - English",
        "sv_SE" => "Sweden - Swedish",
        "en_SE" => "Sweden - English",
        "de_CH" => "Switzerland - German",
        "fr_CH" => "Switzerland - French",
        "en_CH" => "Switzerland - English",
        "zh_TW" => "Taiwan - Traditional Chinese",
        "en_TW" => "Taiwan - English",
        "en_TJ" => "Tajikistan - English",
        "en_TZ" => "Tanzania - English",
        "th_TH" => "Thailand - Thai",
        "en_TH" => "Thailand - English",
        "en_TG" => "Togo - English",
        "en_TO" => "Tonga - English",
        "en_TT" => "Trinidad and Tobago - English",
        "en_TN" => "Tunisia - English",
        "tr_TR" => "Turkey - Turkish",
        "en_TR" => "Turkey - English",
        "en_TM" => "Turkmenistan - English",
        "en_TC" => "Turks and Caicos Islands - English",
        "en_TV" => "Tuvalu - English",
        "en_UG" => "Uganda - English",
        "en_UA" => "Ukraine - English",
        "en_AE" => "United Arab Emirates - English",
        "en_GB" => "United Kingdom - English",
        "fr_GB" => "United Kingdom - French",
        "en_US" => "United States - English",
        "fr_US" => "United States - French",
        "es_US" => "United States - Spanish",
        "zh_US" => "United States - Simplified Chinese",
        "en_UY" => "Uruguay - English",
        "en_VU" => "Vanuatu - English",
        "en_VA" => "Vatican City State - English",
        "en_VE" => "Venezuela - English",
        "en_VN" => "Vietnam - English",
        "en_VG" => "Virgin Islands (British) - English",
        "en_WF" => "Wallis and Futuna Islands - English",
        "en_YE" => "Yemen - English",
        "en_ZM" => "Zambia - English",
        "en_ZW" => "Zimbabwe - English",
        "en_GB" => "International"));

    return array_unique($paypal_button_language);
}