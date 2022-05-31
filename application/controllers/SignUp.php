<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SignUp extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->ion_auth->logged_in()) {
			redirect('dashboard');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');
	}

	/*public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}*/



	public function data()
	{
		$this->output_json($this->master->getDatastudent(), false);
	}

	public function index()
	{
		/*$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Student',
			'subjudul' => 'Add Student Data'
		];*/
		//$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('auth/sign_up');

	}


	public function validasi_student($method)
	{
		$std_id 	= $this->input->post('std_id', true);
		$nim 			= $this->input->post('nim', true);
		$email 			= $this->input->post('email', true);
		if ($method == 'add') {
			$u_nim = '|is_unique[student.nim]';
			$u_email = '|is_unique[student.email]';
		} else {
			$dbdata 	= $this->master->getstudentById($std_id);
			$u_nim		= $dbdata->nim === $nim ? "" : "|is_unique[student.nim]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[student.email]";
		}
		$this->form_validation->set_rules('nim', 'PID', 'required|numeric|trim|min_length[8]|max_length[12]' . $u_nim);
		$this->form_validation->set_rules('nama', 'Name', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('jenis_kelamin', 'Gender', 'required');
		$this->form_validation->set_rules('department', 'Dept.', 'required');
		$this->form_validation->set_rules('level', 'Class', 'required');

		$this->form_validation->set_message('required', '{field} field is required');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->validasi_student($method);

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'nim' => form_error('nim'),
					'nama' => form_error('nama'),
					'email' => form_error('email'),
					'jenis_kelamin' => form_error('jenis_kelamin'),
					'department' => form_error('department'),
					'level' => form_error('level'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'nim' 			=> $this->input->post('nim', true),
				'email' 		=> $this->input->post('email', true),
				'nama' 			=> $this->input->post('nama', true),
				'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
				'level_id' 		=> $this->input->post('level', true),
			];
			if ($method === 'add') {
				$action = $this->master->create('student', $input);
			} else if ($method === 'edit') {
				$id = $this->input->post('std_id', true);
				$action = $this->master->update('student', $input, 'std_id', $id);
			}

			if ($action) {
				$this->output_json(['status' => true]);
			} else {
				$this->output_json(['status' => false]);
			}
		}
	}

		public function load_department()
	{
		$data = $this->master->getdepartment();
		$this->output_json($data);
	}
		public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}
		public function level_by_department($id)
	{
		$data = $this->master->getlevelBydepartment($id);
		$this->output_json($data);
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getstudentById($id);
		$nama = explode(' ', $data->nama);
		$first_name = $nama[0];
		$last_name = end($nama);

		$username = $data->nim;
		$password = $data->nim;
		$email = $data->email;
		$additional_data = [
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('3'); // Sets user to lecturer.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'Username not available (already used).'
			];
		} else if ($this->ion_auth->email_check($email)) {
			$data = [
				'status' => false,
				'msg'	 => 'Email is not available (already in use).'
			];
		} else {
			$this->ion_auth->register($username, $password, $email, $additional_data, $group);
			$data = [
				'status'	=> true,
				'msg'	 => 'User created successfully. PID is used as a password at login.'
			];
		}
		$this->output_json($data);
	}

}
