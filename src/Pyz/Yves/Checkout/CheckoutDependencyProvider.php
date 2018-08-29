<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Checkout;

use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Yves\Customer\Plugin\CustomerStepHandler;
use Pyz\Yves\Discount\Handler\VoucherCodeHandler;
use Pyz\Yves\GiftCard\Cart\Plugin\GiftCardCodeHandler;
use Pyz\Yves\Shipment\Plugin\ShipmentFormDataProviderPlugin;
use Pyz\Yves\Shipment\Plugin\ShipmentHandlerPlugin;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Yves\Checkout\CheckoutDependencyProvider as SprykerCheckoutDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\Nopayment\Plugin\NopaymentHandlerPlugin;
use Spryker\Yves\Payment\Plugin\PaymentFormFilterPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneDirectDebitSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneEpsOnlineTransferSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneEWalletSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneHandlerPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneInvoiceSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayonePrePaymentSubFormPlugin;

class CheckoutDependencyProvider extends SprykerCheckoutDependencyProvider
{
    const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    const CLIENT_CHECKOUT = 'CLIENT_CHECKOUT';
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    const CLIENT_CART = 'CLIENT_CART';
    const CLIENT_PAYMENT = 'CLIENT_PAYMENT';
    const CLIENT_PAYONE = 'CLIENT_PAYONE';
    const STORE = 'STORE';
    const CLIENT_OFFER = 'CLIENT_OFFER';

    const SERVICE_UTIL_VALIDATE = 'SERVICE_UTIL_VALIDATE';

    const PLUGIN_CUSTOMER_STEP_HANDLER = 'PLUGIN_CUSTOMER_STEP_HANDLER';
    const PLUGIN_SHIPMENT_STEP_HANDLER = 'PLUGIN_SHIPMENT_STEP_HANDLER';
    const PLUGIN_SHIPMENT_HANDLER = 'PLUGIN_SHIPMENT_HANDLER';
    const PLUGIN_SHIPMENT_FORM_DATA_PROVIDER = 'PLUGIN_SHIPMENT_FORM_DATA_PROVIDER';
    const CODE_HANDLER_PLUGINS = 'CODE_HANDLER_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->provideClients($container);
        $container = $this->providePlugins($container);
        $container = $this->provideStore($container);
        $container = $this->addUtilValidateService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilValidateService(Container $container)
    {
        $container[static::SERVICE_UTIL_VALIDATE] = function (Container $container) {
            return $container->getLocator()->utilValidate()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideClients(Container $container)
    {
        $container = parent::provideClients($container);

        $container[static::CLIENT_CALCULATION] = function (Container $container) {
            return $container->getLocator()->calculation()->client();
        };

        $container[static::CLIENT_CHECKOUT] = function (Container $container) {
            return $container->getLocator()->checkout()->client();
        };

        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return $container->getLocator()->customer()->client();
        };

        $container[static::CLIENT_CART] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };

        $container[static::CLIENT_PAYMENT] = function (Container $container) {
            return $container->getLocator()->payment()->client();
        };

        $container[static::CLIENT_OFFER] = function (Container $container) {
            return $container->getLocator()->offer()->client();
        };

        $container[static::CLIENT_PAYONE] = function (Container $container) {
            return $container->getLocator()->payone()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function providePlugins(Container $container)
    {
        $container = parent::providePlugins($container);

        $container[static::PLUGIN_CUSTOMER_STEP_HANDLER] = function () {
            return new CustomerStepHandler();
        };

        $container[static::PLUGIN_SHIPMENT_HANDLER] = function () {
            $shipmentHandlerPlugins = new StepHandlerPluginCollection();
            $shipmentHandlerPlugins->add(new ShipmentHandlerPlugin(), static::PLUGIN_SHIPMENT_STEP_HANDLER);

            return $shipmentHandlerPlugins;
        };

        $container[static::PLUGIN_SHIPMENT_FORM_DATA_PROVIDER] = function () {
            return new ShipmentFormDataProviderPlugin();
        };

        $container[static::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        $container = $this->provideCodeHandlePlugins($container);
        $container = $this->extendPaymentMethodHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideCodeHandlePlugins(Container $container)
    {
        $container[static::CODE_HANDLER_PLUGINS] = function () {
            return [
                new VoucherCodeHandler(),
                new GiftCardCodeHandler(),
            ];
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendPaymentMethodHandler(Container $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $paymentMethodHandler->add(new NopaymentHandlerPlugin(), NopaymentConfig::PAYMENT_PROVIDER_NAME);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_INVOICE);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_CREDIT_CARD);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_DIRECT_DEBIT);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_E_WALLET);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_ONLINE_TRANSFER);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_PRE_PAYMENT);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_PAYPAL_EXPRESS_CHECKOUT);

            return $paymentMethodHandler;
        });

        $container[static::PAYMENT_SUB_FORMS] = function () {
            $paymentSubFormPlugin = new SubFormPluginCollection();

            $paymentSubFormPlugin->add(new PayoneInvoiceSubFormPlugin());
            $paymentSubFormPlugin->add(new PayoneCreditCardSubFormPlugin());
            $paymentSubFormPlugin->add(new PayoneDirectDebitSubFormPlugin());
            $paymentSubFormPlugin->add(new PayoneEWalletSubFormPlugin());
            $paymentSubFormPlugin->add(new PayoneEpsOnlineTransferSubFormPlugin());
            $paymentSubFormPlugin->add(new PayonePrePaymentSubFormPlugin());

            return $paymentSubFormPlugin;
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected function getPaymentFormFilterPlugins()
    {
        return [
            new PaymentFormFilterPlugin(),
        ];
    }
}
