<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerJob;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CareerJobController extends Controller
{
    public function list(Request $request)
    {
        $search = $request->search;
        $jobs = CareerJob::withCount('applications')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(25);

        return view('admin-views.career-jobs.list', compact('jobs'));
    }

    public function create()
    {
        return view('admin-views.career-jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'job_type' => 'required|in:full-time,part-time,contract,internship',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:100',
        ]);

        CareerJob::create($request->only([
            'title', 'department', 'location', 'job_type',
            'description', 'requirements', 'salary_range'
        ]));

        Toastr::success(translate('messages.job_created_successfully'));
        return redirect()->route('admin.career-jobs.list');
    }

    public function edit($id)
    {
        $job = CareerJob::findOrFail($id);
        return view('admin-views.career-jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'job_type' => 'required|in:full-time,part-time,contract,internship',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:100',
        ]);

        $job = CareerJob::findOrFail($id);
        $job->update($request->only([
            'title', 'department', 'location', 'job_type',
            'description', 'requirements', 'salary_range'
        ]));

        Toastr::success(translate('messages.job_updated_successfully'));
        return redirect()->route('admin.career-jobs.list');
    }

    public function status($id, $status)
    {
        $job = CareerJob::findOrFail($id);
        $job->status = $status;
        $job->save();

        Toastr::success(translate('messages.status_updated'));
        return back();
    }

    public function delete($id)
    {
        CareerJob::findOrFail($id)->delete();
        Toastr::success(translate('messages.job_deleted_successfully'));
        return back();
    }

    // Job Applications
    public function applications(Request $request, $id)
    {
        $job = CareerJob::findOrFail($id);
        $applications = JobApplication::where('career_job_id', $id)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(25);

        return view('admin-views.career-jobs.applications', compact('job', 'applications'));
    }

    public function applicationStatus(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        Toastr::success(translate('messages.status_updated'));
        return back();
    }

    public function applicationDelete($id)
    {
        $application = JobApplication::findOrFail($id);
        if ($application->resume) {
            $path = storage_path('app/public/' . $application->resume);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $application->delete();
        Toastr::success(translate('messages.application_deleted'));
        return back();
    }

    public function downloadResume($id)
    {
        $application = JobApplication::findOrFail($id);
        if ($application->resume && file_exists(storage_path('app/public/' . $application->resume))) {
            return response()->download(storage_path('app/public/' . $application->resume));
        }
        Toastr::error(translate('messages.file_not_found'));
        return back();
    }
}
