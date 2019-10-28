<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simple_login 
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        //load data model user
        $this->CI->load->model('user_model');
    }

    //fungsi loginnya
    public function login($username,$password)
    {
        $check = $this->CI->user_model->login($username,$password);
        // jika ada data usernya, maka harus crate session login
        if($check)
        {
            $id_user            =$check->id_user;
            $nama               =$check->nama;
            $akses_level        =$check->akses_level;
            //create session
            $this->CI->session->set_userdata('id_user', $id_user);
            $this->CI->session->set_userdata('nama', $nama);
            $this->CI->session->set_userdata('username', $username);
            $this->CI->session->set_userdata('akses_level', $akses_level);
            //redirect ke halaman admin yg diproteksi
            redirect(base_url('admin/dasbor'), 'refresh');
        }else{
            //harus login lagi kalau password atau username salah
            $this->CI->session->set_flashdata('warning', 'Username atau password salah');
            redirect(base_url('login'), 'refresh');
        }
    }

    //fungsi cek loginnya
    public function cek_login()
    {
        //memeriksa apakah session sudah atau belum, jika belum ke halaman login kembali
        if( $this->CI->session->set_userdata('username') == ""){
            $this->CI->session->set_flashdata('warning', 'Anda belum login !');
            redirect(base_url('login'), 'refresh');
        }

    }

    //fungsi logout
    public function logout()
    {
            //buang semua session yg telah diset pada saat login 
            $this->CI->session->unset_userdata('id_user');
            $this->CI->session->unset_userdata('nama');
            $this->CI->session->unset_userdata('username');
            $this->CI->session->unset_userdata('akses_level');
            //kembali ke login setelah seesion dibuang
            $this->CI->session->set_flashdata('sukses', 'Anda sudah berhasil logout');
            redirect(base_url('login'), 'refresh');
    }
}