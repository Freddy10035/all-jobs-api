<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BrighterMondayJobController extends Controller
{
    //gets all jobs present in the BrigherMonday Table
    public function index()
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

    // gets a single job from the BrighterMonday Table
    public function show($id)
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->select('*')
            ->where('id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }


    // saved new job listing data to the database
    public function store(Request $request)
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->insert([
                'job_title' => $request->job_title,
                'company_name' => $request->company_name,
                'job_location' => $request->job_location,
                'job_type' => $request->job_type,
                'job_salary' => $request->job_salary,
                'job_function' => $request->job_function,
                'date_posted' => $request->date_posted,
                'job_link' => $request->job_link
            ]);

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }

    // updates job data based on Id 
    public function update(Request $request, $id)
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->where('id', $id)
            ->update([
                'job_title' => $request->job_title,
                'company_name' => $request->company_name,
                'job_location' => $request->job_location,
                'job_type' => $request->job_type,
                'job_salary' => $request->job_salary,
                'job_function' => $request->job_function,
                'date_posted' => $request->date_posted,
                'job_link' => $request->job_link
            ]);

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }

    // deletes a job listing based on Id
    public function destroy($id)
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }

    public function getJobCount()
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->count();

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }

    public function getJobCountByLocation($location)
    {
        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->where('job_location', $location)
            ->count();

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }

    public function searchJobs(Request $request)
    {
        $search = $request->input('search');

        $query = $this->jobs_db
            ->table('all_available_jobs.BrighterMonday')
            ->where(function ($q) use ($search) {
                $q->where('job_title', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%')
                    ->orWhere('job_location', 'like', '%' . $search . '%')
                    ->orWhere('job_type', 'like', '%' . $search . '%')
                    ->orWhere('job_salary', 'like', '%' . $search . '%')
                    ->orWhere('job_function', 'like', '%' . $search . '%')
                    ->orWhere('date_posted', 'like', '%' . $search . '%')
                    ->orWhere('job_link', 'like', '%' . $search . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }
}
