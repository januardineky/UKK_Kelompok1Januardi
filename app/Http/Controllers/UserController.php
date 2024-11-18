<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\FlareClient\View;

class UserController extends Controller
{
    //
    public function auth(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validate)) {
            return redirect('/home');
        }
        else {
            return redirect()->back();
        }
    }

    public function home()
    {
        $user = Auth::user();
        $data = $user;
        $majors = Major::all();
        $assessors = Assessor::with('user')->get();

        return view('dashboard', compact('data', 'assessors','majors'));
    }

    public function students()
    {
        $students = Student::with(['user', 'major'])->get();
        $user = Auth::user();

        $data = $user;

        return view('students', compact('students','data'));
    }

    public function search(Request $request)
{
    $user = Auth::user();
    $data = $user;

    // Perform the search
    $students = Student::with('user', 'major')
        ->where(function($query) use ($request) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('full_name', 'LIKE', '%' . $request->cari . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->cari . '%')
                  ->orWhere('username', 'LIKE', '%' . $request->cari . '%');
            })
            ->orWhere('nisn', 'LIKE', '%' . $request->cari . '%')
            ->orWhereHas('major', function($q) use ($request) {
                $q->where('major_name', 'LIKE', '%' . $request->cari . '%');
            })
            ->orWhere('grade_level', 'LIKE', '%' . $request->cari . '%'); // Add this line for grade_level
        })
        ->get();

    return view('students', compact('students', 'data'));
    }

    public function createadmin()
    {
        $user = Auth::user();

        $data = $user;

        return view('inputadmin', compact('data'));
    }

    public function inputadmin(Request $request)
    {
        User::create([
            'full_name' => $request->full_name,
            'email' =>  $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password), // Hash the password
            'phone_number' => $request->phone_number,
            'role' => 'admin', // Assuming the role is 'student'
            'is_active' => 1, // Assuming the user is active by default
        ]);
        return redirect('/home');
    }

    public function inputstudents()
    {
        $user = Auth::user();

        $data = $user;
        $majors = Major::all(); // Retrieve all majors from the database
        return view('register-student', compact('majors','data')); // Pass the majors to the view
    }


    public function createstudents(Request $request)
    {
    $user = User::create([
        'full_name' => $request->full_name,
        'email' =>  $request->email,
        'username' => $request->username,
        'password' => bcrypt($request->password), // Hash the password
        'phone_number' => $request->phone_number,
        'role' => 'student', // Assuming the role is 'student'
        'is_active' => 1, // Assuming the user is active by default
    ]);

    Student::create([
        'nisn' => $request->nisn,
        'grade_level' => $request->grade_level,
        'major_id' => $request->major_id,
        'user_id' => $user->id, // Link the student to the newly created user
    ]);

    // Step 4: Redirect or return a response
    return redirect('/home/students')->with('success', 'Student created successfully!');
    }

    public function inputmajors()
    {
        $user = Auth::user();

        $data = $user;
        return view('inputmajors', compact('data'));
    }

    public function createmajors(Request $request)
    {
        Major::create([
            'major_name' => $request->major_name,
            'description' => $request->description
        ]);
        return redirect('/home');
    }

    public function editmajors($id)
    {
        $user = Auth::user();

        $data = $user;
        $major = Major::findOrFail($id);
        return view('edit-major', compact('major','data'));
    }

    public function updatemajors(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $major->major_name = $request->major_name;
        $major->description = $request->description;

        $major->save();

        return redirect('/home');
    }

    public function deletemajors($id)
    {
        // Find the major by ID
        $major = Major::find($id);

        // Check if the major exists
        if (!$major) {
            return redirect()->back()->with('error', 'Major not found.');
        }

        // Delete the major
        $major->delete();

        // Redirect back with success message
        return redirect()->back();
    }

    public function deletestudent($id)
    {
        // Find the student by ID
        $student = Student::find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        $user = $student->user;
        if ($user) {
            $user->delete();
        }

        $student->delete();

        return redirect()->back()->with('success', 'Student and associated user deleted successfully.');
    }

    public function deleteassessor($id)
    {
        $assessor = Assessor::find($id);

        if (!$assessor) {
            return redirect()->back()->with('error', 'assessor not found.');
        }

        $user = $assessor->user;
        if ($user) {
            $user->delete();
        }

        $assessor->delete();

        return redirect()->back()->with('success', 'assessor and associated user deleted successfully.');
    }

    public function editstudent($id)
    {
        $user = Auth::user();

        $data = $user;
        $student = Student::findOrFail($id);
        $majors = Major::all();
        return view('edit-student', compact('student', 'majors','data'));
    }

    public function editassessor($id)
    {
        $user = Auth::user();

        $data = $user;
        $assessor = Assessor::findOrFail($id);
        return view('edit-assessor', compact('assessor','data'));
    }

    public function updatestudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Update the user's information
        $user = $student->user; // Assuming you have a relationship defined

        // Update user attributes
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone_number = $request->phone_number;

        // If a password is provided, hash it before saving
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Save user data
        $user->save();

        // Update the student's specific information
        $student->nisn = $request->nisn;
        $student->grade_level = $request->grade_level;
        $student->major_id = $request->major_id;

        // Save the student data
        $student->save();

        // Redirect back with a success message
        return redirect('/home/students');
    }

    public function updatesassessor(Request $request, $id)
    {
        $assessor = Assessor::findOrFail($id);

        // Update the user's information
        $user = $assessor->user; // Assuming you have a relationship defined

        // Update user attributes
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone_number = $request->phone_number;

        // If a password is provided, hash it before saving
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Save user data
        $user->save();

        // Update the student's specific information
        $assessor->assessor_type = $request->assessor_type;
        $assessor->description = $request->description;

        // Save the student data
        $assessor->save();

        // Redirect back with a success message
        return redirect('/home');
    }

    public function inputassessor()
    {
        $user = Auth::user();

        $data = $user;
        return view('registeassessor', compact('data'));
    }

    public function createassessor(Request $request)
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'email' =>  $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password), // Hash the password
            'phone_number' => $request->phone_number,
            'role' => 'assessor', // Assuming the role is 'student'
            'is_active' => 1, // Assuming the user is active by default
        ]);

        Assessor::create([
            'assessor_type' => $request->assessor_type,
            'description' => $request->description,
            'user_id' => $user->id, // Link the student to the newly created user
        ]);
        return redirect('/home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function profile()
    {
        $user = Auth::user();

        $data = $user;
        return view('profile', compact('data'));
    }

    public function editprofile($id)
    {
        $user = Auth::user();

        $data = $user;
        $admin = User::findOrFail($id);
        return view('edit-admin', compact('admin','data'));
    }

    public function updateadmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Update user attributes
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone_number = $request->phone_number;

        // If a password is provided, hash it before saving
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Save user data
        $user->save();

        // Redirect back with a success message
        return redirect('/home/profile');
    }

    public function table()
    {
        $students = Student::with('examinations')->get();

        // Get the authenticated user
        $user = Auth::user();
        $data = $user;

        // Pass the data to the view
        return view('table', compact('students', 'data'));
    }

    public function exam($id)
    {
        $user = Auth::user();
        $data = $user;
        $student = Student::with(['major', 'examinations.assessor', 'examinations.competencyElement.competencyStandard'])
        ->findOrFail($id); // This will throw a 404 error if the student is not found

        return view('examination-table', compact('student','data'));
    }
}
