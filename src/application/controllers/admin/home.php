<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    protected $view_folder = 'admin';

    public function index()
    {
        $this->render();
    }

    //--------------------------------------------------------------------

}