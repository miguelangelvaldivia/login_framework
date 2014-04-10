<?php
class Model_users extends CI_Model {
	public function can_log_in()
	{
		// Seasoning is important for the odd md5 
		$salt = "Qx-g0Yb.g>)8457!y%AX:?,,u.j93I";

		// Compares the username and password with the db
		$this->db->where('email', $this->input->post('email'));
		$this->db->where('password', 
			md5($this->input->post('password').$salt));
		$query = $this->db->get('user');

		// If the query returns one row then the user exists
		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function add_temp_user($key)
	{
		// Seasoning is important for the odd md5 
		$salt = "Qx-g0Yb.g>)8457!y%AX:?,,u.j93I";
		$data = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password').$salt),
			'key' => $key
		);
		$query = $this->db->insert('temp_user', $data);
		// If the query runs successfully
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function is_key_valid($key)
	{
		$this->db->where('key', $key);
		$query = $this->db->get('temp_user');

		// If there is one row returned then there is a user in the db
		if ($query->num_rows() == 1){
			return true;
		} else {
			return false;
		}
	}

	public function add_user($key)
	{
		$this->db->where('key', $key);
		$temp_user = $this->db->get('temp_user');

		if ($temp_user) {
			// The amount of rows returned by the query
			$row = $temp_user->row();
			$data = array(
				'email' => $row->email,
				'password' => $row->password
			);
			$add_user =  $this->db->insert('user', $data);
		}
		// If the user was added to the db
		if ($add_user) {
			// Remove the temp user
			$this->db->where('key', $key);
			$this->db->delete('temp_user');
			// Returns the email for use with the login redirect
			return $data['email'];
		} else {
			return false;
		}

	}
}
