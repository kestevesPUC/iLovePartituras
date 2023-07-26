<?php

namespace App\Helpers\Constants;

class ContractConstants
{
    //ID's
    public const LINK = 948;
    public const LOOK = 1480;
    public const KTECH = 2233;

    // CNPJ
    public const CNPJ_LINK = '11508222000136';
    public const CNPJ_LOOK = '31171733000112';

    public const PERSON_LINK = 41353;
    public const PERSON_LOOK = 440061;
    public const PERSON_RENEWAL_ONLINE = 947388;

    public const USER_LINK = 1301;

    //GROUPS
    public const GROUP_ADMIN = 1;
    public const GROUP_FINANCIAL = 2;
    public const GROUP_AGR = 3;
    public const GROUP_REPRESENTATION = 4;
    public const GROUP_TRAINING = 5;
    public const GROUP_COMMERCIAL = 7;
    public const GROUP_REPRESENTATION_OPERATOR = 8;
    public const GROUP_SUPPORT = 9;
    public const GROUP_ACI = 10;
    public const GROUP_TI = 11;
    public const GROUP_MARKETING = 12;
    public const GROUP_COMPLIANCE = 13;
    public const GROUP_COMPLIANCE_EXTERNAL = 17;
    public const GROUP_COMMERCIAL_AR = 18;
    public const GROUP_COMMERCIAL_LEADER = 24;

    //GROUP KRYPTON
    public const GROUP_KRYPTON = [
        self::GROUP_ADMIN,
        self::GROUP_FINANCIAL,
        self::GROUP_COMMERCIAL,
        self::GROUP_SUPPORT,
        self::GROUP_ACI,
        self::GROUP_TI,
        self::GROUP_MARKETING,
        self::GROUP_COMPLIANCE,
        self::GROUP_COMMERCIAL_AR,
        self::GROUP_COMMERCIAL_LEADER
    ];

    public const LINK_TEAM = [
        self::GROUP_ADMIN,
        self::GROUP_FINANCIAL,
        self::GROUP_TRAINING,
        self::GROUP_COMMERCIAL,
        self::GROUP_SUPPORT,
        self::GROUP_ACI,
        self::GROUP_TI,
        self::GROUP_MARKETING,
        self::GROUP_COMPLIANCE,
        self::GROUP_COMMERCIAL_AR,
        self::GROUP_COMMERCIAL_LEADER
    ];
    public const LINK_ADVANCED_GROUP = [
        self::GROUP_ADMIN,
        self::GROUP_FINANCIAL,
        self::GROUP_COMMERCIAL,
        self::GROUP_TI,
        self::GROUP_COMPLIANCE,
        self::GROUP_COMMERCIAL_AR,
        self::GROUP_COMMERCIAL_LEADER
    ];
    public const COMMERCIAL_MANAGEMENT = [self::GROUP_ADMIN, self::GROUP_COMMERCIAL_LEADER];
    public const COMMERCIAL_TEAM = [self::GROUP_ADMIN, self::GROUP_COMMERCIAL, self::GROUP_COMMERCIAL_AR, self::GROUP_COMMERCIAL_LEADER];
    public const COMMERCIAL_ARS_TEAM = [self::GROUP_ADMIN, self::GROUP_COMMERCIAL_AR, self::GROUP_COMMERCIAL_LEADER, self::GROUP_REPRESENTATION];

    public const AGR_LINK = [
        902, // THAMIRES
        1027, // HELLEN
        1163, // TALIELE
        1208, // BRUNO
        2208, // YOLANDA
        3235, // BRENDA
        4662, // DANIELLE
        5312, // BRUNA
        6034, // WASHINGTON
        6586, // MARIA
    ];

    //TYPES
    public const TYPE_DISTRIBUTOR = 1;
    public const TYPE_AR = 2;
    public const TYPE_PRE_PAID = 3;
    public const TYPE_REPRESENTATION = 4;

    //PAYMENY TYPE
    public const PAYMENT_POS_PAID = 1;
    public const PAYMENT_PRE_PAID = 2;

    //CAMPAIGN
    public const A1_CAMPAIGN = 1;
    public const DISCOUNT_CAMPAIGN = 2;
    public const CONSUMPTION_CAMPAIGN = 3;
    public const SYSTEM_CAMPAIGN = 4;

    //PAYMENT_OPTIONS
    public const PAYMENT_OPTION_ASER = 119;
    public const PAYMENT_OPTION_ITAU_LINK = 1;
    public const PAYMENT_OPTION_ITAU_LOOK = 167;
    public const PAYMENT_OPTION_KRYPTON_PAY_LINK = 202;
    public const PAYMENT_OPTION_KRYPTON_PAY_LOOK = 541;
    public const PAYMENT_OPTION_KRYPTON_PAY_KTECH = 705;

    // KRYPTON PAY APPLICATIONS
    public const RENEWAL_APPLICATION = 8;
    public const MARKET_RESEARCH_APPLICATION = 579;
}
