<?php


require_once './Services.php';
require_once './OrderContainer.php';
require_once './OrderInterfaces.php';
require_once './ReportGenerator.php';


$container = new OrderContainer();

try {

    $container->singleton(OrderRepositoryInterface::class, function () {
        return new OrderRepositoryService();
    });

    $container->set(ClockInterface::class, function () {
        return new ClockService();
    });

    $container->singleton(LoggerInterface::class, function () {
        return new LoggerService();
    });

    $container->set(ReportGenerator::class, function ($container) {
        return new ReportGenerator(
            $container->get(OrderRepositoryInterface::class),
            $container->get(LoggerInterface::class),
            $container->get(ClockInterface::class),
        );
    });

    // fill order data something
    $dataOrder = [
        ['name' => 'rice', 'price' => 150.00],
        ['name' => 'melon', 'price' => 151.00],
        ['name' => 'apple', 'price' => 150.00],
    ];
    $container->get(OrderRepositoryInterface::class)->setOrder($dataOrder);
    $reportGenerator = $container->get(ReportGenerator::class);
    $report = $reportGenerator->createReport();

    echo $report;
} catch (BadMethodCallException $e) {
    echo $e->getMessage();
}