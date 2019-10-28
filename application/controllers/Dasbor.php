<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dasbor extends CI_Controller {

    //load model
	public function __construct()
	{
        parent:: __construct();
        $this->load->model('pelanggan_model');
        $this->load->model('header_transaksi_model');
        $this->load->model('transaksi_model');
        //halaman ini diproteksi dengan Simple_pelanggan => Check Login
        //$this->simple_pelanggan->cek_login();       
    }
    
    //halaman dasbor
    public function index()
    {
        //ambil data login id_pelanggan dari session
        $id_pelanggan     = $this->session->userdata('id_pelanggan');
        $header_transaksi = $this->header_transaksi_model->pelanggan($id_pelanggan);          

        $data = array(  'title'              => 'Halaman Dasbhoard Pelanggan',
                        'header_transaksi'   => $header_transaksi,
                        'isi'                => 'dasbor/list'
                     );
        $this->load->view('layout/wrapper', $data, FALSE);
    }

    //belanja
    public function belanja()
    {
        //ambil data login id_pelanggan dari session
        $id_pelanggan     = $this->session->userdata('id_pelanggan');
        $header_transaksi = $this->header_transaksi_model->pelanggan($id_pelanggan);            
        
         $data = array( 'title'             => 'Riwayat Belanja',
                        'header_transaksi'  => $header_transaksi,
                        'isi'               => 'dasbor/belanja'
                     );
        $this->load->view('layout/wrapper', $data, FALSE);
    }

    //detail
    public function detail($kode_transaksi)
    {
       //ambil data login id_pelanggan dari session
        $id_pelanggan     = $this->session->userdata('id_pelanggan');
        $header_transaksi = $this->header_transaksi_model->kode_transaksi($kode_transaksi);
        $transaksi        = $this->transaksi_model->kode_transaksi($kode_transaksi);

        //pastikan pelanggan hanya mengakses data transaksinya
        if($header_transaksi->id_pelanggan != $id_pelanggan) {
            $this->session->set_flashdata('warning', 'Anda mencoba mengakses data transaksi orang lain');
            redirect(base_url('masuk'));
        }
        
        $data = array(  'title'             => 'Riwayat Belanja',
                        'header_transaksi'  => $header_transaksi,
                        'transaksi'         => $transaksi,
                        'isi'               => 'dasbor/detail'
                     );
        $this->load->view('layout/wrapper', $data, FALSE); 
    }

    //profil
    public function profil()
    {
       //ambil data login id_pelanggan dari session
        $id_pelanggan     = $this->session->userdata('id_pelanggan');
        $pelanggan        = $this->pelanggan_model->detail($id_pelanggan);

          //validasi input
        $valid = $this->form_validation;


        $valid->set_rules('nama_pelanggan','Nama Lengkap','required',
            array( 'required'       => '%s harus diisi ya'));

         $valid->set_rules('alamat','Alamat Lengkap','required',
            array( 'required'       => '%s harus diisi ya'));
       
         $valid->set_rules('telepon','Nomor Telepon','required',
            array( 'required'       => '%s harus diisi ya'));

        if($valid->run()===FALSE){
        // selesai validasi

        $data = array(  'title'             => 'Profil saya',
                        'pelanggan'         => $pelanggan,
                        'isi'               => 'dasbor/profil'
                     );
        $this->load->view('layout/wrapper', $data, FALSE);

        //masuk database
        }else
        {
            $i = $this->input;
            //kalau passwordnya lebih dari 6 karakter maka ganti password
            if(strlen($i->post('password')) >6) {
            $data = array(  'id_pelanggan'      => $id_pelanggan,
                            'nama_pelanggan'    => $i->post('nama_pelanggan'),
                            'password'          => SHA1($i->post('password')),
                            'telepon'           => $i->post('telepon'),
                            'alamat'            => $i->post('alamat'),
                        );
             }else{
                 //kalau password kurang dari 6 maka password tidak diganti
                 $data = array( 'id_pelanggan'      => $id_pelanggan,
                                'nama_pelanggan'    => $i->post('nama_pelanggan'),
                                'telepon'           => $i->post('telepon'),
                                'alamat'            => $i->post('alamat'),
                        );
                }
                //end data update
            $this->pelanggan_model->edit($data);   
            $this->session->set_flashdata('sukses', 'Update Profil berhasil');
            redirect(base_url('dasbor/profil'),'refresh');                
        }
        //end masuk database
        
     }




}