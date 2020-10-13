<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	public function index(){
	{
		
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|required');
		
		if($this->form_validation->run() == false ) {
		$data['title']= 'Login kawanSD';
		$this->load->view('template/auth_header',$data);
		$this->load->view('auth/member');
        $this->load->view('template/auth_footer');
		} else {
			// validasinya success
			$this->_member();
	}
	}
	}

	private function _member()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');	
	
		$user = $this->db->get_where ('user', ['email' => $email])->row_array();
		
		//jika usernya ada
		 if($user) {
				 //jika usernya aktif
				 if($user) {
					 // cek password
					 if(password_verify($password, $user['password'])){
						 $data = [
							'email' =>$user['email'],
							];
							$this->session->set_userdata($data);
							redirect('home');
					 }else{
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Wrong password!
					</div>');
					redirect('auth');
					 }
					 
				 } else {
					 $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Tjis email has not been activated!
					</div>');
					redirect('auth');
				 }
					 
				 
		 } else {
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
        Email is not registered!
		</div>');
		redirect('auth');
		}
	}
	
		
	public function registration(){
	{
		$this->form_validation->set_rules('name','Name','required|trim');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email');
		
		$this->form_validation->set_rules('password1','Password','required|trim|min_length[3]|matches[password2]', [
			'matches'=> 'Password dont match!',
			'min_length' => 'Password too short!'
			]);
			
		$this->form_validation->set_rules('password2','Password','required|trim|matches[password1]');
		
		if($this->form_validation->run() == false) {
		$data['title'] = 'Registrasi';
		$this->load->view('template/auth_header', $data);
		$this->load->view('auth/registration');
        $this->load->view('template/auth_footer');
	} else {
		$data = [ 
		'name' => $this->input->post('name'),
		'email' => $this->input->post('email'),
		'image' => 'default.jpg',
		'password' => password_hash($this->input->post('password1'),PASSWORD_DEFAULT),
		'date_created' => time()
		];	
		
		$this->db->insert('user', $data);
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Congratulation! your account has been created, please login
		</div>');
		redirect('auth');
		
		}
	}
}
}
	
