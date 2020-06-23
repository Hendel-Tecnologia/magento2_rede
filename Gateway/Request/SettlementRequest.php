<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Rede\Adquirencia\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Rede\Adquirencia\Gateway\Helper\SubjectReader;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\InvoiceRepositoryInterface;
class SettlementRequest implements BuilderInterface
{
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @param ConfigInterface $config
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        ConfigInterface $config,
        SubjectReader $subjectReader,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        InvoiceRepositoryInterface $invoiceRepository
    )
    {
        $this->config = $config;
        $this->subjectReader = $subjectReader;
        $this->invoiceRepository = $invoiceRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $order = $paymentDO->getOrder();

        $payment = $paymentDO->getPayment();

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided.');
        }

        $amount = $this->getInvoiceGrandTotalByOrderId($order->getId());

        if (!$amount) {
            $amount = $order->getGrandTotalAmount();
        }

        return [
            'AMOUNT' => $amount,
            'TID' => $payment->getAdditionalInformation('Id Transação')
        ];
    }

    /**
     * Get Invoice Grand Total by Order Id
     *
     * @param int $orderId
     * @return float
     */
    private function getInvoiceGrandTotalByOrderId(int $orderId)
    {
        $total = 0;
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $orderId)->create();
        try {
            $invoices = $this->invoiceRepository->getList($searchCriteria);
            foreach ($invoices->getItems() as $invoice) {
                $total += $invoice->getGrandTotal();
            }
        } catch (\Exception $exception) {
            $total = 0;
        }
        return $total;
    }
}
