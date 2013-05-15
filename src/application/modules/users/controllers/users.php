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
        $this->auth->login();
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