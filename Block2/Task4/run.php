    <?php

    use Application\UseCases\GenerateReport;
    use Application\Dto\GenerateReportRequest;
    use Domain\Container\OrderContainer;
    use Infrastructure\Repository\FileOrderRepository;
    use Infrastructure\Service\ServiceClock;
    use Infrastructure\Service\ConsoleLoggerService;
    use Domain\Repository\OrderRepositoryInterface;
    use Domain\Service\ClockInterface;
    use Domain\Service\LoggerInterface;

    spl_autoload_register(function ($class) {
        $prefixes = [
            'Application\\' => __DIR__ . '/src/Application/',
            'Domain\\' => __DIR__ . '/src/Domain/',
            'Infrastructure\\' => __DIR__ . '/src/Infrastructure/',
        ];

        foreach ($prefixes as $prefix => $base_dir) {
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) === 0) {
                $relative_class = substr($class, $len);
                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        }
    });

    $container = new OrderContainer();

    try {
        $container->singleton(FileOrderRepository::class, function () {
            return new FileOrderRepository();
        });

        $container->set(ServiceClock::class, function () {
            return new ServiceClock();
        });

        $container->singleton(ConsoleLoggerService::class, function () {
            return new ConsoleLoggerService();
        });

        $container->set(OrderRepositoryInterface::class, function ($container) {
            return $container->get(FileOrderRepository::class);
        });

        $container->set(ClockInterface::class, function ($container) {
            return $container->get(ServiceClock::class);
        });

        $container->set(LoggerInterface::class, function ($container) {
            return $container->get(ConsoleLoggerService::class);
        });

        $container->set(GenerateReport::class, function ($container) {
            return new GenerateReport(
                $container->get(OrderRepositoryInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(ClockInterface::class)
            );
        });

        $dataOrder = [
            ['name' => 'rice', 'price' => 150.00],
            ['name' => 'melon', 'price' => 200.00],
            ['name' => 'apple', 'price' => 240.00]
        ];

        $repository = $container->get(OrderRepositoryInterface::class);

        foreach ($dataOrder as $orderItem) {
            $orderElem = new \Domain\ValueObject\OrderElem(
                $orderItem['name'],
                uniqid(),
                $orderItem['price']
            );
            $order = new \Domain\Entity\Order($orderElem);
            $repository->save($order);
        }

        $reportGenerator = $container->get(GenerateReport::class);
        $request = new GenerateReportRequest();
        $report = $reportGenerator->exec($request);
        echo 'Total from report: ' . $report . PHP_EOL;

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
        echo $e->getTraceAsString();
    }