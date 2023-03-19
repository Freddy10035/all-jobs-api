# JobScout API

This document provides an overview of the API endpoints available for accessing job postings data from the JobsScout application. The API is built using Laravel and utilizes the Passport authentication library for securing endpoints.

<br>

## API Endpoints

The following are some of the endpoints are available in the API:

<br>

## Authentication

This API uses Passport for authentication. In order to access protected endpoints, you will need to obtain an access token by logging in or registering as a user. The access token should be included in the Authorization header of each API request in the format Bearer {access_token}.

For example:

        Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...


<br>

### POST /api/login

- Authenticates a user with the system and returns a valid access token for future API requests.
- Request Body:


        {
            "email": "user@example.com",
            "password": "password"
        }

- Response:

       {
            "token_type": "Bearer",
            "expires_at": "2022-03-18T05:44:17.000000Z",
            "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
       }


<br>

### Job Posting GET /api/jobs

- Returns a list of all job postings.
- Optional query parameters:
    - `job_title`: The job title to filter by.
    - `company_name`: The company name to filter by.
    - `job_location`: The job location to filter by.
    - `job_type`: The job type to filter by.
    - `job_salary`: The job salary to filter by.
    - `job_function`: The job function to filter by.
    - `date_posted`: The date the job was posted, in the format Y-m-d.
    - `job_link`: The link to the job posting to filter by.
    - `page`: The page number to retrieve. Defaults to 1 if not provided.
    - `per_page`: The number of results per page. Defaults to 10 if not provided.

    <br>
    
- Response:

        {
            "current_page": 1,
            "data": [
                {
                    "id": 1,
                    "job_title": "Software Engineer",
                    "company_name": "Acme Inc.",
                    "job_location": "Nairobi",
                    "job_type": "Full-time",
                    "job_salary": "Ksh120,000 - Ksh150,000",
                    "job_function": "Software Development",
                    "date_posted": "2022-03-15",
                    "job_link": "https://www.acme.com/careers/software-engineer"
                },
                {
                    "id": 2,
                    "job_title": "Data Analyst",
                    "company_name": "XYZ Corp.",
                    "job_location": "Nairobi",
                    "job_type": "Contract",
                    "job_salary": "Ksh500 - Ksh600 per hour",
                    "job_function": "Data Analysis",
                    "date_posted": "2022-03-14",
                    "job_link": "https://www.xyz.com/careers/data-analyst"
                },
                ...
            ],
            "first_page_url": "/api/jobs?page=1",
            "from": 1,
            "last_page": 3,
            "last_page_url": "/api/jobs?page=3",
            "next_page_url": "/api/jobs?page=2",
            "path": "/api/jobs",
            "per_page": 10,
            "prev_page_url": null,
            "to": 10,
            "total": 30
        }



### GET /api/jobs/{id}

- Returns the job posting with the specified ID.
- Response:


        {
            "id": 1,
            "job_title": "Software Engineer",
            "company_name": "Acme Inc.",
            "job_location": "Nairobi",
            "job_type": "Full-time",
            "job_salary": "Ksh100,000 - Ksh120,000",
            "job_function": "Engineering",
            "date_posted": "2023-03-16",
            "job_link": "https://www.acmeinc.com/careers/software-engineer"
        }



### PUT /api/jobs/{id}

- Updates an existing job listing in the database.
- Request Body:


        {
            "job_title": "Senior Software Engineer",
            "company_name": "Acme Inc.",
            "job_location": "Nairobi,
            "job_type": "Full-time",
            "job_salary": "Ksh120,000 - Ksh140,000",
            "job_function": "Engineering",
            "date_posted": "2023-03-16",
            "job_link": "https://www.acmeinc.com/careers/software-engineer"
        }

- Response:

        {
            "message": "Job listing updated successfully"
        }


<br>

### DELETE /api/jobs/{id}

- Deletes an existing job listing from the database.
- Response:

        {
            "message": "Job listing deleted successfully"
        }


<br>

### GET /api/categories

- Returns a list of all job categories.
- Response:

        {
            "categories": [
                "Technology",
                "Marketing",
                "Sales",
                ...
            ]
        }


<br>

### GET /api/locations

- Returns a list of all job locations.

<br>

### Users GET /api/user

- Returns the authenticated user's information.
- Requires a valid access token in the Authorization header.
- Response:

        {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com"
        }


<br>

### POST /api/register

- Registers a new user with the system.
- Request Body:

        {
            "name": "John Doe",
            "email": "user@example.com",
            "password": "password",
            "password_confirmation": "password"
        }

- Response:

        {
            "message": "Registration successful"
        }

<br>

## Errors

The API returns errors with appropriate HTTP status codes and JSON response bodies. The response body contains an error key with a message describing the error.

Here are the possible error status codes and messages:

- 400 Bad Request: Invalid request data.
- 401 Unauthorized: Authentication failed or invalid access token.
- 403 Forbidden: Access to the requested resource is forbidden.
- 404 Not Found: The requested resource does not exist.
- 500 Internal Server Error: An unexpected error occurred.

