<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Checkout\Process\Steps;

use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCancelRedirectTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\Customer\CustomerClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Yves\Payone\Handler\PayoneHandler;
use Symfony\Component\HttpFoundation\Request;

class SuccessStep extends AbstractBaseStep implements PayoneClientInterface
{
    /**
     * @var \Pyz\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;


    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Pyz\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct(
        CustomerClientInterface $customerClient,
        PayoneClientInterface $payoneClient,
        $stepRoute,
        $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->customerClient = $customerClient;
        $this->stepRoute = $stepRoute;
        $this->payoneClient = $payoneClient;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return true;
    }

    /**
     * Empty quote transfer and mark logged in customer as "dirty" to force update it in the next request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $this->customerClient->markCustomerAsDirty();

        if (method_exists($quoteTransfer->getPayment(), 'getPayone')) {
            $this->quoteTransfer = $quoteTransfer;
        }

        return new QuoteTransfer();
    }

    public function getTemplateVariables(AbstractTransfer $dataTransfer)
    {
        $getPaymentDetailTransfer = new PayoneGetPaymentDetailTransfer();
        if ($this->quoteTransfer->getPayment()->getPaymentProvider() === PayoneHandler::PAYMENT_PROVIDER) {
            $getPaymentDetailTransfer->setOrderReference($this->quoteTransfer->getOrderReference());
            $getPaymentDetailTransfer = $this->payoneClient->getPaymentDetail($getPaymentDetailTransfer);
        }
        return [
            'quoteTransfer' => $this->quoteTransfer,
            'paymentDetail' => $getPaymentDetailTransfer->getPaymentDetail(),
        ];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getOrderReference() === null) {
            return false;
        }

        return true;
    }

    /**
     * Prepares credit card check request to bring standard parameters and hash to front-end.
     *
     * @api
     *
     * @return \SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainer
     */
    public function getCreditCardCheckRequest()
    {
        // TODO: Implement getCreditCardCheckRequest() method.
    }

    /**
     * Processes and saves transaction status update received from Payone.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $statusUpdateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function updateStatus(PayoneTransactionStatusUpdateTransfer $statusUpdateTransfer)
    {
        // TODO: Implement updateStatus() method.
    }

    /**
     * Performs GetFile request to Payone API for PDF file download.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer)
    {
        // TODO: Implement getFile() method.
    }

    /**
     * Performs GetInvoice request to Payone API for PDF file download.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer)
    {
        // TODO: Implement getInvoice() method.
    }

    /**
     * Fetches payment details for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer
     */
    public function getPaymentDetail(PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer)
    {
        // TODO: Implement getPaymentDetail() method.
    }

    /**
     * Verifies url HMAC signature and fires 'cancel redirect' event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $cancelRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneCancelRedirectTransfer
     */
    public function cancelRedirect(PayoneCancelRedirectTransfer $cancelRedirectTransfer)
    {
        // TODO: Implement cancelRedirect() method.
    }

    /**
     * Performs BankAccountCheck request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayonePaymentDirectDebitTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer)
    {
        // TODO: Implement bankAccountCheck() method.
    }

    /**
     * Performs ManageMandate request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(QuoteTransfer $quoteTransfer)
    {
        // TODO: Implement manageMandate() method.
    }

    /**
     * Send start paypal express checkout to payone.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer)
    {
        // TODO: Implement initPaypalExpressCheckout() method.
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer)
    {
        // TODO: Implement getPaypalExpressCheckoutDetails() method.
    }
}
