<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductionRootDeploymentTest extends TestCase
{
    public function test_production_environment_targets_the_subdomain_root(): void
    {
        $environment = file_get_contents(base_path('.env.production.example'));

        $this->assertStringContainsString('APP_URL=https://test-node.nicebox-sa.com'.PHP_EOL, $environment);
        $this->assertStringContainsString('ASSET_URL=https://test-node.nicebox-sa.com'.PHP_EOL, $environment);
        $this->assertStringContainsString('SESSION_PATH=/'.PHP_EOL, $environment);
        $this->assertStringNotContainsString('/booking', $environment);
    }

    public function test_deployment_script_only_accepts_the_expected_subdomain_public_root(): void
    {
        $script = file_get_contents(base_path('deploy/hostinger-release.sh'));

        $this->assertStringContainsString('*/domains/test-node.nicebox-sa.com/public_html)', $script);
        $this->assertStringNotContainsString('public_html/booking', $script);
    }
}
