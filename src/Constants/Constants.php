<?php

namespace App\Constants;

class Constants
{

    // EMAILS ADDRESS

    //const EMAIL_CONTACT = 'hrdominguez89@gmail.com';
    // const EMAIL_PRICE_LIST = 'hrdominguez89@gmail.com';


    // END EMAILS ADDRESS


    // EMAIL TYPES

    const EMAIL_TYPE_VALIDATION = 1;
    const EMAIL_TYPE_WELCOME = 2;
    const EMAIL_TYPE_FORGET_PASSWORD = 3;
    const EMAIL_TYPE_PASSWORD_CHANGE_REQUEST = 4;
    const EMAIL_TYPE_PASSWORD_CHANGE_SUCCESSFUL = 5;
    const EMAIL_TYPE_CONTACT = 6;
    const EMAIL_TYPE_PRICE_LIST = 7;
    const EMAIL_TYPE_NEW_ORDER_NOTICE = 8;



    // END EMAIL TYPES

    // STATUS EMAIL

    const EMAIL_STATUS_PENDING = 1;
    const EMAIL_STATUS_ERROR = 2;
    const EMAIL_STATUS_SENT = 3;
    const EMAIL_STATUS_CANCELED = 4;

    // END STATUS EMAIL

    // STATUS CUSTOMER

    const CUSTOMER_STATUS_PENDING = 1;
    const CUSTOMER_STATUS_VALIDATED = 2;
    const CUSTOMER_STATUS_DISABLED = 3;

    // END STATUS CUSTOMER


    // STATUS COMMUNICATION BETWEN PLATFORMS

    const CBP_STATUS_PENDING = 1;
    const CBP_STATUS_ERROR = 2;
    const CBP_STATUS_SENT = 3;

    // END COMMUNICATION BETWEN PLATFORMS


    //REGISTRATION TYPE

    const REGISTRATION_TYPE_WEB = 1;
    const REGISTRATION_TYPE_BACKEND = 2;
    const REGISTRATION_TYPE_IMPORT = 3;

    // END REGISTRATION TYPE

    //STATUS SHOPPING CART TYPE

    const STATUS_SHOPPING_CART_ACTIVO = 1;
    const STATUS_SHOPPING_CART_ELIMINADO = 2;
    const STATUS_SHOPPING_CART_EN_ORDEN = 3;

    // END STATUSSHOPPINGCART TYPE

    //STATUS FAVORITE TYPE

    const STATUS_FAVORITE_ACTIVO = 1;
    const STATUS_FAVORITE_ELIMINADO = 2;
    const STATUS_FAVORITE_EN_CARRITO = 3;

    // END STATUSFAVORITETYPE

    //SPECIFICATION TYPES

    const SPECIFICATION_SCREEN_RESOLUTION = 1;
    const SPECIFICATION_SCREEN_SIZE = 2;
    const SPECIFICATION_CPU = 3;
    const SPECIFICATION_GPU = 4;
    const SPECIFICATION_MEMORY = 5;
    const SPECIFICATION_STORAGE = 6;
    const SPECIFICATION_SO = 7;
    const SPECIFICATION_CONDITUM = 8;
    const SPECIFICATION_COLOR = 9;
    const SPECIFICATION_MODEL = 10;


    //FIN SPECIFICATION TYPES

    //STATUS ORDERS

    const STATUS_ORDER_OPEN = 1;
    const STATUS_ORDER_PARTIALLY_SHIPPED = 2;
    const STATUS_ORDER_SHIPPED = 3;
    const STATUS_ORDER_PICKED = 4;
    const STATUS_ORDER_PACKED = 5;
    const STATUS_ORDER_CONFIRMED = 6;
    const STATUS_ORDER_CANCELED = 7;
    const STATUS_ORDER_PENDING = 8;

    //FIN STATUS ORDERS

    //STATUS TRANSACTIONS

