<?php

namespace Tests\Unit\Services;

use App\Services\GitHubService;
use App\Exception\GitHubException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GitHubServiceTest extends TestCase
{
    private GitHubService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GitHubService('https://api.github.com', 'fake_token');
    }

    public function testGetUserRepositoriesSuccess()
    {
        Http::fake([
            'https://api.github.com/users/testuser/repos' => Http::response([
                ['name' => 'repo1'], ['name' => 'repo2']
            ], 200)
        ]);

        Cache::shouldReceive('remember')->once()->andReturn([['name' => 'repo1'], ['name' => 'repo2']]);

        $repos = $this->service->getUserRepositories('testuser');

        $this->assertIsArray($repos);
        $this->assertCount(2, $repos);
    }

    public function testGetUserRepositoriesFailedRequest()
    {
        Http::fake([
            'https://api.github.com/users/testuser/repos' => Http::response([], 500)
        ]);

        $this->expectException(GitHubException::class);
        $this->expectExceptionMessage('Ошибка при запросе к GitHub API');

        $this->service->getUserRepositories('testuser');
    }
}
