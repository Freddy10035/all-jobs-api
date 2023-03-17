<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    //
    public function getAllJobs()
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->select('*')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }
}
