<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms;

use Spryker\Zed\Availability\Communication\Plugin\AvailabilityHandlerPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Command\CreateGiftCardCommandPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Command\ReplaceGiftCardsCommandPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Condition\IsGiftCardConditionPlugin;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Oms\Command\ShipGiftCardByEmailCommandPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderConfirmationPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderShippedPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider as SprykerOmsDependencyProvider;
use Spryker\Zed\ProductBundle\Communication\Plugin\Oms\ProductBundleAvailabilityHandlerPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\AuthorizeCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CancelCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CaptureCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CaptureWithSettlementCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\PreAuthorizeCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\RefundCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsRedirectConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\CaptureIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsAppointedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsCaptureConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsOverpaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsPaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsRefundConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsUnderPaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsRedirectConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\RefundIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\RefundIsPossibleConditionPlugin;

class OmsDependencyProvider extends SprykerOmsDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->extendCommandPlugins($container);
        $container = $this->extendConditionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected function getReservationHandlerPlugins(Container $container)
    {
        return [
            new AvailabilityHandlerPlugin(),
            new ProductBundleAvailabilityHandlerPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendCommandPlugins(Container $container)
    {
        $container->extend(self::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection->add(new SendOrderConfirmationPlugin(), 'Oms/SendOrderConfirmation');
            $commandCollection->add(new SendOrderShippedPlugin(), 'Oms/SendOrderShipped');
            $commandCollection->add(new ShipGiftCardByEmailCommandPlugin(), 'GiftCardMailConnector/ShipGiftCard');
            $commandCollection->add(new CreateGiftCardCommandPlugin(), 'GiftCard/CreateGiftCard');
            $commandCollection->add(new ReplaceGiftCardsCommandPlugin(), 'GiftCard/ReplaceGiftCards');
            $commandCollection->add(new PreAuthorizeCommandPlugin(), 'Payone/PreAuthorize');
            $commandCollection->add(new AuthorizeCommandPlugin(), 'Payone/Authorize');
            $commandCollection->add(new CancelCommandPlugin(), 'Payone/Cancel');
            $commandCollection->add(new CaptureCommandPlugin(), 'Payone/Capture');
            $commandCollection->add(new CaptureWithSettlementCommandPlugin(), 'Payone/CaptureWithSettlement');
            $commandCollection->add(new RefundCommandPlugin(), 'Payone/Refund');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendConditionPlugins(Container $container)
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsGiftCardConditionPlugin(), 'GiftCard/IsGiftCard');
            $conditionCollection->add(new PreAuthorizationIsApprovedConditionPlugin(), 'Payone/PreAuthorizationIsApproved');
            $conditionCollection->add(new AuthorizationIsApprovedConditionPlugin(), 'Payone/AuthorizationIsApproved');
            $conditionCollection->add(new CaptureIsApprovedConditionPlugin(), 'Payone/CaptureIsApproved');
            $conditionCollection->add(new RefundIsApprovedConditionPlugin(), 'Payone/RefundIsApproved');
            $conditionCollection->add(new RefundIsPossibleConditionPlugin(), 'Payone/RefundIsPossible');
            $conditionCollection->add(new PreAuthorizationIsErrorConditionPlugin(), 'Payone/PreAuthorizationIsError');
            $conditionCollection->add(new AuthorizationIsErrorConditionPlugin(), 'Payone/AuthorizationIsError');
            $conditionCollection->add(new PreAuthorizationIsRedirectConditionPlugin(), 'Payone/PreAuthorizationIsRedirect');
            $conditionCollection->add(new AuthorizationIsRedirectConditionPlugin(), 'Payone/AuthorizationIsRedirect');
            $conditionCollection->add(new PaymentIsAppointedConditionPlugin(), 'Payone/PaymentIsAppointed');
            $conditionCollection->add(new PaymentIsCaptureConditionPlugin(), 'Payone/PaymentIsCapture');
            $conditionCollection->add(new PaymentIsPaidConditionPlugin(), 'Payone/PaymentIsPaid');
            $conditionCollection->add(new PaymentIsUnderPaidConditionPlugin(), 'Payone/PaymentIsUnderPaid');
            $conditionCollection->add(new PaymentIsOverpaidConditionPlugin(), 'Payone/PaymentIsOverpaid');
            $conditionCollection->add(new PaymentIsRefundConditionPlugin(), 'Payone/PaymentIsRefund');
            return $conditionCollection;
        });

        return $container;
    }
}
