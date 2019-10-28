<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simple_pelanggan 
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        //load data model user
        $this->CI->load->model('pelanggan_model');
    }

    //fungsi loginnya
    public function login($email,$password)
    {
        $check = $this->CI->pelanggan_model->login($email,$password);
        // jika ada data usernya, maka harus crate session login
        if($check)
        {
            $id_pelanggan       =$check->id_pelanggan;
            $nama_pelanggan     =$check->nama_pelanggan;
            //create session
            $this->CI->session->set_userdata('id_pelanggan', $id_pelanggan);
            $this->CI->session->set_userdata('nama_pelanggan', $nama_pelanggan);
            $this->CI->session->set_userdata('email', $email);
            //redirect ke halaman admin yg diproteksi
            redirect(base_url('dasbor'), 'refresh');
        }else{
            //harus login lagi kalau password atau username salah
            $this->CI->session->set_flashdata('warning', 'Username atau password salah');
            redirect(base_url('masuk'), 'refresh');
        }
    }

    //fungsi cek loginnya
    public function cek_login()
    {
        //memeriksa apakah session sudah atau belum, jika belum ke halaman login kembali
        if( $this->CI->session->set_userdata('email') == ""){
            $this->CI->session->set_flashdata('warning', 'Anda belum login !');
            redirect(base_url('masuk'), 'refresh');
        }

    }

    //fungsi logout
    public function logout()
    {
            //buang semua session yg telah diset pada saat login 
            $this->CI->session->unset_userdata('id_pelanggan');
            $this->CI->session->unset_userdata('nama_pelanggan');
            $this->CI->session->unset_userdata('email');
            //kembali ke login setelah seesion dibuang
            $this->CI->session->set_flashdata('sukses', 'Anda sudah berhasil logout');
            redirect(base_url('masuk'), 'refresh');
    }
}