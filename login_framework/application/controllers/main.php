<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$this->login();
	}

	public function login()
	{
		$this->load->view('login');
	}

	public function signup()
	{
		$this->load->view('signup');
	}

	public function restricted()
	{
		$this->load->view('restricted');
	}

	public function members()
	{
		if ($this->session->userdata('is_logged_in')){
			$this->load->view('members');
		} else {
			redirect('main/restricted');
		}
	}

	public function login_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 
			'required|trim|xss_clean|callback_validate_credentials');
		$this->form_validation->set_rules('password', 
			'Password', 'required|md5|trim');
		
		// If all the validation runs properly
		if ($this->form_validation->run()) {
			$data = array(
				'email' => $this->input->post('email'),
				'is_logged_in' => 1
			);
				// Go to the members page
			$this->session->set_userdata($data);
			redirect('main/members');
		} else {
			// Return to login
			$this->load->view('login');
		}
	}

	public function signup_validation()
	{
		$this->load->library('form_validation');
		$this->load->model('model_users');

		$this->form_validation->set_rules('email', 'Email', 
			'required|trim|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password', 'Password', 
			'required|trim');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 
			'required|trim|matches[password]');
		// Changes the duplicate email message
		$this->form_validation->set_message('is_unique',
			'That email address is already in use.');

		if ($this->form_validation->run()) {
			// Generate a random key
			$key = md5(uniqid());

			// Send an email to the user
			$this->load->library('email', array('mailtype'=>'html'));
			$this->email->from('donotreply@thebox.co.uk', 'The Box');
			$this->email->to($this->input->post('email'));
			$this->email->subject('The Box - Confirm your account');

			$message = "<p>Thank you for signing up.</p>
				<p><a href='".base_url()."main/register_user/$key'
				>Click Here</a> to confirm your email address.</p>";

			$this->email->message($message);

			// If the temp user is successfully added to the db
			if ($this->model_users->add_temp_user($key)) {
				// If the email sends successfully
				if ($this->email->send()) {
					echo "The email has been sent";
				} else {
					echo "Could not send the email";
				}
			} else {
				echo "Error: User was not added to database.";
			}
			//add them to the temp user database

		} else {
			$this->load->view('signup');
		}
	}

	public function register_user($key)
	{
		$this->load->model('model_users');

		// If the key is valid
		if ($this->model_users->is_key_valid($key)) {
			// If the user is added properly
			// $new_email is set here for use with the login redirect
			if ($new_email = $this->model_users->add_user($key)) {
				$data = array (
					'email' => $new_email,
					'is_logged_in' => 1
				);
				$this->session->set_userdata($data);
				redirect ('main/members');
			} else {
				echo "Error: Failed to add user";
			}
		} else {
			echo "Invalid Key";
		}
	}

	public function validate_credentials()
	{
		$this->load->model('model_users');
		
		// If you can log in
		if ($this->model_users->can_log_in()) {
			return true;
		} else {
			$this->form_validation->set_message('validate_credentials',
				'Incorrect username / password');
			return false;
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('main/login');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
