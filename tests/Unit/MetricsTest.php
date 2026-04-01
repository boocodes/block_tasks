<?php

namespace Tests7\Feature;

use Final7\App\Models\Metrics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;

class MetricsTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $metricsListBefore = Metrics::query()->get();

        $response = $this->getJson('/api/health');

        $response->assertStatus(200);

        $metricsListAfter = Metrics::query()->get();

        $this->assertTrue($metricsListBefore->count() < $metricsListAfter->count());

    }
}
