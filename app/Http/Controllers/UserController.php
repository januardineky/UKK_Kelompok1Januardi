<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Examination;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\FlareClient\View;

class UserController extends Controller
{
    //
    public function auth(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (auth()->attempt($validate)) {

            // buat ulang session login
            $request->session()->regenerate();

            if (auth()->user()->role === 'admin') {
                Alert::success('Success', 'Login admin berhasil');
                return redirect('/home');
            }
            else if(auth()->user()->role === 'assessor') {
                // $assessor = Assessor::where('user_id', auth()->user()->id)->first();
                Alert::success('Success', 'Login assessor berhasil');
                return redirect('/index');
            }
        }
        Alert::error('Error', 'Username atau Password salah');
        return redirect('/');
    }

    public function index()
    {
        $user = Auth::user();
        $data = $user;

        $assessor = $user->assessor;

        $competencyStandards = CompetencyStandard::with(['major'])
            ->where('assessor_id', $assessor->id)
            ->get();

        return view('dashboard-assessor', compact('data', 'competencyStandards'));
    }

    public function profil()
    {
        $user = Auth::user();
        $assessor = $user->assessor;
        $data = $user;
        return view('profile-assessor', compact('data','assessor'));
    }

    public function editprofileassessor($id)
    {
        $user = Auth::user();
        $data = $user;
        $assessor = Assessor::findOrFail($id);
        return view('profile-assessor-edit', compact('assessor','data'));
    }

