<?php

namespace Tests\Feature\Services;

use App\Services\GitHubService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GitHubServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private GitHubService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GitHubService('https://api.github.com', 'fake_token');
    }

    public function testGetUserRepositoriesIntegration()
    {
        // Очищаем кеш перед тестом
        Cache::flush();

        // Подменяем HTTP-запрос, но в реальном проекте можно тестировать без подмены
        Http::fake([
            'https://api.github.com/users/testuser/repos' => Http::response([
                ['name' => 'repo1'], ['name' => 'repo2']
            ], 200)
        ]);

        // Запросим репозитории (должны кешироваться)
        $repos = $this->service->getUserRepositories('testuser');

        // Проверяем, что данные получены
        $this->assertIsArray($repos);
        $this->assertCount(2, $repos);

        // Проверяем, что кеширование сработало
        $cachedData = Cache::get("github_repositories_testuser");
        $this->assertNotNull($cachedData);
        $this->assertCount(2, $cachedData);
    }
}
