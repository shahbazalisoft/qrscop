<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Models\CareerJob;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CareerJobController extends Controller
{
    public function list(Request $request)
    {
        $store = Helpers::get_store_data();
        $search = $request->search;
        $jobs = CareerJob::withCount('applications')
            ->where('store_id', $store->id)
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(25);

        return view('vendor-views.career-jobs.list', compact('jobs'));
    }

    public function create()
    {
        return view('vendor-views.career-jobs.create');
    }

    public function store(Request $request)
    {
        $store = Helpers::get_store_data();

        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'job_type' => 'required|in:full-time,part-time,contract,internship',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:100',
        ]);

        CareerJob::create(array_merge(
            $request->only(['title', 'department', 'location', 'job_type', 'description', 'requirements', 'salary_range']),
            ['store_id' => $store->id]
        ));

        Toastr::success(translate('messages.job_created_successfully'));
        return redirect()->route('vendor.career-jobs.list');
    }

    public function edit($id)
    {
        $store = Helpers::get_store_data();
        $job = CareerJob::where('store_id', $store->id)->findOrFail($id);
        return view('vendor-views.career-jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $store = Helpers::get_store_data();

        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'job_type' => 'required|in:full-time,part-time,contract,internship',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:100',
        ]);

        $job = CareerJob::where('store_id', $store->id)->findOrFail($id);
        $job->update($request->only([
            'title', 'department', 'location', 'job_type',
            'description', 'requirements', 'salary_range'
        ]));

        Toastr::success(translate('messages.job_updated_successfully'));
        return redirect()->route('vendor.career-jobs.list');
    }

    public function status($id, $status)
    {
        $store = Helpers::get_store_data();
        $job = CareerJob::where('store_id', $store->id)->findOrFail($id);
        $job->status = $status;
        $job->save();

        Toastr::success(translate('messages.status_updated'));
        return back();
    }

    public function delete($id)
    {
        $store = Helpers::get_store_data();
        CareerJob::where('store_id', $store->id)->findOrFail($id)->delete();
        Toastr::success(translate('messages.job_deleted_successfully'));
        return back();
    }

    public function applications(Request $request, $id)
    {
        $store = Helpers::get_store_data();
        $job = CareerJob::where('store_id', $store->id)->findOrFail($id);
        $applications = JobApplication::where('career_job_id', $id)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(25);

        return view('vendor-views.career-jobs.applications', compact('job', 'applications'));
    }

    public function applicationStatus(Request $request, $id)
    {
        $store = Helpers::get_store_data();
        $application = JobApplication::whereHas('careerJob', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->findOrFail($id);
        $application->status = $request->status;
        $application->save();

        Toastr::success(translate('messages.status_updated'));
        return back();
    }

    public function applicationDelete($id)
    {
        $store = Helpers::get_store_data();
        $application = JobApplication::whereHas('careerJob', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->findOrFail($id);

        if ($application->resume && file_exists(storage_path('app/public/' . $application->resume))) {
            unlink(storage_path('app/public/' . $application->resume));
        }
        $application->delete();
        Toastr::success(translate('messages.application_deleted'));
        return back();
    }

    public function downloadResume($id)
    {
        $store = Helpers::get_store_data();
        $application = JobApplication::whereHas('careerJob', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->findOrFail($id);

        if ($application->resume && file_exists(storage_path('app/public/' . $application->resume))) {
            return response()->download(storage_path('app/public/' . $application->resume));
        }
        Toastr::error(translate('messages.file_not_found'));
        return back();
    }
}
