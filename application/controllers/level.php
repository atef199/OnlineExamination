<?php
defined('BASEPATH') or exit('No direct script access allowed');

class level extends CI_Controller
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
			'judul'	=> 'Level',
			'subjudul' => 'Data Level'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/level/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDatalevel(), false);
	}

	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Add Level',
			'subjudul'	=> 'Add Data Level',
			'banyak'	=> $this->input->post('banyak', true),
			'department'	=> $this->master->getAlldepartment()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/level/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			redirect('admin/level');
		} else {
			$level = $this->master->getlevelById($chk);
			$data = [
				'user' 		=> $this->ion_auth->user()->row(),
				'judul'		=> 'Edit Level',
				'subjudul'	=> 'Edit Data Level',
				'department'	=> $this->master->getAlldepartment(),
				'level'		=> $level
			];
			$this->load->view('_templates/dashboard/_header.php', $data);
			$this->load->view('master/level/edit');
			$this->load->view('_templates/dashboard/_footer.php');
		}
	}

	public function save()
	{
		$rows = count($this->input->post('level_name', true));
		$mode = $this->input->post('mode', true);
		for ($i = 1; $i <= $rows; $i++) {
			$level_name 	= 'level_name[' . $i . ']';
			$dep_id 	= 'dep_id[' . $i . ']';
			$this->form_validation->set_rules($level_name, 'Class', 'required');
			$this->form_validation->set_rules($dep_id, 'Dept.', 'required');
			$this->form_validation->set_message('required', '{field} Required');

			if ($this->form_validation->run() === FALSE) {
				$error[] = [
					$level_name 	=> form_error($level_name),
					$dep_id 	=> form_error($dep_id),
				];
				$status = FALSE;
			} else {
				if ($mode == 'add') {
					$insert[] = [
						'level_name' 	=> $this->input->post($level_name, true),
						'dep_id' 	=> $this->input->post($dep_id, true)
					];
				} else if ($mode == 'edit') {
					$update[] = array(
						'level_id'		=> $this->input->post('level_id[' . $i . ']', true),
						'level_name' 	=> $this->input->post($level_name, true),
						'dep_id' 	=> $this->input->post($dep_id, true)
					);
				}
				$status = TRUE;
			}
		}
		if ($status) {
			if ($mode == 'add') {
				$this->master->create('level', $insert, true);
				$data['insert']	= $insert;
			} else if ($mode == 'edit') {
				$this->master->update('level', $update, 'level_id', null, true);
				$data['update'] = $update;
			}
		} else {
			if (isset($error)) {
				$data['errors'] = $error;
			}
		}
		$data['status'] = $status;
		$this->output_json($data);
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('level', $chk, 'level_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function level_by_department($id)
	{
		$data = $this->master->getlevelBydepartment($id);
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Level',
			'subjudul' => 'Import Level',
			'department' => $this->master->getAlldepartment()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/level/import');
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
					'level' => $sheetData[$i][0],
					'department' => $sheetData[$i][1]
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
			$data[] = ['level_name' => $d->level, 'dep_id' => $d->department];
		}

		$save = $this->master->create('level', $data, true);
		if ($save) {
			redirect('level');
		} else {
			redirect('level/import');
		}
	}
}
