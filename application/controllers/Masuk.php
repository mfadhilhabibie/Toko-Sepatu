<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masuk extends CI_Controller {

    //load database
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pelanggan_model');
    }

    //login pelanggan
    public function index()
    {
        //validasi
		$this->form_validation->set_rules('email','Email/username','required',
			array( 'required'	=> '%s harus diisi'));

			$this->form_validation->set_rules('password','Password','required',
			array( 'required'	=> '%s harus diisi'));

			if($this->form_validation->run())
			{
				$email   	= $this->input->post('email');
				$password	= $this->input->post('password');
				// proses ke simple_login
				$this->simple_pelanggan->login($email,$password);
			}
		// end validasi

        $data = array(  'title'         => 'Login Pelanggan',
                        'isi'           => 'masuk/list'
                     );
        $this->load->view('layout/wrapper', $data, FALSE);
    }

    //logout
    public function logout()
    {
        //ambil fungsi logout di Simple_pelanggan yg sudah diset di autoload libraries
        $this->simple_pelanggan->logout();
    }
}