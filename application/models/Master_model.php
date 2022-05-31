<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public function __construct()
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    }

    public function create($table, $data, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->insert($table, $data);
        } else {
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->update($table, $data, array($pk => $id));
        } else {
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /**
     * Data level
     */

    public function getDatalevel()
    {
        $this->datatables->select('level_id, level_name, level.dep_id, dep_name');
        $this->datatables->from('level');
        $this->datatables->join('department', 'level.dep_id=department.dep_id');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'level_id, level_name, dep_id, dep_name');
        return $this->datatables->generate();
    }

    public function getlevelById($id)
    {
        $this->db->where_in('level_id', $id);
        $this->db->order_by('level_name');
        $query = $this->db->get('level')->result();
        return $query;
    }

    /**
     * Data department
     */

    public function getDatadepartment()
    {
        $this->datatables->select('dep_id, dep_name');
        $this->datatables->from('department');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'dep_id, dep_name');
        return $this->datatables->generate();
    }

    public function getdepartmentById($id)
    {
        $this->db->where_in('dep_id', $id);
        $this->db->order_by('dep_name');
        $query = $this->db->get('department')->result();
        return $query;
    }

    /**
     * Data student
     */

    public function getDatastudent()
    {
        $this->datatables->select('a.std_id, a.nama, a.nim, a.email, b.level_name, c.dep_name');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.nim) AS ada');
        $this->datatables->from('student a');
        $this->datatables->join('level b', 'a.level_id=b.level_id');
        $this->datatables->join('department c', 'b.dep_id=c.dep_id');
        return $this->datatables->generate();
    }

    public function getstudentById($id)
    {
        $this->db->select('*');
        $this->db->from('student');
        $this->db->join('level', 'student.level_id=level.level_id');
        $this->db->join('department', 'level.dep_id=department.dep_id');
        $this->db->where(['std_id' => $id]);
        return $this->db->get()->row();
    }

    public function getdepartment()
    {
        $this->db->select('level.dep_id, dep_name');
        $this->db->from('level');
        $this->db->join('department', 'level.dep_id=department.dep_id');
        $this->db->order_by('dep_name', 'ASC');
        $this->db->group_by('dep_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAlldepartment($id = null)
    {
        if ($id === null) {
            $this->db->order_by('dep_name', 'ASC');
            return $this->db->get('department')->result();
        } else {
            $this->db->select('dep_id');
            $this->db->from('department_course');
            $this->db->where('course_id', $id);
            $department = $this->db->get()->result();
            $dep_id = [];
            foreach ($department as $j) {
                $dep_id[] = $j->dep_id;
            }
            if ($dep_id === []) {
                $dep_id = null;
            }
            
            $this->db->select('*');
            $this->db->from('department');
            $this->db->where_not_in('dep_id', $dep_id);
            $course = $this->db->get()->result();
            return $course;
        }
    }

    public function getlevelBydepartment($id)
    {
        $query = $this->db->get_where('level', array('dep_id'=>$id));
        return $query->result();
    }

    /**
     * Data lecturer
     */

    public function getDatalecturer()
    {
        $this->datatables->select('a.lecturer_id,a.nip, a.lecturer_name, a.email, a.course_id, b.course_name, (SELECT COUNT(id) FROM users WHERE username = a.nip OR email = a.email) AS ada');
        $this->datatables->from('lecturer a');
        $this->datatables->join('course b', 'a.course_id=b.course_id');
        return $this->datatables->generate();
    }

    public function getlecturerById($id)
    {
        $query = $this->db->get_where('lecturer', array('lecturer_id'=>$id));
        return $query->row();
    }

    /**
     * Data course
     */

    public function getDatacourse()
    {
        $this->datatables->select('course_id, course_name');
        $this->datatables->from('course');
        return $this->datatables->generate();
    }

    public function getAllcourse()
    {
        return $this->db->get('course')->result();
    }

    public function getcourseById($id, $single = false)
    {
        if ($single === false) {
            $this->db->where_in('course_id', $id);
            $this->db->order_by('course_name');
            $query = $this->db->get('course')->result();
        } else {
            $query = $this->db->get_where('course', array('course_id'=>$id))->row();
        }
        return $query;
    }

    /**
     * Data level lecturer
     */

    public function getlevellecturer()
    {
        $this->datatables->select('level_lecturer.id, lecturer.lecturer_id, lecturer.nip, lecturer.lecturer_name, GROUP_CONCAT(level.level_name) as level');
        $this->datatables->from('level_lecturer');
        $this->datatables->join('level', 'level.level_id=level_lecturer.level_id');
        $this->datatables->join('lecturer', 'level_lecturer.lecturer_id=lecturer.lecturer_id');
        $this->datatables->group_by('lecturer.lecturer_name');
        return $this->datatables->generate();
    }

    public function getAlllecturer($id = null)
    {
        $this->db->select('lecturer_id');
        $this->db->from('level_lecturer');
        if ($id !== null) {
            $this->db->where_not_in('lecturer_id', [$id]);
        }
        $lecturer = $this->db->get()->result();
        $lecturer_id = [];
        foreach ($lecturer as $d) {
            $lecturer_id[] = $d->lecturer_id;
        }
        if ($lecturer_id === []) {
            $lecturer_id = null;
        }

        $this->db->select('lecturer_id, nip, lecturer_name');
        $this->db->from('lecturer');
        $this->db->where_not_in('lecturer_id', $lecturer_id);
        return $this->db->get()->result();
    }

    
    public function getAlllevel()
    {
        $this->db->select('level_id, level_name, dep_name');
        $this->db->from('level');
        $this->db->join('department', 'level.dep_id=department.dep_id');
        $this->db->order_by('level_name');
        return $this->db->get()->result();
    }
    
    public function getlevelBylecturer($id)
    {
        $this->db->select('level.level_id');
        $this->db->from('level_lecturer');
        $this->db->join('level', 'level_lecturer.level_id=level.level_id');
        $this->db->where('lecturer_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data department course
     */

    public function getdepartmentcourse()
    {
        $this->datatables->select('department_course.id, course.course_id, course.course_name, department.dep_id, GROUP_CONCAT(department.dep_name) as dep_name');
        $this->datatables->from('department_course');
        $this->datatables->join('course', 'department_course.course_id=course.course_id');
        $this->datatables->join('department', 'department_course.dep_id=department.dep_id');
        $this->datatables->group_by('course.course_name');
        return $this->datatables->generate();
    }

    public function getcourse($id = null)
    {
        $this->db->select('course_id');
        $this->db->from('department_course');
        if ($id !== null) {
            $this->db->where_not_in('course_id', [$id]);
        }
        $course = $this->db->get()->result();
        $course_id = [];
        foreach ($course as $d) {
            $course_id[] = $d->course_id;
        }
        if ($course_id === []) {
            $course_id = null;
        }

        $this->db->select('course_id, course_name');
        $this->db->from('course');
        $this->db->where_not_in('course_id', $course_id);
        return $this->db->get()->result();
    }

    public function getdepartmentByIdcourse($id)
    {
        $this->db->select('department.dep_id');
        $this->db->from('department_course');
        $this->db->join('department', 'department_course.dep_id=department.dep_id');
        $this->db->where('course_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}
