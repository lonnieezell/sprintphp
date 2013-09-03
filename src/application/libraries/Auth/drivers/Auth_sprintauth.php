<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_sprintauth extends CI_Driver {

    protected $ci;

    protected $errors = array();

    //--------------------------------------------------------------------

    public function __construct()
    {
        $this->ci =& get_instance();

        // Load our require CI stuff
        $this->ci->load->database();

        $this->autologin();
    }

    //--------------------------------------------------------------------

    /**
     * Attempt to log the user in if they've got the remmeber me token
     * or logged in with 'user_id' in the session.
     */
    public function autologin()
    {
        if ($this->ci->session->userdata('user_id') )
        {

        }

        $user = $this->is_remembered(TRUE);

        if (is_object($user)) $this->user = $user;
        unset($user);
    }

    //--------------------------------------------------------------------


    /**
     * Attempts to log a user in using any type of credentials. If the user
     * is succesfully logged in, we save their basic info to the session,
     * save the current_user object to the Auth class and
     *
     * @param  [type] $credentials [description]
     * @param  [type] $remember    [description]
     * @return [type]              [description]
     */
    public function login($credentials, $remember)
    {
        $this->ci->load->model('users/user_model');

        // If not password is provided in the credentials we can't login...
        $password = isset($credentials['password']) ? $credentials['password'] : NULL;

        if (!$password)
        {
            return FALSE;
        }
        unset($credentials['password']);

        // Grab the user from the database.
        $user = $this->ci->user_model->find_by($credentials);

        if ($user)
        {
            // Load the password hash library
            if (!class_exists('PasswordHash'))
            {
                require(dirname(__FILE__) .'/../PasswordHash.php');
            }
            $hasher = new PasswordHash(8, false);

            // Compare the user and the passed password
            if ($hasher->CheckPassword($password, $user->password_hash))
            {
                unset($hasher);

                $this->remember_me($user->id);

                // Save our last login date
                $data = array(
                    'last_login'    => date('Y-m-d H:i:s')
                );
                $this->ci->user_model->update($user->id, $data, TRUE);

                return $user;
            }
            else
            {
                $this->errors[] = 'Username or Password was incorrect.';
            }

        }

        // Guess the user didn't exist
        return FALSE;
    }

    //--------------------------------------------------------------------

    /**
     * Logs the current user out.
     *
     * @return [type] [description]
     */
    public function logout()
    {
        $user_id = $this->ci->session->userdata('user_id');

        $this->ci->session->sess_destroy();

        delete_cookie('rememberme');
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------

    private function remember_me($user_id)
    {
        $this->ci->load->library('encrypt');

        $token = md5(uniqid(rand(), true));
        $timeout = 60*60*24*7 ;  // 1 week

        $remember_me = $this->ci->encrypt->encode($user_id .':'. $token .':'. (time() + $timeout));

        // Set the cookie and save to database.
        $cookie = array(
            'name'      => 'rememberme',
            'value'     => $remember_me,
            'expire'    => $timeout
        );

        set_cookie($cookie);
    }

    //--------------------------------------------------------------------


    /**
     * Checks if a user is logged in and remembered.
     *
     * @param  boolean $set_user    If TRUE, will store the user in the parent object.
     * @return boolean [description]
     */
    public function is_remembered($set_user=FALSE)
    {
        $this->ci->load->library('encrypt');
        $this->ci->load->helper('cookie');

        // Is there any cookie data?
        if ($cookie_data = get_cookie('rememberme'))
        {
            $user_id    = '';
            $token      = '';
            $timeout    = '';

            $cookie_data = $this->ci->encrypt->decode($cookie_data);

            if (strpos($cookie_data, ':') !== false)
            {
                $cookie_data = explode(':', $cookie_data);

                if (count($cookie_data) == 3)
                {
                    list($user_id, $token, $timeout) = $cookie_data;
                }
            }

            // Cookie expired?
            if ((int)$timeout < time())
            {
                delete_cookie('rememberme');
                return FALSE;
            }

            // Extend the cookie by another week from now so the
            // cookie is essentially forever, assuming they use the site
            // at least once per week.
            $this->remember_me($user_id);

            if ($set_user === TRUE)
            {
                $this->ci->load->model('users/user_model');
                $user = $this->ci->user_model->find($user_id);

                if ($user)
                {
                    return $user;
                }
            }

            return TRUE;
        }

        return FALSE;
    }

    //--------------------------------------------------------------------

}