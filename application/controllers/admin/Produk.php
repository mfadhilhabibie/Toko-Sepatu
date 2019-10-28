<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

    //halaman utama website
	public function __construct()
	{
        parent:: __construct();
        $this->load->model('produk_model');
        $this->load->model('kategori_model');
        
    }
    
    //data produk
    public function index()
    {
        $produk = $this->produk_model->listing();

        $data = array(  'title'     => 'Data Produk',
                        'produk'    =>  $produk,
                        'isi'       =>  'admin/produk/list'
                      );
        $this->load->view('admin/layout/wrapper', $data, FALSE);
    }

    //gambar
    public function gambar($id_produk)
    {
           $produk  = $this->produk_model->detail($id_produk);
           $gambar  = $this->produk_model->gambar($id_produk);
           
        //validasi input
        $valid = $this->form_validation;

        $valid->set_rules('judul_gambar','Judul/Nama Gambar','required',
            array( 'required'       => '%s harus diisi ya'));
       
        if($valid->run()){
            $config['upload_path']      = './assets/upload/image/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = '8000'; // dalam kb ukurannya
            $config['max_width']        = '2024';
            $config['max_height']       = '2024';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('gambar')){
        // selesai validasi

         $data = array(  'title'     => 'Tambah Gambar Produk: '.$produk->nama_produk,
                         'produk'    => $produk,
                         'gambar'    => $gambar,
                         'error'     => $this->upload->display_errors(),
                         'isi'       => 'admin/produk/gambar'
                       );
         $this->load->view('admin/layout/wrapper', $data, FALSE);
        //masuk database
        }else
        {
            $upload_gambar = array('upload_data' => $this->upload->data());

            //create thumbnail gambar
            $config['image_library']     = 'gd2';
            $config['source_image']      = './assets/upload/image/'.$upload_gambar['upload_data']['file_name'];
            //lokasi folder thumbnail
            $config['new_image']         = './assets/upload/image/thumbs/';
            $config['create_thumb']      = TRUE;
            $config['maintain_ratio']    = TRUE;
            $config['width']             = 250;//pixel
            $config['height']            = 250;//pixel
            $config['thumb_marker']      = '';

            $this->load->library('image_lib', $config);

            $this->image_lib->resize();
            // end create thumbnail

            $i = $this->input;

            $data = array(  'id_produk'      => $id_produk,
                            'judul_gambar'   => $i->post('judul_gambar'),
                            // disimpan nama file dari gambarnya
                            'gambar'         =>$upload_gambar['upload_data']['file_name'],
                            
                        );
            $this->produk_model->tambah_gambar($data);
            $this->session->set_flashdata('sukses', 'Data gambar sudah ditambahkan');
            redirect(base_url('admin/produk/gambar/'.$id_produk),'refresh');                
        }}
        //end masuk database
        $data = array(   'title'     => 'Tambah Gambar Produk: '.$produk->nama_produk,
                         'produk'    => $produk,
                         'gambar'    => $gambar,
                         'isi'       => 'admin/produk/gambar'
                       );
         $this->load->view('admin/layout/wrapper', $data, FALSE);
    }

     //tambah produk
     public function tambah()
     { 
         //ambil data kategori
         $kategori = $this->kategori_model->listing();
         //validasi input
        $valid = $this->form_validation;


        $valid->set_rules('nama_produk','Nama Produk','required',
            array( 'required'       => '%s harus diisi ya'));
        

        $valid->set_rules('kode_produk','Kode Produk','required|is_unique[produk.kode_produk]',
            array( 'required'       => '%s harus diisi ya',
                   'is_unique'      => '%s sudah ada. Buat kode produk baru'));
       
        if($valid->run()){
            $config['upload_path']      = './assets/upload/image/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = '8000'; // dalam kb ukurannya
            $config['max_width']        = '2024';
            $config['max_height']       = '2024';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('gambar')){
        // selesai validasi

         $data = array(  'title'     => 'Tambah Produk',
                         'kategori'  => $kategori,
                         'error'     => $this->upload->display_errors(),
                         'isi'       => 'admin/produk/tambah'
                       );
         $this->load->view('admin/layout/wrapper', $data, FALSE);
        //masuk database
        }else
        {
            $upload_gambar = array('upload_data' => $this->upload->data());

            //create thumbnail gambar
            $config['image_library']     = 'gd2';
            $config['source_image']      = './assets/upload/image/'.$upload_gambar['upload_data']['file_name'];
            //lokasi folder thumbnail
            $config['new_image']         = './assets/upload/image/thumbs/';
            $config['create_thumb']      = TRUE;
            $config['maintain_ratio']    = TRUE;
            $config['width']             = 250;//pixel
            $config['height']            = 250;//pixel
            $config['thumb_marker']      = '';

            $this->load->library('image_lib', $config);

            $this->image_lib->resize();
            // end create thumbnail

            $i = $this->input;
            //slug dari produk
            $slug_produk = url_title($this->input->post('nama_produk').'-'.$this->input->post('kode_produk'), 'dash', TRUE);

            $data = array(  'id_user'        =>$this->session->userdata('id_user'),
                            'id_kategori'    =>$i->post('id_kategori'),
                            'kode_produk'    =>$i->post('kode_produk'),
                            'nama_produk'    =>$i->post('nama_produk'),
                            'slug_produk'    =>$slug_produk,
                            'keterangan'     =>$i->post('keterangan'),
                            'keywords'       =>$i->post('keywords'),
                            'harga'          =>$i->post('harga'),
                            'stok'           =>$i->post('stok'),
                            // disimpan nama file dari gambarnya
                            'gambar'         =>$upload_gambar['upload_data']['file_name'],
                            'berat'          =>$i->post('berat'),
                            'ukuran'         =>$i->post('ukuran'),
                            'status_produk'  =>$i->post('status_produk'),
                            'tanggal_post'   =>date('Y-m-d H:i:s')
                        );
            $this->produk_model->tambah($data);
            $this->session->set_flashdata('sukses', 'Data sudah ditambah');
            redirect(base_url('admin/produk'),'refresh');                
        }}
        //end masuk database
        $data = array(   'title'     => 'Tambah Produk',
                         'kategori'  => $kategori,
                         'isi'       => 'admin/produk/tambah'
                       );
         $this->load->view('admin/layout/wrapper', $data, FALSE);
    }

    //edit produk
	public function edit($id_produk)
	{
        // ambil data produk yg akan di edit
        $produk      = $this->produk_model->detail($id_produk);
        //ambil data kategori
        $kategori    = $this->kategori_model->listing(); 
        //validasi input
        $valid       = $this->form_validation;


        $valid->set_rules('nama_produk','Nama Produk','required',
            array( 'required'       => '%s harus diisi ya'));
        

        $valid->set_rules('kode_produk','Kode Produk','required',
            array( 'required'       => '%s harus diisi ya'));
    
        if($valid->run()){
            // check jia gambar diganti
            if(!empty($_FILES['gambar']['name'])) {

            $config['upload_path']      = './assets/upload/image/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = '8000'; // dalam kb ukurannya
            $config['max_width']        = '2024';
            $config['max_height']       = '2024';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('gambar')){
        // selesai validasi

        $data = array(  'title'     => 'Edit Produk: '.$produk->nama_produk,
                        'kategori'  => $kategori,
                        'produk'    => $produk,
                        'error'     => $this->upload->display_errors(),
                        'isi'       => 'admin/produk/edit'
                    );
        $this->load->view('admin/layout/wrapper', $data, FALSE);
        //masuk database
        }else
        {
            $upload_gambar = array('upload_data' => $this->upload->data());

            //create thumbnail gambar
            $config['image_library']     = 'gd2';
            $config['source_image']      = './assets/upload/image/'.$upload_gambar['upload_data']['file_name'];
            //lokasi folder thumbnail
            $config['new_image']         = './assets/upload/image/thumbs/';
            $config['create_thumb']      = TRUE;
            $config['maintain_ratio']    = TRUE;
            $config['width']             = 250;//pixel
            $config['height']            = 250;//pixel
            $config['thumb_marker']      = '';

            $this->load->library('image_lib', $config);

            $this->image_lib->resize();
            // end create thumbnail

            $i = $this->input;
            //slug dari produk
            $slug_produk = url_title($this->input->post('nama_produk').'-'.$this->input->post('kode_produk'), 'dash', TRUE);

            $data = array(  'id_produk'      =>$id_produk,
                            'id_user'        =>$this->session->userdata('id_user'),
                            'id_kategori'    =>$i->post('id_kategori'),
                            'kode_produk'    =>$i->post('kode_produk'),
                            'nama_produk'    =>$i->post('nama_produk'),
                            'slug_produk'    =>$slug_produk,
                            'keterangan'     =>$i->post('keterangan'),
                            'keywords'       =>$i->post('keywords'),
                            'harga'          =>$i->post('harga'),
                            'stok'           =>$i->post('stok'),
                            // disimpan nama file dari gambarnya
                            'gambar'         =>$upload_gambar['upload_data']['file_name'],
                            'berat'          =>$i->post('berat'),
                            'ukuran'         =>$i->post('ukuran'),
                            'status_produk'  =>$i->post('status_produk')
                        );
            $this->produk_model->edit($data);
            $this->session->set_flashdata('sukses', 'Data sudah diedit');
            redirect(base_url('admin/produk'),'refresh');                
        }}else{
            //edit produk tanpa ganti gambar
            $i = $this->input;
            //slug dari produk
            $slug_produk = url_title($this->input->post('nama_produk').'-'.$this->input->post('kode_produk'), 'dash', TRUE);

            $data = array(  'id_produk'      =>$id_produk,
                            'id_user'        =>$this->session->userdata('id_user'),
                            'id_kategori'    =>$i->post('id_kategori'),
                            'kode_produk'    =>$i->post('kode_produk'),
                            'nama_produk'    =>$i->post('nama_produk'),
                            'slug_produk'    =>$slug_produk,
                            'keterangan'     =>$i->post('keterangan'),
                            'keywords'       =>$i->post('keywords'),
                            'harga'          =>$i->post('harga'),
                            'stok'           =>$i->post('stok'),
                            // disimpan nama file dari gambarnya(gambar tidak di ganti)
                            //'gambar'         =>$upload_gambar['upload_data']['file_name'],
                            'berat'          =>$i->post('berat'),
                            'ukuran'         =>$i->post('ukuran'),
                            'status_produk'  =>$i->post('status_produk')
                        );
            $this->produk_model->edit($data);
            $this->session->set_flashdata('sukses', 'Data sudah diedit');
            redirect(base_url('admin/produk'),'refresh');

        }}
        //end masuk database
        $data = array(  'title'     => 'Edit Produk'.$produk->nama_produk,
                        'kategori'  => $kategori,
                        'produk'    => $produk,
                        'isi'       => 'admin/produk/edit'
                    );
        $this->load->view('admin/layout/wrapper', $data, FALSE);       
    }

    // hapus produk
    public function delete($id_produk)
    {
        //prose hapus gambar
        $produk =  $this->produk_model->detail($id_produk);
        unlink('./assets/upload/image/'.$produk->gambar);
        unlink('./assets/upload/image/thumbs/'.$produk->gambar);

        //end proses hapus
        $data = array('id_produk' => $id_produk);
        $this->produk_model->delete($data);
        $this->session->set_flashdata('sukses', 'Data sudah dihapus');
        redirect(base_url('admin/produk'),'refresh'); 
    }

    // hapus gambar produk
    public function delete_gambar($id_produk,$id_gambar)
    {
        //prose hapus gambar
        $produk =  $this->produk_model->detail_gambar($id_gambar);
        unlink('./assets/upload/image/'.$gambar->gambar);
        unlink('./assets/upload/image/thumbs/'.$gambar->gambar);

        //end proses hapus
        $data = array('id_gambar' => $id_gambar);
        $this->produk_model->delete_gambar($data);
        $this->session->set_flashdata('sukses', 'Data gambar sudah dihapus');
        redirect(base_url('admin/produk/gambar/'.$id_produk),'refresh'); 
    }
}