<?php 

class Admin extends XPQ_Controller {

   public function __construct()
   {
      session_start();
      parent::__construct();
   }

	public function index()
	{
		if ( isset($_SESSION['email_address']) ) {
			$this->data_master['content'] = $this->load->view('admin', '', true);
		}
		else{
      		$this->load->helper('form');
      		$this->load->helper('url');
			$this->load->library('form_validation');

			$this->data_master['content'] = $this->load->view('login', '', true);
			$this->data_master['css'] = '<link rel="stylesheet" type="text/css" href="/c/xpq8.form.login.css" />';
			$this->data_master['container'] = false;

			$this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
			if ( $this->form_validation->run() !== false ) {
				// then validation passed. Get from db
				$this->load->model('admin_model');
				$res = $this
					->admin_model
					->verify_user(
						$this->input->post('email_address'), 
						$this->input->post('password')
						);

				if ( $res !== false ) {
					$_SESSION['email_address'] = $this->input->post('email_address');
					redirect('admin');
				}
			}
		}

		$this->load->view('templates/master', $this->data_master);
	}

	public function logout() {
      	$this->load->helper('url');
		unset($_SESSION['email_address']);
		redirect('admin');
	}
}