    public function updateassessor(Request $request, $id)
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
        Alert::success('Success', 'Data berhasil diubah');
        return redirect('/index');
    }

    public function inputcompetency()
    {
        $user = Auth::user();

        $data = $user;
        $majors = Major::all();
        return view('inputcompetency', compact('majors','data'));
    }

    public function createcompetency(Request $request)
    {
        $user = Auth::user();

        // dd($user->assessor);

        CompetencyStandard::create([
            'unit_code' => $request->unit_code,
            'unit_title' => $request->unit_title,
            'unit_description' => $request->unit_description,
            'major_id' => $request->major_id,
            'grade_level' => $request->grade_level,
            'assessor_id' => $user->assessor->id,
        ]);
        Alert::success('Success', 'Data berhasil ditambahkan');
        return redirect('/index');
    }

    public function editcompetency($id)
    {
        $user = Auth::user();
        $data = $user;

        $major = CompetencyStandard::with('major')->findOrFail($id);

        $majors = Major::all();

        return view('edit-competency', compact('major', 'data', 'majors'));
    }

    public function updatecompetency(Request $request, $id)
    {
        $user = Auth::user();
        $competencyStandards = CompetencyStandard::findOrFail($id);

        $competencyStandards->unit_code = $request->unit_code;
        $competencyStandards->unit_title = $request->unit_title;
        $competencyStandards->unit_description = $request->unit_description;
        $competencyStandards->major_id = $request->major_id;
        $competencyStandards->grade_level = $request->grade_level;
        $competencyStandards->assessor_id = $user->assessor->id;

        $competencyStandards->save();

        Alert::success('Success', 'Data berhasil diubah');

        return redirect('/index');
    }

    public function showelement($id)
    {
        $user = Auth::user();
        $data = $user;
        $competencyStandard = CompetencyStandard::with('elements')->find($id);

        // dd($competencyStandard->elements);

        return view('element-tables', compact('competencyStandard', 'data'));
    }

    public function deletestandard($id)
    {
        $competencyStandards = CompetencyStandard::findOrFail($id);
        $competencyStandards->delete();
        Alert::success('Success', 'Data berhasil dihapus');
        return redirect('/index');
    }

    public function inputelement()
    {
        $user = Auth::user()->assessor->id;

        $data = Auth::user();
        $standards = CompetencyStandard::where('assessor_id',$user)->get();
        return view('input-element', compact('standards','data','user'));
    }

    public function createelement(Request $request)
    {
    // Step 1: Create the competency element
    $competencyElement = CompetencyElement::create([
        'criteria' => $request->criteria,
        'competency_id' => $request->competency_id,
    ]);

    // Step 2: Fetch the competency standard associated with the new element
    $competencyStandard = CompetencyStandard::find($request->competency_id);

    // Step 3: Ensure the competency standard exists
    if (!$competencyStandard) {
        return redirect('/index')->with('error', 'Competency Standard not found.');
    }

    // Step 4: Fetch students based on the major and grade level from the competency standard
    $students = Student::where('major_id', $competencyStandard->major_id)
        ->where('grade_level', $competencyStandard->grade_level)
        ->get();

    // Step 5: Create examinations for each student
    foreach ($students as $student) {
        Examination::create([
            'exam_date' => now(), // Set the exam date to now or a specific date
            'student_id' => $student->id,
            'assessor_id' => $competencyStandard->assessor_id, // Fetch the assessor_id from the competency standard
            'element_id' => $competencyElement->id,
            'status' => 0, // Set the initial status as needed
            'comment' => null, // Or set a default comment
        ]);
    }

    Alert::success('Success', 'Data berhasil ditambahkan');

    // Redirect after successfully creating the competency element and examinations
    return redirect('/index')->with('success', 'Competency Element and Examinations created successfully.');
    }

    public function editelement($id)
    {
        $user = Auth::user();
        $data = $user;

        $element = CompetencyElement::findOrFail($id);

        $standards = CompetencyStandard::all();

        return view('edit-element', compact('element', 'data', 'standards'));
    }

    public function updateelement(Request $request, $id)
    {
        $element = CompetencyElement::findOrFail($id);

        $element->criteria = $request->criteria;
        $element->competency_id = $request->competency_id;

        $element->save();

        Alert::success('Success', 'Data berhasil diubah');

        return redirect('/index');
    }

    public function deleteelement($id)
    {
        $admin = CompetencyElement::findOrFail($id);
        $admin->delete();
        Alert::success('Success', 'Data berhasil dihapus');

        return redirect()->back();
    }

    public function home()
    {
        $competencyStandard = CompetencyStandard::with(['major','assessor'])->get();
        $admins = User::where('role', 'admin')->get();
        $user = Auth::user();
        $data = $user;
        $majors = Major::all();
        $assessors = Assessor::with('user')->get();

        return view('dashboard', compact('data', 'assessors','majors','admins','competencyStandard'));
    }

    public function showcompetency()
    {
        $user = Auth::user();
        $data = $user;

        $assessor = $user->assessor;

        $competencyStandards = CompetencyStandard::all();

        return view('dashboard-competency', compact('data', 'competencyStandards'));
    }

    public function admininputcompetency()
    {
        $user = Auth::user();

        $data = $user;
        $majors = Major::all();
        $assessors = User::with('assessor')->whereHas('assessor')->get();
        return view('admin-competency-input', compact('majors','data','assessors'));
    }

    public function admincreatecompetency(Request $request)
    {
        // dd($user->assessor);

        CompetencyStandard::create([
            'unit_code' => $request->unit_code,
            'unit_title' => $request->unit_title,
            'unit_description' => $request->unit_description,
            'major_id' => $request->major_id,
            'grade_level' => $request->grade_level,
            'assessor_id' => $request->assessor_id,
        ]);
        Alert::success('Success', 'Data berhasil ditambahkan');

        return redirect('/home');
    }

    public function admineditcompetency($id)
    {
        $user = Auth::user();
        $data = $user;

        $competencyStandard = CompetencyStandard::with(['major', 'assessor.user'])->findOrFail($id);
        $assessors = Assessor::with('user')->get();
        $majors = Major::all();

        // dd($competencyStandard);

        return view('admin-competency-edit', compact('competencyStandard', 'data', 'majors', 'assessors'));
    }

    public function adminupdatecompetency(Request $request, $id)
    {
        $competencyStandards = CompetencyStandard::where('id',$request->id)->first();

        $competencyStandards->update([
            'unit_code' => $request->unit_code,
            'unit_title' => $request->unit_title,
            'unit_description' => $request->unit_description,
            'grade_level' => $request->grade_level,
            'major_id' => $request->major_id,
            'assessor_id' => $request->assessor_id,
        ]);
        Alert::success('Success', 'Data berhasil diubah');

        return redirect('/home')->with('success', 'Competency Standard updated successfully.');
    }

    public function admindeletestandard($id)
    {
        $competencyStandards = CompetencyStandard::findOrFail($id);
        $competencyStandards->delete();
        Alert::success('Success', 'Data berhasil dihapus');

        return redirect('/home');
    }

    public function adminshowelement($id)
    {
        $user = Auth::user();
        $data = $user;
        $competencyStandard = CompetencyStandard::with('elements')->find($id);

        // dd($competencyStandard->elements);

        return view('admin-element', compact('competencyStandard', 'data'));
    }

    public function admininputelement()
    {
        // $user = Auth::user()->assessor->id;

        $data = Auth::user();
        $standards = CompetencyStandard::all();
        return view('admin-element-input', compact('standards','data'));
    }

    public function admincreateelement(Request $request)
    {
        CompetencyElement::create([
            'criteria' => $request->criteria,
            'competency_id' => $request->competency_id,
        ]);
        Alert::success('Success', 'Data berhasil ditambahkan');

        return redirect('/home');
    }

    public function admineditelement($id)
    {
        $user = Auth::user();
        $data = $user;

        $element = CompetencyElement::findOrFail($id);

        $standards = CompetencyStandard::all();

        return view('admin-edit-element', compact('element', 'data', 'standards'));
    }

    public function adminupdateelement(Request $request, $id)
    {
        $element = CompetencyElement::findOrFail($id);

        $element->criteria = $request->criteria;
        $element->competency_id = $request->competency_id;

        $element->save();

        Alert::success('Success', 'Data berhasil diubah');

        return redirect('/home');
    }

    public function admindeleteelement($id)
    {
        $admin = CompetencyElement::findOrFail($id);
        $admin->delete();
        Alert::success('Success', 'Data berhasil dihapus');

        return redirect('/home');
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
        Alert::success('Success', 'Data berhasil ditambahkan');

        return redirect('/home');
    }

    public function deleteadmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();
        Alert::success('Success', 'Data berhasil dihapus');

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
    // Step 1: Create the user
    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => bcrypt($request->password), // Hash the password
        'phone_number' => $request->phone_number,
        'role' => 'student', // Assuming the role is 'student'
        'is_active' => 1, // Assuming the user is active by default
    ]);

    // Step 2: Create the student
    $student = Student::create([
        'nisn' => $request->nisn,
        'grade_level' => $request->grade_level,
        'major_id' => $request->major_id,
        'user_id' => $user->id, // Link the student to the newly created user
    ]);

    // Step 3: Fetch competency standards linked to the major and grade level
    $competencyStandards = CompetencyStandard::where('major_id', $student->major_id)
        ->where('grade_level', $student->grade_level) // Filter by grade level
        ->get();

    // Step 4: Loop through each competency standard and create examinations
    foreach ($competencyStandards as $standard) {
        // Fetch competency elements linked to the standard
        $competencyElements = CompetencyElement::where('competency_id', $standard->id)->get();

        foreach ($competencyElements as $element) {
            // Create an examination for each competency element
            Examination::create([
                'exam_date' => now(), // Set the exam date to now or a specific date
                'student_id' => $student->id,
                'assessor_id' => $standard->assessor_id, // Fetch the assessor_id from the competency standard
                'element_id' => $element->id,
                'status' => 0, // Set the initial status as needed
                'comment' => null, // Or set a default comment
            ]);
        }
    }
        Alert::success('Success', 'Data berhasil ditambahkan');

        return redirect('/home/students');
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
        Alert::success('Success', 'Data berhasil ditambahkan');

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

        Alert::success('Success', 'Data berhasil diubah');

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

        Alert::success('Success', 'Data berhasil dihapus');

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

        Alert::success('Success', 'Data berhasil dihapus');

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

        Alert::success('Success', 'Data berhasil dihapus');

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

        Alert::success('Success', 'Data berhasil diubah');
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
        Alert::success('Success', 'Data berhasil diubah');
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
        Alert::success('Success', 'Data berhasil ditambahkan');

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
        Alert::success('Success', 'Data berhasil diubah');
        return redirect('/home');
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

    public function tableassessor()
    {

        // Get the authenticated user
        $user = Auth::user();
        $data = $user;

        // Check if the user is an assessor
        if ($user->role === 'assessor') {
            // Get the assessor's ID
            $assessorId = Assessor::where('user_id', $user->id)->value('id');

            // Get competency standards associated with this assessor
            $competencyStandards = CompetencyStandard::where('assessor_id', $assessorId)->get();

            // Get the major IDs from the competency standards
            $majorIds = $competencyStandards->pluck('major_id');

            // Retrieve students with their examinations filtered by the major IDs
            $students = Student::with(['examinations' => function ($query) {
                $query->with('competencyElement'); // Eager load competency elements if needed
            }])->whereIn('major_id', $majorIds)->get();
        } else {
            // If the user is not an assessor, return an empty collection
            $students = collect(); // Return an empty collection
        }

        // Pass the data to the view
        return view('table-assessor', compact('students', 'user','data'));
    }

    public function filterStudents(Request $request)
    {
        $gradeLevel = $request->query('grade_level');
        $students = Student::with('user', 'major')
            ->when($gradeLevel, function ($query) use ($gradeLevel) {
                return $query->where('grade_level', $gradeLevel);
            })
            ->get();

        return response()->json(['students' => $students]);
    }

    public function assessorexam($id)
    {
    $user = Auth::user();
    $data = $user;

    // Retrieve the student with related examinations, assessors, and competency elements
    $student = Student::with(['major', 'examinations.assessor', 'examinations.competencyElement.competencyStandard'])
        ->findOrFail($id); // This will throw a 404 error if the student is not found

    // Get the logged-in assessor's ID
    $assessorId = $user->assessor->id;

    // Get the assessor's competency standards
    $competencyStandards = $user->assessor->competencyStandards()->pluck('id')->toArray();

    // Filter the student's examinations based on the assessor's competency standards and assessor ID
    $filteredExaminations = $student->examinations->filter(function ($examination) use ($competencyStandards, $assessorId) {
        return in_array($examination->competencyElement->competencyStandard->id, $competencyStandards) && $examination->assessor_id == $assessorId;
    });

    // Initialize variables for competency score calculation
    $competencyScores = [];
    $competencyLevels = [];

    // Group filtered examinations by competency standard
    $examinationsByStandard = $filteredExaminations->groupBy(function ($examination) {
        return $examination->competencyElement->competencyStandard->id;
    });

    // Calculate scores for each competency standard
    foreach ($examinationsByStandard as $standardId => $examinations) {
        $totalElements = $examinations->count();
        $competentCount = $examinations->where('status', 1)->count();

        if ($totalElements > 0) {
            $percentage = round(($competentCount / $totalElements) * 100);
            $competencyScores[$standardId] = $percentage;

            // Determine competency level based on the percentage
            if ($percentage >= 91) {
                $competencyLevels[$standardId] = 'Sangat Kompeten';
            } elseif ($percentage >= 75) {
                $competencyLevels[$standardId] = 'Kompeten';
            } elseif ($percentage >= 61) {
                $competencyLevels[$standardId] = 'Cukup Kompeten';
            } else {
                $competencyLevels[$standardId] = 'Belum Kompeten';
            }
        } else {
            $competencyScores[$standardId] = 0; // No examinations for this standard
            $competencyLevels[$standardId] = 'Belum Dinilai'; // No evaluations
        }
    }

    // Pass the data to the view
    return view('evaluation-table', compact('student', 'data', 'competencyScores', 'competencyLevels', 'examinationsByStandard'));
    }

    public function updateExaminationStatus(Request $request, $studentId)
    {
        // Validate the incoming request
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:0,1', // Ensure that each status is either 0 or 1
        ]);

        // Loop through each status and update the corresponding examination
        foreach ($request->status as $examinationId => $status) {
            $examination = Examination::find($examinationId);
            if ($examination) {
                $examination->status = $status;
                $examination->save();
            }
        }

        Alert::success('Success', 'Data berhasil diubah');
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Examination statuses updated successfully.');
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
