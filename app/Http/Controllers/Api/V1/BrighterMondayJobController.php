<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BrighterMondayJobController extends Controller
{
    //gets all jobs present in the BrigherMonday Table
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ?? 10; // default to 10 items per page
        $page = $request->input('page') ?? 1; // default to the first page

        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->select('*')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    // gets a single job from the BrighterMonday Table
    public function show($id)
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->select('*')
                ->where('id', $id)
                ->get();

            if ($query->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No record found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    // saved new job listing data to the database
    public function store(Request $request)
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // updates job data based on Id 
    public function update(Request $request, $id)
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
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

            if ($query === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found or update failed'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // deletes a job listing based on Id
    public function destroy($id)
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->where('id', $id)
                ->delete();

            if ($query === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found or delete failed'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // gets the total number of jobs available in the BrighterMonday table
    public function getJobCount()
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->count();

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // gets the total number of jobs available in the BrighterMonday table based on location
    public function getJobCountByLocation($location)
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->where('job_location', $location)
                ->count();

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // loops all over the table while searching for the predefined search-term
    public function searchJobs(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // groups the jobs by job_function(Categories)
    public function groupByCategory(Request $request)
    {

        $perPage = $request->input('perPage') ?? 10; // default to 10 items per page
        $page = $request->input('page') ?? 1; // default to the first page

        try {
            //$expression = DB::raw('count(*) as job_count');
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->select('job_function', DB::raw('count(*) as job_count')) //$expression->getValue($this->jobs_db->getQueryGrammar()))
                ->groupBy('job_function')
                ->paginate($perPage, ['*'], 'page', $page);


            if ($query->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $query
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching grouped job data.'
            ], 500);
        }
    }

    // groups the jobs by job_location
    public function getByLocation(Request $request)
    {

        $perPage = $request->input('perPage') ?? 10; // default to 10 items per page
        $page = $request->input('page') ?? 1; // default to the first page

        $location = $request->route('location');

        try {
            $jobs = $this->jobs_db
                ->table('BrighterMonday')
                ->where('job_location', '=', $location)
                ->paginate($perPage, ['*'], 'page', $page);


            if ($jobs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $jobs
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching job data.'
            ], 500);
        }
    }
}
