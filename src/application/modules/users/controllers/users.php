<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {

    protected $model_file = 'users/user_model';

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->load->driver('Auth');
    }

    //--------------------------------------------------------------------

    public function index()
    {
        /*
        $users = $this->user_model->limit(25)->find_all();

        $data = array(
            'users' => $users,
            'total_users' => $this->user_model->count_all()
        );

        $this->set_var('page_title', 'Users');
        $this->render($data);
        */
    }

    //--------------------------------------------------------------------

    public function login()
    {
        $this->load->library('form_validation');

        $redirect = $this->input->post('redirect');

        // Any chance it was set in the session?
        if ($this->session->userdata('after_login'))
        {
            $redirect = $this->session->userdata('after_login');
            $this->session->unset_userdata('after_login');
        }

        if ($redirect == site_url() || empty($redirect))
        {
            $redirect .= 'manage';
        }

        $this->set_var('redirect', $redirect);

        if ($this->input->post('submit'))
        {
            $remember = (boolean)$this->input->post('remember');

            $data = array(
                'email' => $this->input->post('email', true),
                'password' => $this->input->post('password')
            );

            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            if ($this->form_validation->run())
            {
                if ($this->auth->login($data, $remember))
                {
                    redirect($redirect);
                }
            }
        }

        $this->render();
    }

    //--------------------------------------------------------------------

    public function logout()
    {
        $redirect = site_url();

        $this->auth->logout($redirect);
    }

    //--------------------------------------------------------------------


    public function register()
    {
        $this->load->library('form_validation');

        if ($this->input->post('submit'))
        {
            if ($this->user_model->insert($this->input->post()))
            {
                die('inserted user');
            }
        }

        $this->render();
    }

    //--------------------------------------------------------------------


}