<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeInfo;
use App\Models\Position;
use App\Models\User;
use App\Notifications\EmployeeCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    private $nationalities =array ("Afghan","Albanian","Algerian","American","Andorran","Angolan","Antiguans","Argentinean","Armenian","Australian",
							"Austrian","Azerbaijani","Bahamian","Bahraini","Bangladeshi","Barbadian","Barbudans","Batswana","Belarusian","Belgian",
							"Belizean","Beninese","Bhutanese","Bolivian","Bosnian","Brazilian","British","Bruneian","Bulgarian","Burkinabe","Burmese",
							"Burundian","Cambodian","Cameroonian","Canadian","Cape Verdean","Central African","Chadian","Chilean","Chinese","Colombian",
							"Comoran","Congolese","Costa Rican","Croatian","Cuban","Cypriot","Czech","Danish","Djibouti","Dominican","Dutch","East Timorese",
							"Ecuadorean","Egyptian","Emirian","Equatorial Guinean","Eritrean","Estonian","Ethiopian","Fijian","Filipino","Finnish",
							"French","Gabonese","Gambian","Georgian","German","Ghanaian","Greek","Grenadian","Guatemalan","Guinea-Bissauan","Guinean",
							"Guyanese","Haitian","Herzegovinian","Honduran","Hungarian","I-Kiribati","Icelander","Indian","Indonesian","Iranian",
							"Iraqi","Irish","Israeli","Italian","Ivorian","Jamaican","Japanese","Jordanian","Kazakhstani","Kenyan","Kittian and Nevisian",
							"Kuwaiti","Kyrgyz","Laotian","Latvian","Lebanese","Liberian","Libyan","Liechtensteiner","Lithuanian","Luxembourger","Macedonian","Malagasy",
							"Malawian","Malaysian","Maldivian","Malian","Maltese","Marshallese","Mauritanian","Mauritian","Mexican","Micronesian","Moldovan",
							"Monacan","Mongolian","Moroccan","Mosotho","Motswana","Mozambican","Namibian","Nauruan","Nepalese","New Zealander","Ni-Vanuatu",
							"Nicaraguan","Nigerian","Nigerien","North Korean","Northern Irish","Norwegian","Omani","Pakistani","Palauan","Panamanian","Papua New Guinean",
							"Paraguayan","Peruvian","Polish","Portuguese","Qatari","Romanian","Russian","Rwandan","Saint Lucian","Salvadoran",
							"Samoan","San Marinese","Sao Tomean","Saudi","Scottish","Senegalese","Serbian","Seychellois","Sierra Leonean","Singaporean",
							"Slovakian","Slovenian","Solomon Islander","Somali","South African","South Korean","Spanish","Sri Lankan","Sudanese","Surinamer","Swazi","Swedish",
							"Swiss","Syrian","Taiwanese","Tajik","Tanzanian","Thai","Togolese","Tongan","Trinidadian or Tobagonian","Tunisian","Turkish",
							"Tuvaluan","Ugandan","Ukrainian","Uruguayan","Uzbekistani","Venezuelan","Vietnamese","Welsh","Yemenite","Zambian","Zimbabwean");

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin,hrmanager']);
    }

    public function addEmployeeForm()
    {
        $managers = User::with('getDepartment', 'getEmployeeInfo')->orderBy('role', 'DESC')->whereIn('role', [0,1,2])->get();
        $departments = Department::all();
        $positions = Position::all();

        return view('addEmployee', ['departments' => $departments, 'managers' => $managers, 'nationalities' => $this->nationalities, 'positions' => $positions]);
    }

    public function addEmployee(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:tomorrow',
            'gender' => 'required',
            'address' => 'required|max:255',
            'ic' => 'required|max:255',
            'nationality' => 'required',
            'citizenship' => 'required',
            'religion' => 'required',
            'race' => 'required',
            'maritalStatus' => 'required',
            'spouseName' => 'max:255|nullable',
            'spouseDateOfBirth' => 'before:tomorrow|nullable',
            'spouseIC' => 'max:255|nullable',
            'dateOfMarriage' => 'before:tomorrow|nullable',
            'spouseOccupation' => 'max:255|nullable',
            'spouseContactNumber' => 'regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14|nullable',
            'emergencyContactName' => 'required|max:255',
            'emergencyContactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'emergencyAddress' => 'required|max:255',
            'username' => 'required|max:255|unique:employee,username',
            'email' => 'required|email|max:255|unique:employee,email',
            'employeeID' => 'required|max:255|unique:employee,employeeID',
            'department' => 'required',
            'position' => 'required',
            'reportingManager' => 'required',
            'role' => 'required',
        ],
        [
            'employeeID.unique' => 'The employee ID has already been taken'
        ]);
        
        $password = Str::random(10);
        $employee = new User();
        $employee->username = $request->username;
        $employee->email = $request->email;
        $employee->employeeID = $request->employeeID;
        $employee->department = $request->department;
        $employee->position = $request->position;
        if($request->role == 2 && $request->department == 1){
            $employee->role = 1;
        }
        else{
            $employee->role = $request->role;
        }
        $employee->reportingManager = $request->reportingManager;
        $employee->password = Hash::make($password);
        $employee->save();

        $employeeInfo = new EmployeeInfo();
        $employeeInfo->employeeID = $employee->id;
        $employeeInfo->firstname = $request->firstname;
        $employeeInfo->lastname = $request->lastname;
        $employeeInfo->contactNumber = $request->contactNumber;
        $employeeInfo->dateOfBirth = $request->dateOfBirth;
        $employeeInfo->gender = $request->gender;
        $employeeInfo->address = $request->address;
        $employeeInfo->ic = $request->ic;
        $employeeInfo->nationality = $request->nationality;
        $employeeInfo->citizenship = $request->citizenship;
        $employeeInfo->religion = $request->religion;
        $employeeInfo->race = $request->race;
        $employeeInfo->maritalStatus = $request->maritalStatus;
        if($request->maritalStatus == "Married"){
            $employeeInfo->spouseName = $request->spouseName;
            $employeeInfo->spouseDateOfBirth = $request->spouseDateOfBirth;
            $employeeInfo->spouseIC = $request->spouseIC;
            $employeeInfo->dateOfMarriage = $request->dateOfMarriage;
            $employeeInfo->spouseOccupation = $request->spouseOccupation;
            $employeeInfo->spouseContactNumber = $request->spouseContactNumber;
            $employeeInfo->spouseResidentStatus = $request->spouseResidentStatus;
        }
        $employeeInfo->emergencyContactName = $request->emergencyContactName;
        $employeeInfo->emergencyContactNumber = $request->emergencyContactNumber;
        $employeeInfo->emergencyContactAddress = $request->emergencyAddress;
        $employeeInfo->save();
        
        $employee->notify(new EmployeeCreatedNotification($request->firstname, $request->username, $password));

        return redirect()->route('addEmployee')->with('message', 'Employee added successfully!')
                                               ->with('message1', 'An email will be sent for the newly created employee. <br> The default password will be randomly generated');
    }

    public function manageEmployee()
    {
        $employees  = User::with('getDepartment', 'getEmployeeInfo')->where('id', '!=', Auth::id())->get();

        return view('manageEmployee', ['employees' => $employees]);
    }

    public function editEmployeeForm($id)
    {
        $employees = User::findOrFail($id);
        $departments = Department::all();
        $positions = Position::all();
        $managers = User::with('getDepartment', 'getEmployeeInfo')->orderBy('role', 'DESC')->whereIn('role', [0,1,2])->where('id', '!=', $id)->get();

        return view('editEmployee', ['employees' => $employees, 'departments' => $departments, 'managers' => $managers, 'nationalities' => $this->nationalities, 'positions' => $positions]);
    }

    public function editEmployee(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:tomorrow',
            'gender' => 'required',
            'address' => 'required|max:255',
            'ic' => 'required|max:255',
            'nationality' => 'required',
            'citizenship' => 'required',
            'religion' => 'required',
            'race' => 'required',
            'maritalStatus' => 'required',
            'spouseName' => 'max:255|nullable',
            'spouseDateOfBirth' => 'before:tomorrow|nullable',
            'spouseIC' => 'max:255|nullable',
            'dateOfMarriage' => 'before:tomorrow|nullable',
            'spouseOccupation' => 'max:255|nullable',
            'spouseContactNumber' => 'regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14|nullable',
            'emergencyContactName' => 'required|max:255',
            'emergencyContactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'emergencyAddress' => 'required|max:255',
            'username' => 'required|max:255|unique:employee,username,'.$id.'',
            'email' => 'required|email|max:255|unique:employee,email,'.$id.'',
            'employeeID' => 'required|max:255|unique:employee,employeeID,'.$id.'',
            'department' => 'required',
            'position' => 'required',
            'reportingManager' => 'required',
            'role' => 'required',
        ],
        [
            'employeeID.unique' => 'The employee ID has already been taken'
        ]
        );

        $employee = User::findOrFail($id);
        $employee->username = $request->username;
        $employee->email = $request->email;
        $employee->employeeID = $request->employeeID;
        $employee->department = $request->department;
        $employee->position = $request->position;
        $employee->reportingManager = $request->reportingManager;
        if($request->role == 2 && $request->department == 1){
            $employee->role = 1;
        }
        else{
            $employee->role = $request->role;
        }
        $employee->save();

        $employeeInfo = EmployeeInfo::findOrFail($employee->id);
        $employeeInfo->firstname = $request->firstname;
        $employeeInfo->lastname = $request->lastname;
        $employeeInfo->contactNumber = $request->contactNumber;
        $employeeInfo->dateOfBirth = $request->dateOfBirth;
        $employeeInfo->gender = $request->gender;
        $employeeInfo->address = $request->address;
        $employeeInfo->ic = $request->ic;
        $employeeInfo->nationality = $request->nationality;
        $employeeInfo->citizenship = $request->citizenship;
        $employeeInfo->religion = $request->religion;
        $employeeInfo->race = $request->race;
        $employeeInfo->maritalStatus = $request->maritalStatus;
        if($request->maritalStatus == "Married"){
            $employeeInfo->spouseName = $request->spouseName;
            $employeeInfo->spouseDateOfBirth = $request->spouseDateOfBirth;
            $employeeInfo->spouseIC = $request->spouseIC;
            $employeeInfo->dateOfMarriage = $request->dateOfMarriage;
            $employeeInfo->spouseOccupation = $request->spouseOccupation;
            $employeeInfo->spouseContactNumber = $request->spouseContactNumber;
            $employeeInfo->spouseResidentStatus = $request->spouseResidentStatus;
        }
        else{
            $employeeInfo->spouseName = null;
            $employeeInfo->spouseDateOfBirth = null;
            $employeeInfo->spouseIC = null;
            $employeeInfo->dateOfMarriage = null;
            $employeeInfo->spouseOccupation = null;
            $employeeInfo->spouseContactNumber = null;
            $employeeInfo->spouseResidentStatus = null;
        }
        $employeeInfo->emergencyContactName = $request->emergencyContactName;
        $employeeInfo->emergencyContactNumber = $request->emergencyContactNumber;
        $employeeInfo->emergencyContactAddress = $request->emergencyAddress;
        $employeeInfo->save();
        
        return redirect()->route('editEmployee', ['id' => $id])->with('message', 'Employee details updated successfully!');
    }

    public function deleteEmployee($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();
        
        $employeeInfo = EmployeeInfo::findOrFail($id);
        $employeeInfo->delete();

        return redirect()->route('manageEmployee');
    }

    public function viewEmployee($id)
    {
        $employees = User::findOrFail($id);
        
        return view('viewEmployee', ['employees' => $employees]);
    }
}
