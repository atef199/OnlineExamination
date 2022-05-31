<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends CI_Controller {

	public $mhs, $user;

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
		$this->load->helper('my');
		$this->load->model('Master_model', 'master');
		$this->load->model('question_model', 'question');
		$this->load->model('test_model', 'test');
		$this->form_validation->set_error_delimiters('','');

		$this->user = $this->ion_auth->user()->row();
		$this->mhs 	= $this->test->getIdstudent($this->user->username);
    }

    public function akses_lecturer()
    {
        if ( !$this->ion_auth->in_group('Lecturer') ){
			show_error('This page is specifically for lecturers to make an Online Test, <a href="'.base_url('dashboard').'">Back to main menu</a>', 403, 'Forbidden Access');
		}
    }

    public function akses_student()
    {
        if ( !$this->ion_auth->in_group('Student') ){
			show_error('This page is specifically for students taking the exam, <a href="'.base_url('dashboard').'">Back to main menu</a>', 403, 'Forbidden Access');
		}
    }

    public function output_json($data, $encode = true)
	{
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
	}
	
	public function json($id=null)
	{
        $this->akses_lecturer();

		$this->output_json($this->test->getDatatest($id), false);
	}

    public function master()
	{
        $this->akses_lecturer();
        $user = $this->ion_auth->user()->row();
        $data = [
			'user' => $user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Exam Data',
			'lecturer' => $this->test->getIdlecturer($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('test/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function add()
	{
		$this->akses_lecturer();
		
		$user = $this->ion_auth->user()->row();

        $data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Add Exam',
			'course'	=> $this->question->getcourselecturer($user->username),
			'lecturer'		=> $this->test->getIdlecturer($user->username),
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('test/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function edit($id)
	{
		$this->akses_lecturer();
		
		$user = $this->ion_auth->user()->row();

        $data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Edit Exam',
			'course'	=> $this->question->getcourselecturer($user->username),
			'lecturer'		=> $this->test->getIdlecturer($user->username),
			'test'		=> $this->test->gettestById($id),
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('test/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function convert_tgl($tgl)
	{
		$this->akses_lecturer();
		return date('Y-m-d H:i:s', strtotime($tgl));
	}

	public function validasi()
	{
		$this->akses_lecturer();
		
		$user 	= $this->ion_auth->user()->row();
		$lecturer 	= $this->test->getIdlecturer($user->username);
		$jml 	= $this->test->getJumlahquestion($lecturer->lecturer_id)->jml_question;
		$jml_a 	= $jml + 1; // If you don't understand, please read the user_guide codeigniter about form_validation in the less_than section

		$this->form_validation->set_rules('exam_name', 'Exam Name', 'required|alpha_numeric_spaces|max_length[50]');
		$this->form_validation->set_rules('questions_num', 'Number of Questions', "required|integer|less_than[{$jml_a}]|greater_than[0]", ['less_than' => "There are no sufficient questions, you only have {$jml} questions available in the question bank."]);
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'Completion Date', 'required');
		$this->form_validation->set_rules('time', 'Time', 'required|integer|max_length[4]|greater_than[0]');
		$this->form_validation->set_rules('exam_type', 'Random Question', 'required|in_list[Random,Sort]');
	}

	public function save()
	{
		$this->validasi();
		$this->load->helper('string');

		$method 		= $this->input->post('method', true);
		$lecturer_id 		= $this->input->post('lecturer_id', true);
		$course_id 		= $this->input->post('course_id', true);
		$exam_name 	= $this->input->post('exam_name', true);
		$questions_num 	= $this->input->post('questions_num', true);
		$start_date 		= $this->convert_tgl($this->input->post('start_date', 	true));
		$end_date	= $this->convert_tgl($this->input->post('end_date', true));
		$time			= $this->input->post('time', true);
		$exam_type			= $this->input->post('exam_type', true);
		$token 			= strtoupper(random_string('alpha', 5));

		if( $this->form_validation->run() === FALSE ){
			$data['status'] = false;
			$data['errors'] = [
				'exam_name' 	=> form_error('exam_name'),
				'questions_num' 	=> form_error('questions_num'),
				'start_date' 	=> form_error('start_date'),
				'end_date' 	=> form_error('end_date'),
				'time' 		=> form_error('time'),
				'exam_type' 		=> form_error('exam_type'),
			];
		}else{
			$input = [
				'exam_name' 	=> $exam_name,
				'questions_num' 	=> $questions_num,
				'start_date' 	=> $start_date,
				'end_date' 	=> $end_date,
				'time' 		=> $time,
				'exam_type' 		=> $exam_type,
			];
			if($method === 'add'){
				$input['lecturer_id']	= $lecturer_id;
				$input['course_id'] = $course_id;
				$input['token']		= $token;
				$action = $this->master->create('exam', $input);
			}else if($method === 'edit'){
				$exam_id = $this->input->post('exam_id', true);
				$action = $this->master->update('exam', $input, 'exam_id', $exam_id);
			}
			$data['status'] = $action ? TRUE : FALSE;
		}
		$this->output_json($data);
	}

	public function delete()
	{
		$this->akses_lecturer();
		$chk = $this->input->post('checked', true);
        if(!$chk){
            $this->output_json(['status'=>false]);
        }else{
            if($this->master->delete('exam', $chk, 'exam_id')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
	}

	public function refresh_token($id)
	{
		$this->load->helper('string');
		$data['token'] = strtoupper(random_string('alpha', 5));
		$refresh = $this->master->update('exam', $data, 'exam_id', $id);
		$data['status'] = $refresh ? TRUE : FALSE;
		$this->output_json($data);
	}

	/**
	 * BAGIAN MAHASISWA
	 */

	public function list_json()
	{
		$this->akses_student();
		
		$list = $this->test->getListtest($this->mhs->std_id, $this->mhs->level_id);
		$this->output_json($list, false);
	}
	
	public function list()
	{
		$this->akses_student();

		$user = $this->ion_auth->user()->row();
		
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'List Exam',
			'mhs' 		=> $this->test->getIdstudent($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('test/list');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function token($id)
	{
		$this->akses_student();
		$user = $this->ion_auth->user()->row();
		
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Token Exam',
			'mhs' 		=> $this->test->getIdstudent($user->username),
			'test'		=> $this->test->gettestById($id),
			'encrypted_id' => urlencode($this->encryption->encrypt($id))
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('test/token');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function cektoken()
	{
		$id = $this->input->post('exam_id', true);
		$token = $this->input->post('token', true);
		$cek = $this->test->gettestById($id);
		
		$data['status'] = $token === $cek->token ? TRUE : FALSE;
		$this->output_json($data);
	}

	public function encrypt()
	{
		$id = $this->input->post('id', true);
		$key = urlencode($this->encryption->encrypt($id));
		// $decrypted = $this->encryption->decrypt(rawurldecode($key));
		$this->output_json(['key'=>$key]);
	}

	public function index()
	{
		$this->akses_student();
		$key = $this->input->get('key', true);
		$id  = $this->encryption->decrypt(rawurldecode($key));
		
		$test 		= $this->test->gettestById($id);
		$question 		= $this->test->getquestion($id);
		
		$mhs		= $this->mhs;
		$std_exam 	= $this->test->Hsltest($id, $mhs->std_id);
	
		$cek_sudah_ikut = $std_exam->num_rows();

		if ($cek_sudah_ikut < 1) {
			$question_urut_ok 	= array();
			$i = 0;
			foreach ($question as $s) {
				$question_per = new stdClass();
				$question_per->q_id 		= $s->q_id;
				$question_per->question 		= $s->question;
				$question_per->file 		= $s->file;
				$question_per->file_type 	= $s->file_type;
				$question_per->ans_a 		= $s->ans_a;
				$question_per->ans_b 		= $s->ans_b;
				$question_per->ans_c 		= $s->ans_c;
				$question_per->ans_d 		= $s->ans_d;
				$question_per->ans_e 		= $s->ans_e;
				$question_per->right_ans 		= $s->right_ans;
				$question_urut_ok[$i] 		= $question_per;
				$i++;
			}
			$question_urut_ok 	= $question_urut_ok;
			$list_q_id	= "";
			$list_jw_question 	= "";
			if (!empty($question)) {
				foreach ($question as $d) {
					$list_q_id .= $d->q_id.",";
					$list_jw_question .= $d->q_id."::N,";
				}
			}
			$list_q_id 	= substr($list_q_id, 0, -1);
			$list_jw_question 	= substr($list_jw_question, 0, -1);
			$time_selesai 	= date('Y-m-d H:i:s', strtotime("+{$test->time} minute"));
			$time_mulai		= date('Y-m-d H:i:s');

			$input = [
				'exam_id' 		=> $id,
				'std_id'	=> $mhs->std_id,
				'q_list'		=> $list_q_id,
				'answers' 	=> $list_jw_question,
				'correct_ans_num'		=> 0,
				'mark'			=> 0,
				'max_mark'	=> 0,
				'start_date'		=> $time_mulai,
				'end_date'	=> $time_selesai,
				'status'		=> 'Y'
			];
			$this->master->create('std_exam', $input);

			// Setelah insert wajib refresh dulu
			redirect('test/?key='.urlencode($key), 'location', 301);
		}
		
		$q_question = $std_exam->row();
		
		$urut_question 		= explode(",", $q_question->answers);
		$question_urut_ok	= array();
		for ($i = 0; $i < sizeof($urut_question); $i++) {
			$pc_urut_question	= explode(":",$urut_question[$i]);
			$pc_urut_question1 	= empty($pc_urut_question[1]) ? "''" : "'{$pc_urut_question[1]}'";
			$ambil_question 	= $this->test->ambilquestion($pc_urut_question1, $pc_urut_question[0]);
			$question_urut_ok[] = $ambil_question; 
		}

		$detail_tes = $q_question;
		$question_urut_ok = $question_urut_ok;

		$pc_answers = explode(",", $detail_tes->answers);
		$arr_jawab = array();
		foreach ($pc_answers as $v) {
			$pc_v 	= explode(":", $v);
			$idx 	= $pc_v[0];
			$val 	= $pc_v[1];
			$rg 	= $pc_v[2];

			$arr_jawab[$idx] = array("j"=>$val,"r"=>$rg);
		}

		$arr_opsi = array("a","b","c","d","e");
		$html = '';
		$no = 1;
		if (!empty($question_urut_ok)) {
			foreach ($question_urut_ok as $s) {
				$path = 'uploads/question_bank/';
				$vrg = $arr_jawab[$s->q_id]["r"] == "" ? "N" : $arr_jawab[$s->q_id]["r"];
				$html .= '<input type="hidden" name="q_id_'.$no.'" value="'.$s->q_id.'">';
				$html .= '<input type="hidden" name="rg_'.$no.'" id="rg_'.$no.'" value="'.$vrg.'">';
				$html .= '<div class="step" id="widget_'.$no.'">';

				$html .= '<div class="text-center"><div class="w-25">'.tampil_media($path.$s->file).'</div></div>'.$s->question.'<div class="funkyradio">';
				for ($j = 0; $j < $this->config->item('jml_opsi'); $j++) {
					$opsi 			= "ans_".$arr_opsi[$j];
					$file 			= "file_".$arr_opsi[$j];
					$checked 		= $arr_jawab[$s->q_id]["j"] == strtoupper($arr_opsi[$j]) ? "checked" : "";
					$pilihan_opsi 	= !empty($s->$opsi) ? $s->$opsi : "";
					$tampil_media_opsi = (is_file(base_url().$path.$s->$file) || $s->$file != "") ? tampil_media($path.$s->$file) : "";
					$html .= '<div class="funkyradio-success" onclick="return simpan_sementara();">
						<input type="radio" id="ans_'.strtolower($arr_opsi[$j]).'_'.$s->q_id.'" name="ans_'.$no.'" value="'.strtoupper($arr_opsi[$j]).'" '.$checked.'> <label for="ans_'.strtolower($arr_opsi[$j]).'_'.$s->q_id.'"><div class="huruf_opsi">'.$arr_opsi[$j].'</div> <p>'.$pilihan_opsi.'</p><div class="w-25">'.$tampil_media_opsi.'</div></label></div>';
				}
				$html .= '</div></div>';
				$no++;
			}
		}

		// Enkripsi Id Tes
		$id_tes = $this->encryption->encrypt($detail_tes->id);

		$data = [
			'user' 		=> $this->user,
			'mhs'		=> $this->mhs,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Exam Sheet',
			'question'		=> $detail_tes,
			'no' 		=> $no,
			'html' 		=> $html,
			'id_tes'	=> $id_tes
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('test/sheet');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function simpan_satu()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);
		
		$input 	= $this->input->post(null, true);
		$answers 	= "";
		for ($i = 1; $i < $input['jml_question']; $i++) {
			$_tjawab 	= "ans_".$i;
			$_tidquestion 	= "q_id_".$i;
			$_ragu 		= "rg_".$i;
			$right_ans_ 	= empty($input[$_tjawab]) ? "" : $input[$_tjawab];
			$answers	.= "".$input[$_tidquestion].":".$right_ans_.":".$input[$_ragu].",";
		}
		$answers	= substr($answers, 0, -1);
		$d_simpan = [
			'answers' => $answers
		];
		
		// Simpan right_ans
		$this->master->update('std_exam', $d_simpan, 'id', $id_tes);
		$this->output_json(['status'=>true]);
	}

	public function simpan_akhir()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);
		
		// Get Jawaban
		$answers = $this->test->getJawaban($id_tes);

		// Pecah Jawaban
		$pc_right_ans = explode(",", $answers);
		
		$jumlah_benar 	= 0;
		$jumlah_salah 	= 0;
		$jumlah_ragu  	= 0;
		$max_mark 	= 0;
		$total_weight	= 0;
		$questions_num	= sizeof($pc_right_ans);

		foreach ($pc_right_ans as $jwb) {
			$pc_dt 		= explode(":", $jwb);
			$q_id 	= $pc_dt[0];
			$right_ans 	= $pc_dt[1];
			$ragu 		= $pc_dt[2];

			$cek_jwb 	= $this->question->getquestionById($q_id);
			$total_weight = $total_weight + $cek_jwb->weight;

			$right_ans == $cek_jwb->right_ans ? $jumlah_benar++ : $jumlah_salah++;
		}

		$mark = ($jumlah_benar / $questions_num)  * 100;
		$max_mark = ($total_weight / $questions_num)  * 100;

		$d_update = [
			'correct_ans_num'		=> $jumlah_benar,
			'mark'			=> number_format(floor($mark), 0),
			'max_mark'	=> number_format(floor($max_mark), 0),
			'status'		=> 'N'
		];

		$this->master->update('std_exam', $d_update, 'id', $id_tes);
		$this->output_json(['status'=>TRUE, 'data'=>$d_update, 'id'=>$id_tes]);
	}
}