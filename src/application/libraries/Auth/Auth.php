<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Driver_Library {

    /**
     * Stores the currently authenticated user.
     */
    protected $user;

    /**
     * The currently in use driver.
     * @var string
     */
    protected $_driver;

    /**
     * Pointer to the CodeIgniter instance.
     */
    protected $ci;

    //--------------------------------------------------------------------

    public function __construct()
    {
        $this->ci =& get_instance();

        // Set the drivers based on what's defined in application config file
        $this->valid_drivers = $this->ci->config->item('auth.allowed_drivers');

        // Set the default driver
        $this->_driver = $this->ci->config->item('auth.default_driver');
    }

    //--------------------------------------------------------------------

    /**
     * Sets the driver to use.
     *
     * @param string $name
     */
    public function set_driver($name)
    {
        $this->_driver = trim( $name );
    }

    //--------------------------------------------------------------------

    /**
     * Attempts to log the user in. The credentials array contains any
     * key/value pairs to be passed to the driver.
     *
     * @param  array   $credentials An array of key/value pairs to match the user on
     * @param  boolean $remember
     * @return mixed
     */
    public function login( $credentials, $remember=FALSE, $redirect=null )
    {
        $user = $this->{$this->_driver}->login($credentials, $remember);

        if ($user)
        {
            $this->user = $user;
        }

        if (!empty($redirect))
        {
            redirect($redirect);
        }

        return is_object($user) ? true : false;
    }

    //--------------------------------------------------------------------

    /**
     * Logs a user out.
     *
     * @param string $redirect The site url to redirect to on successful login.
     */
    public function logout($redirect='')
    {
        $return = $this->{$this->_driver}->logout($redirect);

        if (!empty($redirect))
        {
            redirect($redirect);
        }

        return $return;
    }

    //--------------------------------------------------------------------

    /**
     * Is the user currently logged in?
     *
     * @return [type] [description]
     */
    public function logged_in()
    {
        return !empty($this->user);
    }

    //--------------------------------------------------------------------

    /**
     * Gets the current user.
     *
     * @return [type] [description]
     */
    public function current_user()
    {
        if (empty($this->user))
        {
            return NULL;
        }

        return $this->user;
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // Utility Methods
    //--------------------------------------------------------------------

    /**
     * Redirect all method calls not in this class to the child class set
     * in the variable 'driver'.
     *
     * @param  mixed $child
     * @param  mixed $arguments
     * @return mixed
     */
    public function __call($child, $arguments)
    {
        return call_user_func_array( array($this->{$this->driver}, $child), $arguments);
    }

    //--------------------------------------------------------------------

}