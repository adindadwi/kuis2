<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai_model');
		$this->load->helper('url','form');
		$this->load->library('form_validation');

		if($this->session->userdata('status') != "login"){
			redirect(base_url("login"));
		}
	}

	public function index()
	{
		$this->load->model('pegawai_model');
		$data['pegawai_object']=$this->pegawai_model->getPegawaiQueryObject();

		$limit_per_page=6;
		$start_index = (
		$this->uri->segment(3)) ? 
		$this->uri->segment(3) : 0;
		

		$this->load->view('pegawai/pegawai_list', $data);

	}

	public function tambahPegawai(){
		$this->load->helper('url','form');
		$this->load->library('form_validation');

		$this->load->view('pegawai/tambah_pegawai_view');
	}

	public function Create(){
		$this->load->helper('url','form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('nama','Nama','trim|required');
		$this->form_validation->set_rules('nip','Nip','trim|required');
		$this->form_validation->set_rules('tanggal','Tanggal','trim|required');

		$this->load->model('pegawai_model');
		if($this->form_validation->run() == FALSE) {
			$this->load->view('pegawai/tambah_pegawai_view');
		} else {

			$config['upload_path'] = './assets/image/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = '1000000000';
			$config['max_width']  = '10240';
			$config['max_height']  = '7680';
			
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('userfile')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				//$data = array('upload_data' => $this->upload->data());
				$this->pegawai_model->insertPegawai();
				$this->load->view('pegawai/tambah_pegawai_sukses');
			}
		}
	}

	public function Update($id){
		$this->form_validation->set_rules('nama', 'nama', 'trim|required');
		$this->form_validation->set_rules('nip', 'nip', 'trim|required');
		$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');

		$data['pegawai']=$this->pegawai_model->getPegawai($id);

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('pegawai/edit_pegawai_view', $data);
		} else {

			$config['upload_path'] = './assets/image/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = '1000000000';
			$config['max_width']  = '10240';
			$config['max_height']  = '7680';
			
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('userfile')){
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}
			else{
				//$data = array('upload_data' => $this->upload->data());
				//echo "success";

				$this->pegawai_model->UpdateById($id);
				$this->load->view('pegawai/edit_pegawai_sukses');
			}			
		}

	}

	public function delete($id){
		$this->pegawai_model->delete($id);
		$this->load->view('pegawai/hapus_pegawai_sukses');
	}


	public function datatable(){
		$data['pegawai_list']=$this->pegawai_model->getDataPegawai();
		$this->load->view('pegawai/pegawai_datatable', $data);

	}
}
