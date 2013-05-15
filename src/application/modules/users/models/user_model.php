<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A basic user_model that handles most of the features you need to get
 * started supporting a simple user system for your application.
 *
 * SQL:
 *     CREATE TABLE `users` (
 *         `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 *         `username` varchar(60) DEFAULT NULL,
 *         `email` varchar(255) NOT NULL DEFAULT '',
 *         `password_hash` varchar(60) NOT NULL,
 *         `last_login` datetime DEFAULT NULL,
 *         `created_on` datetime DEFAULT NULL,
 *         `modified_on` datetime DEFAULT NULL,
 *         PRIMARY KEY (`id`),
 *         UNIQUE KEY `email` (`email`),
 *         UNIQUE KEY `username` (`username`)
 *     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
class User_model extends MY_Model {

    protected $_table = 'users';

    protected $before_insert = array('hash_password', 'create_username');
    protected $before_update = array('hash_password');

    protected $protected_attributes = array('submit', 'id');

    protected $validate = array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|max_length[60]|is_unique[users.username]|xss_clean'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|max_length[255]|valid_email|is_unique[users.username]|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|min_length[8]'
        ),
        array(
            'field' => 'pass_confirm',
            'label' => 'Password (again)',
            'rules' => 'matches[password]'
        )
    );

    //--------------------------------------------------------------------

    /**
     * Uses PHPPass to create a secure password hash of any password.
     * This requires that a field named 'password_hash' be present in the
     * database as a VARCHAR(60) column.
     *
     * To function properly, you should send a field called 'password' in
     * the $data array.
     *
     * @param  array $data
     */
    public function hash_password($data)
    {
        if (!isset($data['password']))
        {
            return $data;
        }

        // Load the password hash library
        if (!class_exists('PasswordHash'))
        {
            require(APPPATH .'libraries/Auth/PasswordHash.php');
        }
        $hasher = new PasswordHash(8, false);

        // Passwords should never be longer than 72 characters to prevent DOS attacks
        if (strlen($data['password']) > 72) die('Password must be 72 characters or less. Possible DOS attack.');

        $hash = $hasher->HashPassword($data['password']);
        unset($hasher);

        if (strlen($hash) < 20)
        {
            // Something went wrong....
            die('Something terribly wrong happened while hashing the password.');
        }

        $data['password_hash'] = $hash;
        unset($data['password'], $data['pass_confirm']);

        return $data;
    }

    //--------------------------------------------------------------------

    /**
     * To be used prior to inserting a new user, this function will set
     * the username to be equal to the email if it hasn't already been set.
     *
     * @param  array $data
     */
    public function create_username($data)
    {
        // Nothing we can do without the email.
        if (!isset($data['email']))
        {
            return $data;
        }

        if (!isset($data['username']) || (isset($data['username']) && empty($data['username'])))
        {
            $data['username'] = $data['email'];
        }

        return $data;
    }

    //--------------------------------------------------------------------

}