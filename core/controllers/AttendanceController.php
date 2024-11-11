<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/views/AttendanceView.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/Controller.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/models/AttendanceModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/models/UserModel.php";

class AttendanceController extends Controller
{
    private AttendanceView $view;
    private AttendanceModel $attendance_model;
    private UserModel $user_model;

    public function __construct()
    {
        $this->view = new AttendanceView();
        $this->attendance_model = new AttendanceModel();
        $this->user_model = new UserModel();
    }

    public function index(): void
    {
        $this->view->index(
            $this->user_model->get_user_by_id($_COOKIE['user_id']), 
            $this->user_model->get_user_role($_COOKIE['user_id'])
        );
    }

    public function add(): void
    {
        $income_time = $_POST['income'];
        $outcome_time = $_POST['outcome'];
        $employee_id = $_POST['employee_id'];
        $date = date("Y-m-d");
        if (isset($_POST['date'])) 
        {
            $date = $_POST['date'];
        }
        $income = DateTime::createFromFormat("Y-m-d H:i", "$date $income_time");
        $outcome = DateTime::createFromFormat("Y-m-d H:i", "$date $outcome_time");
        $this->attendance_model->add_attendance($employee_id, $income, $outcome);
        header("location: http://10.174.246.199/report/");
    }
}