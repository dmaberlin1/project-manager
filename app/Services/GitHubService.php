<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected $apiUrl;
    protected $apiToken;


    public function __construct()
    {
        $this->apiUrl=config('services.github.url');
        $this->apiToken = config('services.github.token');
    }

    public function getUserRepositories(string $username): ?array
    {
        $response = Http::withToken($this->apiToken)
            ->get("{$this->apiUrl}/users/{$username}/repos");

        if ($response->failed()) {
            throw new \Exception('Error when requesting the GitHub API');
        }
       return $response->json();
    }
}
