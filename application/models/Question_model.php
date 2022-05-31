<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_model extends CI_Model {
    
    public function getDataquestion($id, $lecturer)
    {
        $this->datatables->select('a.q_id, a.question, FROM_UNIXTIME(a.created_on) as created_on, FROM_UNIXTIME(a.updated_on) as updated_on, b.course_name, c.lecturer_name');
        $this->datatables->from('tbl_question a');
        $this->datatables->join('course b', 'b.course_id=a.course_id');
        $this->datatables->join('lecturer c', 'c.lecturer_id=a.lecturer_id');
        if ($id!==null && $lecturer===null) {
            $this->datatables->where('a.course_id', $id);            
        }else if($id!==null && $lecturer!==null){
            $this->datatables->where('a.lecturer_id', $lecturer);
        }
        return $this->datatables->generate();
    }

    public function getquestionById($id)
    {
        return $this->db->get_where('tbl_question', ['q_id' => $id])->row();
    }

    public function getcourselecturer($nip)
    {
        $this->db->select('lecturer.course_id, course_name, lecturer_id, lecturer_name');
        $this->db->join('course', 'course.course_id=lecturer.course_id');
        $this->db->from('lecturer')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getAlllecturer()
    {
        $this->db->select('*');
        $this->db->from('lecturer a');
        $this->db->join('course b', 'a.course_id=b.course_id');
        return $this->db->get()->result();
    }
}