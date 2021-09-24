<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
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
        $this->middleware('auth');
    }

    public function viewProfile()
    {
        $employees = User::find(Auth::id());

        return view('viewProfile', ['employees' => $employees]);
    }

    public function updateProfileForm()
    {
        $employees = User::find(Auth::id());

        return view('updateProfile', ['employees' => $employees, 'nationalities' => $this->nationalities]);
    }
    
    public function updateProfile(Request $request)
    {
        $id = Auth::id();

        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:tomorrow',
            'gender' => 'required',
            'address' => 'required|max:255',
            'ic' => 'required|min:12|max:12',
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
            'email' => 'required|email|max:255|unique:users,email,'.$id.'',
            'username' => 'required|max:255|unique:users,username,'.$id.'',
        ]);
        
        $employee = User::findOrFail($id);
        $employee->email = $request->email;
        $employee->username = $request->username;
        $employee->save();

        $employeeInfo = EmployeeInfo::where('userID', $employee->id)->get()->first();
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

       return redirect()->route('viewProfile')->with('message', 'Profile details updated successfully!');
    }
}
