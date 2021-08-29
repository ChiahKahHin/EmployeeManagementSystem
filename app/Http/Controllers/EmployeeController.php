<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Notifications\EmployeeCreatedNotification;
use Illuminate\Http\Request;
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
        $managers = User::with('getDepartment')->orderBy('role', 'DESC')->whereIn('role', [0,1,2])->get();
        $departments = Department::all()->where('departmentName', '!=', 'Administration');

        return view('addEmployee', ['departments' => $departments, 'managers' => $managers, 'nationalities' => $this->nationalities]);
    }

    public function addEmployee(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:today',
            'gender' => 'required',
            'address' => 'required|max:255',
            'ic' => 'required|min:12|max:12',
            'nationality' => 'required',
            'citizenship' => 'required',
            'religion' => 'required',
            'race' => 'required',
            'emergencyContactName' => 'required|max:255',
            'emergencyContactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'emergencyAddress' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'employeeID' => 'required|max:255|unique:users,employeeID',
            'reportingManager' => 'required',
            'department' => 'required',
        ],
        [
            'employeeID.unique' => 'The employee ID has already been taken'
        ]);
        
        $password = Str::random(10);
        $employee = new User();
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->contactNumber = $request->contactNumber;
        $employee->dateOfBirth = $request->dateOfBirth;
        $employee->gender = $request->gender;
        $employee->address = $request->address;
        $employee->ic = $request->ic;
        $employee->nationality = $request->nationality;
        $employee->citizenship = $request->citizenship;
        $employee->religion = $request->religion;
        $employee->race = $request->race;
        $employee->emergencyContactName = $request->emergencyContactName;
        $employee->emergencyContactNumber = $request->emergencyContactNumber;
        $employee->emergencyContactAddress = $request->emergencyAddress;
        $employee->username = $request->username;
        $employee->email = $request->email;
        $employee->password = Hash::make($password);
        $employee->employeeID = $request->employeeID;
        $employee->reportingManager = $request->reportingManager;
        $employee->department = $request->department;
        if($request->manager == null){
            $employee->role = 3;
        }
        else{
            $employee->role = $request->manager;
        }
        $employee->save();
        
        $employee->notify(new EmployeeCreatedNotification($request->firstname, $request->username, $password));

        return redirect()->route('addEmployee')->with('message', 'Employee added successfully!')
                                               ->with('message1', 'An email will be sent for the newly created employee. <br> The default password will be randomly generated');
    }

    public function manageEmployee()
    {
        $employees  = User::with('getDepartment')->where('role', '!=', '0')->get();

        return view('manageEmployee', ['employees' => $employees]);
    }

    public function editEmployeeForm($id)
    {
        $employees = User::findOrFail($id);
        $departments = Department::all()->where('departmentName', '!=', 'Administration');
        $managers = User::orderBy('role', 'DESC')->whereIn('role', [0,1,2])->get();

        return view('editEmployee', ['employees' => $employees, 'departments' => $departments, 'managers' => $managers, 'nationalities' => $this->nationalities]);
    }

    public function editEmployee(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:today',
            'gender' => 'required',
            'address' => 'required|max:255',
            'ic' => 'required|min:12|max:12',
            'nationality' => 'required',
            'citizenship' => 'required',
            'religion' => 'required',
            'race' => 'required',
            'emergencyContactName' => 'required|max:255',
            'emergencyContactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'emergencyAddress' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,'.$id.'',
            'email' => 'required|email|max:255|unique:users,email,'.$id.'',
            'employeeID' => 'required|max:255|unique:users,employeeID,'.$id.'',
            'reportingManager' => 'required',
            'department' => 'required',
        ],
        [
            'employeeID.unique' => 'The employee ID has already been taken'
        ]
        );

        $employee = User::findOrFail($id);
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->contactNumber = $request->contactNumber;
        $employee->dateOfBirth = $request->dateOfBirth;
        $employee->gender = $request->gender;
        $employee->address = $request->address;
        $employee->ic = $request->ic;
        $employee->nationality = $request->nationality;
        $employee->citizenship = $request->citizenship;
        $employee->religion = $request->religion;
        $employee->race = $request->race;
        $employee->emergencyContactName = $request->emergencyContactName;
        $employee->emergencyContactNumber = $request->emergencyContactNumber;
        $employee->emergencyContactAddress = $request->emergencyAddress;
        $employee->username = $request->username;
        $employee->email = $request->email;
        $employee->employeeID = $request->employeeID;
        $employee->reportingManager = $request->reportingManager;
        $employee->department = $request->department;
        if($request->manager == null){
            $employee->role = 3;
        }
        else{
            $employee->role = $request->manager;
        }
        $employee->save();
        
        return redirect()->route('editEmployee', ['id' => $id])->with('message', 'Employee details updated successfully!');
    }

    public function deleteEmployee($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('manageEmployee');
    }

    public function viewEmployee($id)
    {
        $employees = User::findOrFail($id);
        
        return view('viewEmployee', ['employees' => $employees]);
    }
}
