<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected $apiUrl;
    protected $apiToken;


    public function __construct()
    {
        $this->apiUrl = config('services.github.url');
        $this->apiToken = config('services.github.token');
    }

    public function getUserRepositories(string $username): ?array
    {
        $cacheKey = "github_repositories_{$username}";


        // Кешируем ответ GitHub API на 1ч
        return Cache::remember($cacheKey, 3600, function () use ($username) {
            $response = Http::withToken($this->apiToken)
                ->get("{$this->apiUrl}/users/{$username}/repos");

            if ($response->failed()) {
                throw new \Exception('Error when requesting the GitHub API');
            }
            return $response->json();
        });

    }
}
