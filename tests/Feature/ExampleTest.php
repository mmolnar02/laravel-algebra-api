<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected string $apiPrefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiPrefix = env('APP_API_PREFIX', 'api/v1');
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get($this->apiPrefix . '/');

        $response->assertStatus(200);
    }
}
