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
        $perPage = $request->input('perPage') ?? 10; // default to 10 items per page
        $page = $request->input('page') ?? 1; // default to the first page

        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->select('*')
                // This should return the id field as a string in the response.
                //->select('*', DB::raw('CAST(id as CHAR(255)) as id'))
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
    // public function store(Request $request)
    // {
    //     try {
    //         $query = $this->jobs_db
    //             ->table('BrighterMonday')
    //             ->insert([
    //                 'job_title' => $request->job_title,
    //                 'company_name' => $request->company_name,
    //                 'job_location' => $request->job_location,
    //                 'job_type' => $request->job_type,
    //                 'job_salary' => $request->job_salary,
    //                 'job_function' => $request->job_function,
    //                 'date_posted' => $request->date_posted,
    //                 'job_link' => $request->job_link,
    //                 'job_summary' => $request->job_summary,
    //                 'min_qualifications' => $request->min_qualifications,
    //                 'experience_level' => $request->experience_level,
    //                 'experience_length' => $request->experience_length,
    //                 'job_requirements' => $request->job_requirements
    //             ]);

    //         return response()->json([
    //             'success' => true,
    //             'data' => $query
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'job_title' => 'required',
            'company_name' => 'required',
            'job_location' => 'required',
            'job_type' => 'required',
            'job_salary' => 'required',
            'job_function' => 'required',
            'date_posted' => 'required|date',
            'job_link' => 'required|url',
            'job_summary' => 'required',
            'min_qualifications' => 'required',
            'experience_level' => 'required',
            'experience_length' => 'required',
            'job_requirements' => 'required'
        ]);

        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->insert($validatedData);

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
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $query = $this->jobs_db
    //             ->table('BrighterMonday')
    //             ->where('id', $id)
    //             ->update([
    //                 'job_title' => $request->job_title,
    //                 'company_name' => $request->company_name,
    //                 'job_location' => $request->job_location,
    //                 'job_type' => $request->job_type,
    //                 'job_salary' => $request->job_salary,
    //                 'job_function' => $request->job_function,
    //                 'date_posted' => $request->date_posted,
    //                 'job_link' => $request->job_link,
    //                 'job_summary' => $request->job_summary,
    //                 'min_qualifications' => $request->min_qualifications,
    //                 'experience_level' => $request->experience_level,
    //                 'experience_length' => $request->experience_length,
    //                 'job_requirements' => $request->job_requirements
    //             ]);

    //         if ($query === 0) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Record not found or update failed'
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => $query
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'job_title' => 'sometimes|required',
            'company_name' => 'sometimes|required',
            'job_location' => 'sometimes|required',
            'job_type' => 'sometimes|required',
            'job_salary' => 'sometimes|required',
            'job_function' => 'sometimes|required',
            'date_posted' => 'sometimes|required|date',
            'job_link' => 'sometimes|required|url',
            'job_summary' => 'sometimes|required',
            'min_qualifications' => 'sometimes|required',
            'experience_level' => 'sometimes|required',
            'experience_length' => 'sometimes|required',
            'job_requirements' => 'sometimes|required'
        ]);

        try {
            $query = $this->jobs_db
                ->table('BrighterMonday')
                ->where('id', $id)
                ->update($validatedData);

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
                ->table('all_available_jobs.BrighterMonday')
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
}