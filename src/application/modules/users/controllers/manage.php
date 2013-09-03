<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
    Users Module - Manage controller

    maps to /manage/users...
 */
class Manage extends MY_Controller {

    protected $model_file = 'users/user_model';

    //--------------------------------------------------------------------

    public function index()
    {
        $users = $this->user_model->limit(25)->find_all();

        $data = array(
            'users' => $users,
            'total_users' => $this->user_model->count_all()
        );

        $this->set_var('page_title', 'Users');
        $this->render($data);
    }

    //--------------------------------------------------------------------

    public function create()
    {
        $this->load->library('form_validation');

        if ($this->input->post('submit'))
        {
            $post = array(
                'email'     => $this->input->post('email'),
                'username'  => $this->input->post('username')
            );

            if ( $this->input->post('password') && $this->input->post('pass_confirm'))
            {
                $post['password']       = $this->input->post('password');
                $post['pass_confirm']   = $this->input->post('pass_confirm');
            }

            if ($this->user_model->insert($post))
            {
                $this->set_message('User successfully created.');
                redirect('manage/users');
            }
        }

        $this->set_var('page_title', 'Create User');
        $this->render();
    }

    //--------------------------------------------------------------------

    public function edit($id=0)
    {
        $this->load->library('form_validation');

        if ($this->input->post('submit'))
        {
            $post = $_POST;

            if ($this->user_model->update($id, $post))
            {
                $this->set_message('User successfully saved.');
                redirect('manage/users');
            }
        }

        $data = array(
            'user' => $this->user_model->find($id)
        );

        $this->set_var('page_title', 'Edit User');
        $this->render($data);
    }

    //--------------------------------------------------------------------

    public function history()
    {
        $limit = 25;
        $offset = $this->uri->segment(4);

        $logins = $this->user_model->limit($limit, $offset)
                                   ->get_history();

        $this->set_var('logins', $logins);

        $total_rows = $this->db->count_all('user_logins');

        $this->set_var('range_first', (int)$offset + 1);
        $this->set_var('range_end', (int)$offset + $limit > $total_rows ? $total_rows : (int)$offset + $limit);
        $this->set_var('total_rows', $total_rows);

        $this->load->library('pagination');

        $config['base_url']     = site_url('manage/users/history');
        $config['total_rows']   = $total_rows;
        $config['per_page']     = $limit;

        $this->pagination->initialize($config);

        $this->set_var('page_title', 'Login History');
        $this->render();
    }

    //--------------------------------------------------------------------

}