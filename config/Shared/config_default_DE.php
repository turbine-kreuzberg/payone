<?php

use Pyz\Shared\Newsletter\NewsletterConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Zed\Oms\OmsConfig;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\PayoneConfig;

$domain = getenv('VM_PROJECT') ?: 'demoshop-nonsplit';

// ---------- Yves host
$config[ApplicationConstants::HOST_YVES] = 'www.de.demoshop-nonsplit.local';
$config[ApplicationConstants::PORT_YVES] = '';
$config[ApplicationConstants::PORT_SSL_YVES] = '';
$config[ApplicationConstants::BASE_URL_YVES] = sprintf(
    'http://%s%s',
    $config[ApplicationConstants::HOST_YVES],
    $config[ApplicationConstants::PORT_YVES]
);
$config[ApplicationConstants::BASE_URL_SSL_YVES] = sprintf(
    'https://%s%s',
    $config[ApplicationConstants::HOST_YVES],
    $config[ApplicationConstants::PORT_SSL_YVES]
);
$config[ProductManagementConstants::BASE_URL_YVES] = $config[ApplicationConstants::BASE_URL_YVES];
$config[CustomerConstants::BASE_URL_YVES] = $config[ApplicationConstants::BASE_URL_YVES];
// ---------- Zed host
$config[ApplicationConstants::HOST_ZED] = 'zed.de.demoshop-nonsplit.local';
$config[ApplicationConstants::PORT_ZED] = '';
$config[ApplicationConstants::PORT_SSL_ZED] = '';
$config[ApplicationConstants::BASE_URL_ZED] = sprintf(
    'http://%s%s',
    $config[ApplicationConstants::HOST_ZED],
    $config[ApplicationConstants::PORT_ZED]
);
$config[ApplicationConstants::BASE_URL_SSL_ZED] = sprintf(
    'https://%s%s',
    $config[ApplicationConstants::HOST_ZED],
    $config[ApplicationConstants::PORT_SSL_ZED]
);
$config[ZedRequestConstants::HOST_ZED_API] = $config[ApplicationConstants::HOST_ZED];
$config[ZedRequestConstants::BASE_URL_ZED_API] = $config[ApplicationConstants::BASE_URL_ZED];
$config[ZedRequestConstants::BASE_URL_SSL_ZED_API] = $config[ApplicationConstants::BASE_URL_SSL_ZED];
// ---------- Assets / Media
$config[ApplicationConstants::BASE_URL_STATIC_ASSETS] = $config[ApplicationConstants::BASE_URL_YVES];
$config[ApplicationConstants::BASE_URL_STATIC_MEDIA] = $config[ApplicationConstants::BASE_URL_YVES];
$config[ApplicationConstants::BASE_URL_SSL_STATIC_ASSETS] = $config[ApplicationConstants::BASE_URL_SSL_YVES];
$config[ApplicationConstants::BASE_URL_SSL_STATIC_MEDIA] = $config[ApplicationConstants::BASE_URL_SSL_YVES];
// ---------- Session
$config[SessionConstants::YVES_SESSION_COOKIE_NAME] = $config[ApplicationConstants::HOST_YVES];
$config[SessionConstants::YVES_SESSION_COOKIE_DOMAIN] = $config[ApplicationConstants::HOST_YVES];
$config[SessionConstants::ZED_SESSION_COOKIE_NAME] = $config[ApplicationConstants::HOST_ZED];
// ---------- Events
$config[EventConstants::LOG_FILE_PATH] = APPLICATION_ROOT_DIR . '/data/DE/logs/application_events.log';
// ---------- Payone
$config[PayoneConstants::PAYONE] = [
    PayoneConstants::PAYONE_CREDENTIALS_ENCODING => 'UTF-8',
    PayoneConstants::PAYONE_CREDENTIALS_KEY => '6R7j3031dPSUvI85',
    PayoneConstants::PAYONE_CREDENTIALS_MID => '32481',
    PayoneConstants::PAYONE_CREDENTIALS_AID => '32893',
    PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID => '2030040',
    PayoneConstants::PAYONE_PAYMENT_GATEWAY_URL => 'https://api.pay1.de/post-gateway/',
    PayoneConstants::PAYONE_REDIRECT_SUCCESS_URL => sprintf(
        '%s/checkout/success',
        $config[ApplicationConstants::BASE_URL_YVES]
    ),
    PayoneConstants::PAYONE_REDIRECT_ERROR_URL => sprintf(
        '%s/checkout/payment',
        $config[ApplicationConstants::BASE_URL_YVES]
    ),
    PayoneConstants::PAYONE_REDIRECT_BACK_URL => sprintf(
        '%s/checkout/regular-redirect-payment-cancellation',
        $config[ApplicationConstants::BASE_URL_YVES]
    ),
    PayoneConstants::PAYONE_MODE => 'test',
    PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER => 0,
    PayoneConstants::HOST_YVES => $config[ApplicationConstants::BASE_URL_YVES],
];
$config[PayoneConstants::PAYONE][PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL] = sprintf(
    '%s/checkout/paypal-express-checkout-entry-point',
    $config[ApplicationConstants::BASE_URL_YVES]
);
$config[PayoneConstants::PAYONE][PayoneConstants::PAYONE_EXPRESS_CHECKOUT_BACK_URL] = sprintf(
    '%s/cart',
    $config[ApplicationConstants::BASE_URL_YVES]
);
$config[PayoneConstants::PAYONE][PayoneConstants::PAYONE_EXPRESS_CHECKOUT_FAILURE_URL] = sprintf(
    '%s/cart',
    $config[ApplicationConstants::BASE_URL_YVES]
);
$config[OmsConstants::PROCESS_LOCATION] = [
    OmsConfig::DEFAULT_PROCESS_LOCATION,
//    $config[KernelConstants::SPRYKER_ROOT] . '/DummyPayment/config/Zed/Oms',
    APPLICATION_VENDOR_DIR . '/spryker-eco/payone/config/Zed/Oms',
];
$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    PayoneConfig::PAYMENT_METHOD_CREDIT_CARD => 'PayoneCreditCard',
    PayoneConfig::PAYMENT_METHOD_E_WALLET => 'PayoneEWallet',
    PayoneConfig::PAYMENT_METHOD_DIRECT_DEBIT => 'PayoneDirectDebit',
    PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_INSTANT_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_GIROPAY_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_IDEAL_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_POSTFINANCE_CARD_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_POSTFINANCE_EFINANCE_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_PRZELEWY24_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_BANCONTACT_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT => 'PayonePrePayment',
    PayoneConfig::PAYMENT_METHOD_INVOICE => 'PayoneInvoice',
    PayoneConfig::PAYMENT_METHOD_SECURITY_INVOICE => 'PayoneSecurityInvoice',
    PayoneConfig::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT => 'PayonePaypalExpressCheckout',
    DummyPaymentConfig::PAYMENT_METHOD_INVOICE => 'DummyPayment01',
    DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD => 'DummyPayment01',
    PayoneConfig::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT => 'PayonePaypalExpressCheckout'
];
$config[OmsConstants::ACTIVE_PROCESSES] = [
    'PayoneCreditCard',
    'PayoneEWallet',
    'PayoneDirectDebit',
    'PayoneOnlineTransfer',
    'PayonePrePayment',
    'PayoneInvoice',
    'PayonePaypalExpressCheckout',
    'PayoneSecurityInvoice',
    'PayonePaypalExpressCheckout'
];