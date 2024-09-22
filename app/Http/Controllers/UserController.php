<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    

    public function index(Request $request)
    {
        $query = Employee::query();

        // Check if there is a search input
        if ($request->input('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
        }

        // Paginate the results
        $users = $query->paginate(10);

        return view('user.index', compact('users'));
    }

    public function create()
    {
        // dd('$users');

        return view('user.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $employeeCode = $this->generateEmployeeCode();

        // Create the user
        Employee::create([
            'code' => $employeeCode,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Redirect with a success message
        return redirect()->route('users.index')->with('status', 'user-created');
    }

    private function generateEmployeeCode()
    {
        // Get the last employee code from the database
        $lastUser = Employee::orderBy('code', 'desc')->first();

        if ($lastUser) {
            // Increment the last employee code by 1 and format it
            $lastCode = (int) $lastUser->code; // Convert to integer
            $nextCode = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT); // Pad with zeros
        } else {
            // If no users exist, start with 0001
            $nextCode = '0001';
        }

        return $nextCode;
    }

    public function edit($id)
    {
        $user = Employee::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Employee::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('users.index')->with('status', 'user-updated');
    }

    public function destroy($id)
    {
        $user = Employee::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('status', 'user-deleted');
    }


}
