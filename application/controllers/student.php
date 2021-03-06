<?php
defined('BASEPATH') or exit('No direct script access allowed');

class student extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Only Administrators are authorized to access this page, <a href="' . base_url('dashboard') . '">Back to main menu</a>', 403, 'Forbidden Access');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Student',
			'subjudul' => 'Data Student'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/student/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDatastudent(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Student',
			'subjudul' => 'Add Student Data'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/student/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$mhs = $this->master->getstudentById($id);
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Student',
			'subjudul'	=> 'Edit Student Data',
			'department'	=> $this->master->getdepartment(),
			'level'		=> $this->master->getlevelBydepartment($mhs->dep_id),
			'student' => $mhs
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/student/edit');
		$this->load->view('_templates/dashboard/_footer.php');
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

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('student', $chk, 'std_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
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

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Student',
			'subjudul' => 'Import Student Data',
			'level' => $this->master->getAlllevel()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/student/import');
		$this->load->view('_templates/dashboard/_footer');
	}
	public function preview()
	{
		$config['upload_path']		= './uploads/import/';
		$config['allowed_types']	= 'xls|xlsx|csv';
		$config['max_size']			= 2048;
		$config['encrypt_name']		= true;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('upload_file')) {
			$error = $this->upload->display_errors();
			echo $error;
			die;
		} else {
			$file = $this->upload->data('full_path');
			$ext = $this->upload->data('file_ext');

			switch ($ext) {
				case '.xlsx':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					break;
				case '.xls':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					break;
				case '.csv':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					break;
				default:
					echo "unknown file ext";
					die;
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'nim' => $sheetData[$i][0],
					'nama' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'jenis_kelamin' => $sheetData[$i][3],
					'level_id' => $sheetData[$i][4]
				];
			}

			unlink($file);

			$this->import($data);
		}
	}

	public function do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		foreach ($input as $d) {
			$data[] = [
				'nim' => $d->nim,
				'nama' => $d->nama,
				'email' => $d->email,
				'jenis_kelamin' => $d->jenis_kelamin,
				'level_id' => $d->level_id
			];
		}

		$save = $this->master->create('student', $data, true);
		if ($save) {
			redirect('student');
		} else {
			redirect('student/import');
		}
	}
}
