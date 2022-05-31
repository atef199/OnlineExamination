<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class question extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}else if ( !$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Lecturer') ){
			show_error('Only Administrators and lecturers are authorized to access this page, <a href="'.base_url('dashboard').'">Back to main menu</a>', 403, 'Forbidden Access');
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
		$this->load->helper('my');// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->load->model('question_model', 'question');
		$this->form_validation->set_error_delimiters('','');
	}

	public function output_json($data, $encode = true)
	{
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
	{
        $user = $this->ion_auth->user()->row();
		$data = [
			'user' => $user,
			'judul'	=> 'Question',
			'subjudul'=> 'Question Bank'
        ];
        
        if($this->ion_auth->is_admin()){
            //Jika admin maka tampilkan semua course
            $data['course'] = $this->master->getAllcourse();
        }else{
            //Jika bukan maka course dipilih otomatis sesuai course lecturer
            $data['course'] = $this->question->getcourselecturer($user->username);
        }

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('question/data');
		$this->load->view('_templates/dashboard/_footer.php');
    }
    
    public function detail($id)
    {
        $user = $this->ion_auth->user()->row();
		$data = [
			'user'      => $user,
			'judul'	    => 'Question',
            'subjudul'  => 'Edit Question',
            'question'      => $this->question->getquestionById($id),
        ];

        $this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('question/detail');
		$this->load->view('_templates/dashboard/_footer.php');
    }
    
    public function add()
	{
        $user = $this->ion_auth->user()->row();
		$data = [
			'user'      => $user,
			'judul'	    => 'Question',
            'subjudul'  => 'Create Questions'
        ];

        if($this->ion_auth->is_admin()){
            //Jika admin maka tampilkan semua course
            $data['lecturer'] = $this->question->getAlllecturer();
        }else{
            //Jika bukan maka course dipilih otomatis sesuai course lecturer
            $data['lecturer'] = $this->question->getcourselecturer($user->username);
        }

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('question/add');
		$this->load->view('_templates/dashboard/_footer.php');
    }

    public function edit($id)
	{
		$user = $this->ion_auth->user()->row();
		$data = [
			'user'      => $user,
			'judul'	    => 'Question',
            'subjudul'  => 'Edit Question',
            'question'      => $this->question->getquestionById($id),
        ];
        
        if($this->ion_auth->is_admin()){
            //Jika admin maka tampilkan semua course
            $data['lecturer'] = $this->question->getAlllecturer();
        }else{
            //Jika bukan maka course dipilih otomatis sesuai course lecturer
            $data['lecturer'] = $this->question->getcourselecturer($user->username);
        }

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('question/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data($id=null, $lecturer=null)
	{
		$this->output_json($this->question->getDataquestion($id, $lecturer), false);
    }

    public function validasi()
    {
        if($this->ion_auth->is_admin()){
            $this->form_validation->set_rules('lecturer_id', 'Lecturer', 'required');
        }
        // $this->form_validation->set_rules('question', 'question', 'required');
        // $this->form_validation->set_rules('right_ans_a', 'Jawaban A', 'required');
        // $this->form_validation->set_rules('right_ans_b', 'Jawaban B', 'required');
        // $this->form_validation->set_rules('right_ans_c', 'Jawaban C', 'required');
        // $this->form_validation->set_rules('right_ans_d', 'Jawaban D', 'required');
        // $this->form_validation->set_rules('right_ans_e', 'Jawaban E', 'required');
        $this->form_validation->set_rules('right_ans', 'Answer key', 'required');
        $this->form_validation->set_rules('weight', 'Question Weight', 'required|max_length[2]');
    }

    public function file_config()
    {
        $allowed_type 	= [
            "image/jpeg", "image/jpg", "image/png", "image/gif",
            "audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
            "video/mp4", "application/octet-stream"
        ];
        $config['upload_path']      = FCPATH.'uploads/question_bank/';
        $config['allowed_types']    = 'jpeg|jpg|png|gif|mpeg|mpg|mpeg3|mp3|wav|wave|mp4';
        $config['encrypt_name']     = TRUE;
        
        return $this->load->library('upload', $config);
    }
    
    public function save()
    {
        $method = $this->input->post('method', true);
        $this->validasi();
        $this->file_config();

        
        if($this->form_validation->run() === FALSE){
            $method==='add'? $this->add() : $this->edit();
        }else{
            $data = [
                'question'      => $this->input->post('question', true),
                'right_ans'   => $this->input->post('right_ans', true),
                'weight'     => $this->input->post('weight', true),
            ];
            
            $abjad = ['a', 'b', 'c', 'd', 'e'];
            
            // Inputan Opsi
            foreach ($abjad as $abj) {
                $data['ans_'.$abj]    = $this->input->post('right_ans_'.$abj, true);
            }

            $i = 0;
            foreach ($_FILES as $key => $val) {
                $img_src = FCPATH.'uploads/question_bank/';
                $getquestion = $this->question->getquestionById($this->input->post('q_id', true));
                
                $error = '';
                if($key === 'file_question'){
                    if(!empty($_FILES['file_question']['name'])){
                        if (!$this->upload->do_upload('file_question')){
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'File Ques. Error');
                            exit();
                        }else{
                            if($method === 'edit'){
                                if(!unlink($img_src.$getquestion->file)){
                                    show_error('Error when deleting image <br/>'.var_dump($getquestion), 500, 'Image Editing Error');
                                    exit();
                                }
                            }
                            $data['file'] = $this->upload->data('file_name');
                            $data['file_type'] = $this->upload->data('file_type');
                        }
                    }
                }else{
                    $file_abj = 'file_'.$abjad[$i];
                    if(!empty($_FILES[$file_abj]['name'])){    
                        if (!$this->upload->do_upload($key)){
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'Option Files '.strtoupper($abjad[$i]).' Error');
                            exit();
                        }else{
                            if($method === 'edit'){
                                if(!unlink($img_src.$getquestion->$file_abj)){
                                    show_error('Error when deleting image', 500, 'Image Editing Error');
                                    exit();
                                }
                            }
                            $data[$file_abj] = $this->upload->data('file_name');
                        }
                    }
                    $i++;
                }
            }
                
            if($this->ion_auth->is_admin()){
                $pecah = $this->input->post('lecturer_id', true);
                $pecah = explode(':', $pecah);
                $data['lecturer_id'] = $pecah[0];
                $data['course_id'] = end($pecah);
            }else{
                $data['lecturer_id'] = $this->input->post('lecturer_id', true);
                $data['course_id'] = $this->input->post('course_id', true);
            }

            if($method==='add'){
                //push array
                $data['created_on'] = time();
                $data['updated_on'] = time();
                //insert data
                $this->master->create('tbl_question', $data);
            }else if($method==='edit'){
                //push array
                $data['updated_on'] = time();
                //update data
                $q_id = $this->input->post('q_id', true);
                $this->master->update('tbl_question', $data, 'q_id', $q_id);
            }else{
                show_error('Method unknown', 404);
            }
            redirect('question');
        }
    }

    public function delete()
    {
        $chk = $this->input->post('checked', true);
        
        // Delete File
        foreach($chk as $id){
            $abjad = ['a', 'b', 'c', 'd', 'e'];
            $path = FCPATH.'uploads/question_bank/';
            $question = $this->question->getquestionById($id);
            // Hapus File question
            if(!empty($question->file)){
                if(file_exists($path.$question->file)){
                    unlink($path.$question->file);
                }
            }
            //Hapus File Opsi
            $i = 0; //index
            foreach ($abjad as $abj) {
                $file_opsi = 'file_'.$abj;
                if(!empty($question->$file_opsi)){
                    if(file_exists($path.$question->$file_opsi)){
                        unlink($path.$question->$file_opsi);
                    }
                }
            }
        }

        if(!$chk){
            $this->output_json(['status'=>false]);
        }else{
            if($this->master->delete('tbl_question', $chk, 'q_id')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
    }
}