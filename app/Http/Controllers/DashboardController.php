<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Memo;
use App\Models\Task;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $quotes = array(
        "\"Concentrate all your thoughts upon the work in hand. The sun's rays do not burn until brought to a focus.\" - Alexander Graham Bell",
        "\"Either you run the day or the day runs you.\" - Jim Rohn",
        "\"I’m a greater believer in luck, and I find the harder I work the more I have of it.\" - Thomas Jefferson",
        "\"When we strive to become better than we are, everything around us becomes better too.\" - Paulo Coelho",
        "\"Opportunity is missed by most people because it is dressed in overalls and looks like work.\" - Thomas Edison",
        "\"You've got to get up every morning with determination if you're going to go to bed with satisfaction\". - George Lorimer",
        "\"Don’t judge each day by the harvest you reap but by the seeds that you plant.\" - Robert Louis Stevenson",
        "\"Just one small positive thought in the morning can change your whole day.\" - Dalai Lama",
        "\"Don’t wish it were easier. Wish you were better.\" - Jim Rohn",
        "\"Whether you think you can, or you think you can’t – you’re right.\" - Henry Ford",
        "\"Do the hard jobs first. The easy jobs will take care of themselves.\" - Dale Carnegie",
        "\"Happiness is not in the mere possession of money; it lies in the joy of achievement, in the thrill of creative effort.\" - Franklin D. Roosevelt",
        "\"The future depends on what you do today.\" - Mahatma Gandhi",
        "\"The man who moves a mountain begins by carrying away small stones.\" - Confucius",
        "\"Things may come to those who wait, but only the things left by those who hustle.\" - Abraham Lincoln",
        "\"Start by doing what’s necessary, then what’s possible; and suddenly you are doing the impossible.\" - Saint Francis",
        "\"People rarely succeed unless they have fun in what they are doing.\" - Dale Carnegie",
        "\"To think too long about doing a thing often becomes its undoing.\" - Eva Young",
        "\"Talent means nothing, while experience, acquired in humility and with hard work, means everything.\" - Patrick Suskind",
        "\"You will never plough a field if you only turn it over in your mind.\" - Irish Proverb",
        "\"Don't wait. The time will never be just right.\" - Napoleon Hill",
        "\"Success seems to be connected with action. Successful people keep moving. They make mistakes, but they don’t quit.\" - Conrad Hilton",
        "\"You don’t have to see the whole staircase, just take the first step.\" - Martin Luther King, Jr.",
        "\"Motivation is a fire from within. If someone else tries to light that fire under you, chances are it will burn very briefly.\" - Stephen R. Covey",
        "\"Try not to become a person of success, but rather try to become a person of value.\" - Albert Einstein",
    );

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin,hrManager'])->only(['dashboard1']);
        $this->middleware(['employee:manager,employee'])->only(['dashboard2']);
    }

    public function dashboard1()
    {
        $departmentName = array();
        $employeeNumber = array();
        $departments = Department::all();
        foreach ($departments as $department) {
            $employees = User::where('department', $department->id)->count();
            if($employees != 0){
                array_push($departmentName, $department->departmentName);
                array_push($employeeNumber, $employees);
            }
        }
        $accountArrays = array(
            '01' => 0,
            '02' => 0,
            '03' => 0,
            '04' => 0,
            '05' => 0,
            '06' => 0,
            '07' => 0,
            '08' => 0,
            '09' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0
        );

        $accounts = User::where('created_at', '>=', ''.date('Y').'-01-01')->get();
        foreach ($accounts as $account) {
            $month = date('m', strtotime($account->created_at));
            $accountArrays[$month] = $accountArrays[$month] + 1;
        }

        $memos = Memo::where('memoStatus', 0)->orderBy('memoDate', 'ASC')->take(5)->get();

        if (Auth::user()->isAdmin()) {     
            $tasks = Task::with('getPersonInCharge')->where('status', 1)->orderBy('dueDate', 'ASC')->take(5)->get();
            $leaves = LeaveRequest::with('getLeaveType', 'getEmployee')->where('leaveStatus', 0)->orderBy('leaveStartDate', 'ASC')->take(5)->get();
            $claims = ClaimRequest::with('getClaimType', 'getEmployee')->where('claimStatus', 0)->orderBy('claimDate', 'ASC')->take(5)->get();
        } else {
            $tasks = Task::with('getPersonInCharge')->where('status', 1)->where('managerID', Auth::id())->orWhere('delegateManagerID', Auth::id())->orderBy('dueDate', 'ASC')->take(5)->get();
            $leaves = LeaveRequest::with('getLeaveType', 'getEmployee')->where('leaveStatus', 0)->where('managerID', Auth::id())->orWhere('delegateManagerID', Auth::id())->orderBy('leaveStartDate', 'ASC')->take(5)->get();
            $claims = ClaimRequest::with('getClaimType', 'getEmployee')->where('claimStatus', 0)->where('claimManager', Auth::id())->orderBy('claimDate', 'ASC')->take(5)->get();
        }

        $quotes = Arr::random($this->quotes);
        
        return view('dashboard.dashboard1', ['quotes' => $quotes, 'departmentName' => $departmentName, 'employeeNumber' => $employeeNumber, 'accountArrays' => $accountArrays, 'memos' => $memos, 'tasks' => $tasks, 'leaves' => $leaves, 'claims' => $claims]);
    }

    public function dashboard2()
    {
        if(Auth::user()->isManager()){
            $tasks = Task::with('getPersonInCharge')->where('status', 1)->where('managerID', Auth::id())->orWhere('delegateManagerID', Auth::id())->orderBy('dueDate', 'ASC')->take(5)->get();
            $leaves = LeaveRequest::with('getLeaveType', 'getEmployee')->where('leaveStatus', 0)->where('managerID', Auth::id())->orWhere('delegateManagerID', Auth::id())->orderBy('leaveStartDate', 'ASC')->take(5)->get();
            $claims = ClaimRequest::with('getClaimType', 'getEmployee')->where('claimStatus', 0)->where('claimManager', Auth::id())->orderBy('claimDate', 'ASC')->take(5)->get();
        }
        else{
            $tasks = Task::with('getPersonInCharge')->where('status', 1)->where('personInCharge', Auth::id())->orderBy('dueDate', 'ASC')->take(5)->get();
            $leaves = LeaveRequest::with('getLeaveType', 'getEmployee')->where('leaveStatus', 0)->where('employeeID', Auth::id())->orderBy('leaveStartDate', 'ASC')->take(5)->get();
            $claims = ClaimRequest::with('getClaimType', 'getEmployee')->where('claimStatus', 0)->where('claimEmployee', Auth::id())->orderBy('claimDate', 'ASC')->take(5)->get();
        }

        $trainingPrograms = TrainingProgram::with('getAttendees')->where('department', Auth::user()->department)->orWhereNull('department')->where('status', 0)->orderBy('dateAndTime', 'ASC')->take(5)->get();
        
        $quotes = Arr::random($this->quotes);

        return view('dashboard.dashboard2', ['quotes' => $quotes, 'tasks' => $tasks, 'leaves' => $leaves, 'claims' => $claims, 'trainingPrograms' => $trainingPrograms]);
    }
}
