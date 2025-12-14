<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Models\JobCategory;
use App\Models\JobTitle;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    private function employeeRoleId(): int
    {
        // ambil dari config biar fleksibel
        $slug = config('lms.employee_role_slug', 'employee');

        $id = Role::query()->where('slug', $slug)->value('id');

        return $id ?? abort(500, "Role untuk karyawan belum ada. Pastikan roles.slug = '{$slug}'.");
    }

    public function index(): View
    {
        $q = request('q');

        $jobCategoryId = request('job_category_id');
        $jobTitleId    = request('job_title_id');

        $sort = request('sort');
        $dir  = request('dir', 'asc');
        $dir  = in_array($dir, ['asc', 'desc']) ? $dir : 'asc';

        // untuk dropdown
        $jobCategories = JobCategory::query()->orderBy('name')->get();
        $jobTitles     = JobTitle::query()->orderBy('name')->get();

        $employees = User::query()
            ->with(['jobCategory', 'jobTitle', 'role'])
            ->where('role_id', $this->employeeRoleId())

            // filter dropdown
            ->when($jobCategoryId, fn($query) => $query->where('job_category_id', $jobCategoryId))
            ->when($jobTitleId, fn($query) => $query->where('job_title_id', $jobTitleId))

            // search
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })

            // sorting
            ->when($sort === 'job_category', function ($query) use ($dir) {
                $query->leftJoin('job_categories', 'job_categories.id', '=', 'users.job_category_id')
                    ->select('users.*')
                    ->orderBy('job_categories.name', $dir)
                    ->orderBy('users.name', 'asc');
            })
            ->when($sort === 'job_title', function ($query) use ($dir) {
                $query->leftJoin('job_titles', 'job_titles.id', '=', 'users.job_title_id')
                    ->select('users.*')
                    ->orderBy('job_titles.name', $dir)
                    ->orderBy('users.name', 'asc');
            })
            ->when(!in_array($sort, ['job_category', 'job_title']), fn($query) => $query->orderByDesc('id'))

            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'q',
            'sort',
            'dir',
            'jobCategories',
            'jobTitles',
            'jobCategoryId',
            'jobTitleId'
        ));
    }

    public function create(): View
    {
        $jobCategories = JobCategory::query()->orderBy('name')->get();
        $jobTitles = JobTitle::query()->orderBy('name')->get();

        return view('employees.create', compact('jobCategories', 'jobTitles'));
    }

    public function store(EmployeeStoreRequest $request)
    {
        $data = $request->validated();

        User::create([
            'name'            => $data['name'],
            'nik'             => $data['nik'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'birth_date'      => $data['birth_date'],
            'gender'          => $data['gender'],
            'job_category_id' => $data['job_category_id'],
            'job_title_id'    => $data['job_title_id'],
            'is_active'       => (bool) $data['is_active'],
            'role_id'         => $this->employeeRoleId(),
            'password'        => Hash::make($data['password']),
        ]);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dibuat.');
    }

    public function edit(User $employee): View
    {
        abort_unless($employee->role_id === $this->employeeRoleId(), 404);

        $jobCategories = JobCategory::query()->orderBy('name')->get();
        $jobTitles = JobTitle::query()->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'jobCategories', 'jobTitles'));
    }

    public function update(EmployeeUpdateRequest $request, User $employee)
    {
        abort_unless($employee->role_id === $this->employeeRoleId(), 404);

        $data = $request->validated();

        $payload = [
            'name'            => $data['name'],
            'nik'             => $data['nik'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'birth_date'      => $data['birth_date'],
            'gender'          => $data['gender'],
            'job_category_id' => $data['job_category_id'],
            'job_title_id'    => $data['job_title_id'],
            'is_active'       => (bool) $data['is_active'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $employee->update($payload);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diupdate.');
    }

    public function destroy(User $employee)
    {
        abort_unless($employee->role_id === $this->employeeRoleId(), 404);

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
