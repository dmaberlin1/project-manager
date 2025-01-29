<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Services\GitHubInterface;

class GitHubController extends Controller
{
    protected GitHubInterface $githubService;

    public function __construct(GitHubInterface $gitHubService)
    {
        $this->githubService = $gitHubService;
    }

    public function show(string $username)
    {
        try {
            $repos = $this->githubService->getUserRepositories($username);
            return response()->json($repos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
