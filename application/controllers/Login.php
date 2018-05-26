<?php 

class Login extends CI_Controller{

	public function __construct(){
		parent::__construct();		
		$this->load->model('m_login');
	}

	public function index(){
		$this->load->view('v_login');
	}

	public function aksi_login(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$where = array(
			'username' => $username,
			'password' => $password
			);
		$cek = $this->m_login->cek_login("user",$where)->num_rows();
		if($cek > 0){

			$data_session = array(
				'nama' => $username,
				'status' => "login"
				);

			$this->session->set_userdata($data_session);

			redirect(site_url("pegawai"));

		}else{
			echo "Username dan password salah !";
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(site_url('login'));
	}
}