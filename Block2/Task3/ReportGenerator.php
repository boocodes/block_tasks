<?php

require_once './OrderInterfaces.php';


class ReportGenerator
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private LoggerInterface $logger,
        private ClockInterface $clock,
    )
    {}

    public function createReport(): float
    {
        $this->logger->logMessage("Report: \n");
        $ordersData = $this->orderRepository->getOrder();
        $totalAmount = 0;
        foreach ($ordersData as $order)
        {
            $totalAmount += $order['price'];
        }
        $now = $this->clock->nowDate();
        $this->logger->logMessage('Total amount of orders: - ' . $totalAmount . ' - '. $now->format('c') . '\n');
        return $totalAmount;
    }

}
