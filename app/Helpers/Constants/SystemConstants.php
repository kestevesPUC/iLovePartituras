<?php namespace App\Helpers\Constants;

class SystemConstants
{
    //System User
    const USER_SYSTEM = 1;

    //Type method transaction
    const BANK_SLIP = 1;
    const CREDIT_CARD = 2;
    const PIX = 4;

    //Type Person
    const TYPE_PERSON_CPF = 1;
    const TYPE_PERSON_CNPJ = 2;

    //Status payment
    const ID_STATUS_TRANSACTION_PENDING = 1;
    const ID_STATUS_TRANSACTION_PAID = 2;
    const ID_STATUS_TRANSACTION_PARTIAL_PAID = 3;
    const ID_STATUS_TRANSACTION_DECLINED = 4;
    const ID_STATUS_TRANSACTION_CANCELED = 5;
    const ID_STATUS_TRANSACTION_CONFIRMED = 6;
    const ID_STATUS_TRANSACTION_AUTHORIZED = 7;
    const ID_STATUS_TRANSACTION_DENIED = 8;
    const ID_STATUS_TRANSACTION_APPROVED = 9;
    const ID_STATUS_TRANSACTION_FINISHED = 10;

    //Order low
    const NORMAL = 1;
    const EMPLOYEE = 2;
    const CONTINGENCY = 3;
    const BONUS = 4;
    const EXTERIOR_PROJECT = 5;

    //Status NFse
    const WAITING_TO_SENT = 1;
    const SEND = 2;
    const UN_PROCESSED = 3;
    const PROCESSED = 4;
    const WAITING_FOR_RESEND = 5;
    const WITH_ERROR = 6;

    //NFS-e
    const ALIQUOT = 2.5;
    const COUNTY_BELO_HORIZONTE = 3106200;

    //NFs-e actions
    const CANCEL = 1;
    const DOWLOAD_XML = 2;
    const DOWLOAD_NFSE = 3;
    const VIEW_NFSE = 4;
    const MODAL_SEND_EMAIL = 5;
    const SEND_EMAIL = 6;

    //TYPE USER
    const NOT_ADMIN = 0;
    const MASTER_ADMIN = 1;
    const OWN_SYSTEM_ADMIN = 2;

    //Emission
    const SYSTEM_LINK = 2;
}
