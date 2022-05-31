<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->model('Dashboard_model', 'dashboard');
		$this->user = $this->ion_auth->user()->row();
	}

	public function admin_box()
	{
		$box = [
			[
				'box' 		=> 'yellow',
				'total' 	=> $this->dashboard->total('department'),
				'title'		=> 'department',
				'text'      => 'Department',
				'icon'		=> 'th-large'
			],
			[
				'box' 		=> 'green',
				'total' 	=> $this->dashboard->total('level'),
				'title'		=> 'level',
				'text'      => 'Class',
				'icon'		=> 'building-o'
			],
			[
				'box' 		=> 'blue',
				'total' 	=> $this->dashboard->total('lecturer'),
				'title'		=> 'lecturer',
				'text'      => 'Lecturer',
				'icon'		=> 'users'
			],
			[
				'box' 		=> 'red',
				'total' 	=> $this->dashboard->total('student'),
				'title'		=> 'student',
				'text'      => 'Student',
				'icon'		=> 'graduation-cap'
			],
			[
				'box' 		=> 'maroon',
				'total' 	=> $this->dashboard->total('course'),
				'title'		=> 'course',
				'text'      => 'Course',
				'icon'		=> 'th'
			],
			[
				'box' 		=> 'aqua',
				'total' 	=> $this->dashboard->total('tbl_question'),
				'title'		=> 'question',
				'text'      => 'Questions',
				'icon'		=> 'file-text'
			],
			[
				'box' 		=> 'purple',
				'total' 	=> $this->dashboard->total('std_exam'),
				'title'		=> 'exam_results',
				'text'      => 'Results Generated',
				'icon'		=> 'file'
			],
			[
				'box' 		=> 'olive',
				'total' 	=> $this->dashboard->total('users'),
				'title'		=> 'users',
				'text'      => 'System Users',
				'icon'		=> 'key'
			],
		];
		$info_box = json_decode(json_encode($box), FALSE);
		return $info_box;
	}

	public function index()
	{
		$user = $this->user;
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Dashboard',
			'subjudul'	=> 'Application Data',
		];

		if ( $this->ion_auth->is_admin() ) {
			$data['info_box'] = $this->admin_box();
		} elseif ( $this->ion_auth->in_group('Lecturer') ) {
			$course = ['course' => 'lecturer.course_id=course.course_id'];
			$data['lecturer'] = $this->dashboard->get_where('lecturer', 'nip', $user->username, $course)->row();

			$level = ['level' => 'level_lecturer.level_id=level.level_id'];
			$data['level'] = $this->dashboard->get_where('level_lecturer', 'lecturer_id' , $data['lecturer']->lecturer_id, $level, ['level_name'=>'ASC'])->result();
		}else{
			$join = [
				'level b' 	=> 'a.level_id = b.level_id',
				'department c'	=> 'b.dep_id = c.dep_id'
			];
			$data['student'] = $this->dashboard->get_where('student a', 'nim', $user->username, $join)->row();
		}

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('dashboard');
		$this->load->view('_templates/dashboard/_footer.php');
	}
}