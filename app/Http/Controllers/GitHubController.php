<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use Illuminate\Http\Request;

class GitHubController extends Controller
{
    protected $githubService;

    public function __construct(GitHubService $gitHubService)
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
