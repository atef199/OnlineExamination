<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class exam_results extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		
		$this->load->library(['datatables']);// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->load->model('test_model', 'test');
		
		$this->user = $this->ion_auth->user()->row();
	}

	public function output_json($data, $encode = true)
	{
		if($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function data()
	{
		$nip_lecturer = null;
		
		if( $this->ion_auth->in_group('Lecturer') ) {
			$nip_lecturer = $this->user->username;
		}

		$this->output_json($this->test->getHasiltest($nip_lecturer), false);
	}

	public function NilaiMhs($id)
	{
		$this->output_json($this->test->HsltestById($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Exam results',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('test/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function detail($id)
	{
		$test = $this->test->gettestById($id);
		$mark = $this->test->bandingNilai($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Detail Exam results',
			'test'	=> $test,
			'mark'	=> $mark
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('test/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->test->getIdstudent($this->user->username);
		$hasil 	= $this->test->Hsltest($id, $mhs->std_id)->row();
		$test 	= $this->test->gettestById($id);
		
		$data = [
			'test' => $test,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];
		
		$this->load->view('test/cetak', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$test = $this->test->gettestById($id);
		$mark = $this->test->bandingNilai($id);
		$hasil = $this->test->HsltestById($id)->result();

		$data = [
			'test'	=> $test,
			'mark'	=> $mark,
			'hasil'	=> $hasil
		];

		$this->load->view('test/cetak_detail', $data);
	}
	
}