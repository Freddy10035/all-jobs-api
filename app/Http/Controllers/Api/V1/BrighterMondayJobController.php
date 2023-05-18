<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

/**
 * Summary of BrighterMondayJobController
 */
class BrighterMondayJobController extends Controller
{
    //gets all jobs present in the BrigherMonday Table
    /**
     * Summary of index
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // $perPage = $request->input('perPage') ?? 10; // default to 10 items per page
        // $page = $request->input('page') ?? 1; // default to the first page

        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->select('job_title', 'company_name', 'job_location', 'job_type', 'job_salary', 'job_function', 'date_posted', 'job_link', 'job_summary', 'min_qualifications', 'experience_level', 'experience_length')
                ->orderBy('created_on', 'desc')
                ->limit(100)
                ->get();

            return response()->json([
                'success' => true,
                //get the count
                'count' => $query->count(),
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
    /**
     * Summary of show
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Summary of store
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->insert([
                    'job_title' => $request->input('job_title'),
                    'company_name' => $request->input('company_name'),
                    'job_location' => $request->input('job_location'),
                    'job_type' => $request->input('job_type'),
                    'job_salary' => $request->input('job_salary'),
                    'job_function' => $request->input('job_function'),
                    'date_posted' => $request->input('date_posted'),
                    'job_link' => $request->input('job_link'),
                    'job_summary' => $request->input('job_summary'),
                    'min_qualifications' => $request->input('min_qualifications'),
                    'experience_level' => $request->input('experience_level'),
                    'experience_length' => $request->input('experience_length'),
                    'job_requirements' => $request->input('job_requirements')
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
    /**
     * Summary of update
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, $id)
    {
        try {
            // Get the record to update
            $record = $this->jobs_db->table('BrighterMonday')->find($id);

            // Check if the record exists
            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found'
                ]);
            }

            // Validate the input fields
            $fieldsToUpdate = $request->validate([
                'job_title' => 'sometimes|required|string',
                'company_name' => 'sometimes|required|string',
                'job_location' => 'sometimes|required|string',
                'job_type' => 'sometimes|required|string',
                'job_salary' => 'sometimes|required|string',
                'job_function' => 'sometimes|required|string',
                'date_posted' => 'sometimes|required|date',
                'job_link' => 'sometimes|required|url',
                'job_summary' => 'sometimes|required|string',
                'min_qualifications' => 'sometimes|required|string',
                'experience_level' => 'sometimes|required|string',
                'experience_length' => 'sometimes|required|string',
                'job_requirements' => 'sometimes|required|string'
            ]);

            // Check if there are any fields to update
            if (empty($fieldsToUpdate)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No fields to update'
                ]);
            } else {
                // Update the record
                $this->jobs_db->table('BrighterMonday')
                    ->where('id', $id)
                    ->update($fieldsToUpdate);

                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // deletes a job listing based on Id
    /**
     * Summary of destroy
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Summary of getJobCount
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Summary of getJobCountByLocation
     * @param mixed $location
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Summary of searchJobs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchJobs(Request $request)
    {
        try {
            $search = $request->input('search');

            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->where(function ($q) use ($search) {
                    $q->where('job_title', 'like', '%' . $search . '%')
                        ->orWhere('company_name', 'like', '%' . $search . '%')
                        ->orWhere('job_location', 'like', '%' . $search . '%')
                        ->orWhere('job_type', 'like', '%' . $search . '%')
                        ->orWhere('job_salary', 'like', '%' . $search . '%')
                        ->orWhere('job_function', 'like', '%' . $search . '%')
                        ->orWhere('date_posted', 'like', '%' . $search . '%')
                        ->orWhere('job_link', 'like', '%' . $search . '%')
                        ->orWhere('job_summary', 'like', '%' . $search . '%')
                        ->orWhere('min_qualifications', 'like', '%' . $search . '%')
                        ->orWhere('experience_level', 'like', '%' . $search . '%')
                        ->orWhere('experience_length', 'like', '%' . $search . '%')
                        ->orWhere('job_requirements', 'like', '%' . $search . '%');
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

    // groups the jobs by job_function(Categories) and lists the number and the jobs
    /**
     * Summary of groupByCategory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupByCategory(Request $request)
    {
        $perPage = $request->input('perPage') ?? 10; // default to 10 items per page
        $page = $request->input('page') ?? 1; // default to the first page

        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->select('job_function', DB::raw('count(*) as job_count'))
                ->groupBy('job_function')
                ->paginate($perPage, ['*'], 'page', $page);

            if ($query->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found',
                ], 404);
            }

            // loop through each category and add the job listings
            foreach ($query as $category) {
                $category->jobs = $this->jobs_db
                    ->table('BrighterMonday')
                    ->where('job_function', $category->job_function)
                    ->get();
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
    /**
     * Summary of getByLocation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    public function filterSearch(Request $request)
    {
        try {
            $job_title = '%' . $request->input('job_title') . '%'; //search for similar job titles
            $job_location = '%' . $request->input('job_location') . '%';
            $job_type = '%' . $request->input('job_type') . '%';

            $jobs = $this->jobs_db
                ->table('BrighterMonday')
                ->where('job_title', 'like', $job_title)
                ->where('job_location', 'like', $job_location)
                ->where('job_type', 'like', $job_type)
                ->select('id', 'job_title', 'job_location', 'job_function', 'job_link', 'job_summary')
                ->get();

            if ($jobs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No jobs found.'
                ]);
            }

            return response()->json([
                'success' => true,
                'jobs' => $jobs
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

}