    const STATUS_TRANSACTION_NEW = 1;
    const STATUS_TRANSACTION_CANCELED = 2;
    const STATUS_TRANSACTION_ACCEPTED = 3;
    const STATUS_TRANSACTION_REJECTED = 4;

    //FIN STATUS TRANSACTIONS


    //PAYMENT TYPE

    const PAYMENT_TYPE_TRANSACTION = 1;
    const PAYMENT_TYPE_CARDNET_ONE_PAYMENT = 2;
    const PAYMENT_TYPE_CARDNET_SEVERAL_PAYMENTS = 3;

    //FIN PAYMENT TYPE

    const CARDNET_MESSAGES = [
        '00' => 'Aprobada',
        '01' => 'Llamar al Banco',
        '02' => 'Llamar al Banco',
        '03' => 'Comercio Invalido',
        '04' => 'Rechazada',
        '05' => 'Rechazada',
        '06' => 'Error en Mensaje',
        '07' => 'Tarjeta Rechazada',
        '08' => 'Llamar al Banco',
        '09' => 'Request in progress',
        '10' => 'Aprobación Parcial',
        '11' => 'Approved VIP',
        '12' => 'Transaccion Invalida',
        '13' => 'Monto Invalido',
        '14' => 'Cuenta Invalida',
        '15' => 'No such issuer',
        '16' => 'Approved update track 3',
        '17' => 'Customer cancellation',
        '18' => 'Customer dispute',
        '19' => 'Reintentar Transaccion',
        '20' => 'No tomo accion',
        '21' => 'No tomo acción',
        '22' => 'Transaccion No Aprobada',
        '23' => 'Transaccion No Aceptada',
        '24' => 'File update not supported',
        '25' => 'Unable to locate record',
        '26' => 'Duplicate record',
        '27' => 'File update edit error',
        '28' => 'File update file locked',
        '30' => 'File update failed',
        '31' => 'Bin no soportado',
        '32' => 'Tx. Completada Parcialmente',
        '33' => 'Tarjeta Expirada',
        '34' => 'Transacción No Aprobada',
        '35' => 'Transaccion No Aprobada',
        '36' => 'Transaccion No Aprobada',
        '37' => 'Transaccion No Aprobada',
        '38' => 'Transaccion No Aprobada',
        '39' => 'Tarjeta Invalida',
        '40' => 'Función no Soportada',
        '41' => 'Transacción No Aprobada',
        '42' => 'Cuenta Invalida',
        '43' => 'Transacción No Aprobada',
        '44' => 'No investment account',
        '51' => 'Fondos insuficientes',
        '52' => 'Cuenta Invalidad',
        '53' => 'Cuenta Invalidad',
        '54' => 'Tarjeta vencida',
        '56' => 'Cuenta Invalidad',
        '57' => 'Transaccion no permitida',
        '58' => 'Transaccion no permitida en terminal',
        '60' => 'Contactar Adquirente',
        '61' => 'Excedió Limte de Retiro',
        '62' => 'Tarjeta Restringida',
        '65' => 'Excedió Cantidad de Intento',
        '66' => 'Contactar Adquirente',
        '67' => 'Hard capture',
        '68' => 'Response received too late',
        '75' => 'Pin excedio Limte de Intentos',
        '77' => 'Captura de Lote Invalida',
        '78' => 'Intervención del Banco Requerida',
        '79' => 'Rechazada',
        '81' => 'Pin invalido',
        '82' => 'PIN Required',
        '85' => 'Llaves no disponibles',
        '89' => 'Terminal Invalida',
        '90' => 'Cierre en proceso',
        '91' => 'Host No Disponible',
        '92' => 'Error de Ruteo',
        '94' => 'Duplicate Transaction',
        '95' => 'Error de Reconciliación',
        '96' => 'Error de Sistema',
        '97' => 'Emisor no Disponible',
        '98' => 'Excede Limite de Efectivo',
        '99' => 'CVV or CVC Error response',
        'TF' => 'Solicitud de autenticación rechazada o no completada',
    ];
}
