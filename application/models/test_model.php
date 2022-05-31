<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test_model extends CI_Model {
    
    public function getDatatest($id)
    {
        $this->datatables->select('a.exam_id, a.token, a.exam_name, b.course_name, a.questions_num, CONCAT(a.start_date, " <br/> (", a.time, " Minute)") as time, a.exam_type');
        $this->datatables->from('exam a');
        $this->datatables->join('course b', 'a.course_id = b.course_id');
        if($id!==null){
            $this->datatables->where('lecturer_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListtest($id, $level)
    {
        $this->datatables->select("a.exam_id, e.lecturer_name, d.level_name, a.exam_name, b.course_name, a.questions_num, CONCAT(a.start_date, ' <br/> (', a.time, ' Minute)') as time,  (SELECT COUNT(id) FROM std_exam h WHERE h.std_id = {$id} AND h.exam_id = a.exam_id) AS ada");
        $this->datatables->from('exam a');
        $this->datatables->join('course b', 'a.course_id = b.course_id');
        $this->datatables->join('level_lecturer c', "a.lecturer_id = c.lecturer_id");
        $this->datatables->join('level d', 'c.level_id = d.level_id');
        $this->datatables->join('lecturer e', 'e.lecturer_id = c.lecturer_id');
        $this->datatables->where('d.level_id', $level);
        return $this->datatables->generate();
    }

    public function gettestById($id)
    {
        $this->db->select('*');
        $this->db->from('exam a');
        $this->db->join('lecturer b', 'a.lecturer_id=b.lecturer_id');
        $this->db->join('course c', 'a.course_id=c.course_id');
        $this->db->where('exam_id', $id);
        return $this->db->get()->row();
    }

    public function getIdlecturer($nip)
    {
        $this->db->select('lecturer_id, lecturer_name')->from('lecturer')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getJumlahquestion($lecturer)
    {
        $this->db->select('COUNT(q_id) as jml_question');
        $this->db->from('tbl_question');
        $this->db->where('lecturer_id', $lecturer);
        return $this->db->get()->row();
    }

    public function getIdstudent($nim)
    {
        $this->db->select('*');
        $this->db->from('student a');
        $this->db->join('level b', 'a.level_id=b.level_id');
        $this->db->join('department c', 'b.dep_id=c.dep_id');
        $this->db->where('nim', $nim);
        return $this->db->get()->row();
    }

    public function Hsltest($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(end_date) as time_habis');
        $this->db->from('std_exam');
        $this->db->where('exam_id', $id);
        $this->db->where('std_id', $mhs);
        return $this->db->get();
    }

    public function getquestion($id)
    {
        $test = $this->gettestById($id);
        $order = $test->exam_type==="Random" ? 'rand()' : 'q_id';

        $this->db->select('q_id, question, file, file_type, ans_a, ans_b, ans_c, ans_d, ans_e, right_ans');
        $this->db->from('tbl_question');
        $this->db->where('lecturer_id', $test->lecturer_id);
        $this->db->where('course_id', $test->course_id);
        $this->db->order_by($order);
        $this->db->limit($test->questions_num);
        return $this->db->get()->result();
    }

    public function ambilquestion($pc_urut_question1, $pc_urut_question_arr)
    {
        $this->db->select("*, {$pc_urut_question1} AS right_ans");
        $this->db->from('tbl_question');
        $this->db->where('q_id', $pc_urut_question_arr);
        return $this->db->get()->row();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('answers');
        $this->db->from('std_exam');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->answers;
    }

    public function getHasiltest($nip = null)
    {
        $this->datatables->select('b.exam_id, b.exam_name, b.questions_num, CONCAT(b.time, " Minute") as time, b.start_date');
        $this->datatables->select('c.course_name, d.lecturer_name');
        $this->datatables->from('std_exam a');
        $this->datatables->join('exam b', 'a.exam_id = b.exam_id');
        $this->datatables->join('course c', 'b.course_id = c.course_id');
        $this->datatables->join('lecturer d', 'b.lecturer_id = d.lecturer_id');
        $this->datatables->group_by('b.exam_id');
        if($nip !== null){
            $this->datatables->where('d.nip', $nip);
        }
        return $this->datatables->generate();
    }

    public function HsltestById($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.nama, b.level_name, c.dep_name, d.correct_ans_num, d.mark');
        $this->$db->from('student a');
        $this->$db->join('level b', 'a.level_id=b.level_id');
        $this->$db->join('department c', 'b.dep_id=c.dep_id');
        $this->$db->join('std_exam d', 'a.std_id=d.std_id');
        $this->$db->where(['d.exam_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingNilai($id)
    {
        $this->db->select_min('mark', 'min_mark');
        $this->db->select_max('mark', 'max_mark');
        $this->db->select_avg('FORMAT(FLOOR(mark),0)', 'avg_mark');
        $this->db->where('exam_id', $id);
        return $this->db->get('std_exam')->row();
    }

}