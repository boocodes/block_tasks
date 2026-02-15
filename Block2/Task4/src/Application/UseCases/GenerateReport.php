<?php

namespace Application\UseCases;


use Domain\Repository\OrderRepositoryInterface;
use Domain\Service\ClockInterface;
use Domain\Service\LoggerInterface;
use Application\DTO\GenerateReportRequest;

class GenerateReport
{
    private OrderRepositoryInterface $orderRepository;
    private LoggerInterface $logger;
    private ClockInterface $clock;

    public function __construct(OrderRepositoryInterface $orderRepository,
                                LoggerInterface          $logger,
                                ClockInterface           $clock)
    {
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        $this->clock = $clock;
    }
    public function exec(GenerateReportRequest $request): float
    {
        $this->logger->logMessage("Start");
        $orders = $this->orderRepository->findAll();
        $total = 0;
        foreach ($orders as $order) {
            $total += $order->getPrice();
        }
        $nowData = $this->clock->now();
        $message = 'Total: ' . $total . '. ' . $nowData->format('c');
        $this->logger->logMessage($message);
        return $total;
    }
}