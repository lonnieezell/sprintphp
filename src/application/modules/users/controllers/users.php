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
        die('here');
    }

    //--------------------------------------------------------------------

    public function login()
    {
        $this->load->library('form_validation');

        if ($this->input->post('submit'))
        {
            $redirect = $this->input->post('redirect');

            $remember = (boolean)$this->input->post('remember');

            $data = array(
                'email' => $this->input->post('email', true),
                'password' => $this->input->post('password')
            );

            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            if ($this->form_validation->run())
            {
                $this->auth->login($data, $remember, $redirect);
            }
        }

        $this->set_var('page_title', 'Login');
        $this->render();
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