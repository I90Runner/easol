<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends Easol_Controller {


    protected function accessRules(){
        return [
            "index"     =>  "*",
            "logout"    =>  "@",
            "accessdenied"  =>  "@"
        ];
    }
    /**
     * index page
     */
    public function index()
	{			    
		if($this->session->userdata('logged_in')== true)
			return redirect('/dashboard');
	
		if((isset($_POST['login']) and $data = $this->input->post('login')) or isset($_REQUEST['idtoken'])) {
		    	
		    if(isset($_REQUEST['idtoken'])) {
    			$this->_idtoken_login();
        	} 
	
		    if( isset($_POST['login']) && $data=$this->input->post('login')) {
				$this->_password_login($data);		    
		    }
		}
	
		if(isset($_REQUEST['idtoken'])) {
			// really?
		} else {
			$this->render("login");
		}
	}

	private function _idtoken_login ()
	{
		$this->load->model('Usermanagement_M');   
		$user = $this->Usermanagement_M->getEasolUsers($_REQUEST['uemail'], "SEM.ElectronicMailAddress");

		// FOR TESTING
		$thistestmode = TRUE;
		$staffUSI_alt = 207219; 
		// END TEST VARS

		if($user or $thistestmode) {

			$staffUSI = ($thistestmode) ? $staffUSI_alt : $user[0]->StaffUSI;
	 		
	 		$this->load->model('External_Auth','vToken');
	 		$gAuthGood = $this->vToken->validate_google_token($_REQUEST['uemail'], $_REQUEST['idtoken'], 'http://easol-dev.azurewebsites.net');
	 		
	 		if($gAuthGood == "valid") {

	 			$this->load->model('entities/easol/Easol_StaffAuthentication');
	 			$authentication = $this->Easol_StaffAuthentication->findOne(['StaffUSI' => $staffUSI]);

		 		if($authentication){
		    		$this->session->sess_expiration =   '1200';
				    $data=[
					    'LoginId'	=>      isset($user[0]) ? $user[0]->ElectronicMailAddress : $staffUSI,
					    'StaffUSI'  =>      $StaffUSI,
					    'RoleId'	=>      $authentication->RoleId,
					    'logged_in' => TRUE,
					];
				    
				    if($authentication->RoleId == 3 or $authentication->RoleId == 4) {
					    $data['SchoolId'] = isset($user[0]) ? $user[0]->Institutions[0]->EducationOrganizationId : null;
				    	$data['SchoolName'] = isset($user[0]) ? $user[0]->Institutions[0]->NameOfInstitution : null;
				    }

		    		$this->session->set_userdata($data);
		    		//return redirect('/student');
		    		echo "gloginValid";
		 		} else { 
		 			/* authentication failed */ echo "Error Logging in - Easol authentication failed - Please contact Support."; 
		 		}

	 		} else { 
	 			/* Google authentication failed */ echo "Error Logging in - Google authentication failed - Please contact Support."; 
	 		}
	      
		} else { 
		/* NO matching email found */ echo "Error Logging in - no matching email - Please contact Support."; 
		}
	}

	private function _password_login ($data = array())
	{

		$this->load->model('Usermanagement_M');   
		$user = $this->Usermanagement_M->getEasolUsers($data['email'], "SEM.ElectronicMailAddress");

	    if(is_array($user) and !empty($user)) {
			$this->load->model('entities/easol/Easol_StaffAuthentication');
			$authentication = $this->Easol_StaffAuthentication->findOne(['StaffUSI' => $user[0]->StaffUSI]);

			if($authentication && $authentication->Password == sha1($data['password'])){
			    $this->session->sess_expiration =   '1200';
			    $data=[
				    'LoginId'	=>	$user[0]->ElectronicMailAddress,
				    'StaffUSI'  =>  $user[0]->StaffUSI,
				    'RoleId'  	=> 	$authentication->RoleId,
				    'logged_in' => TRUE,
				];

			    if($authentication->RoleId==3 or $authentication->RoleId==4) {
				    $data['SchoolId'] = $user[0]->Institutions[0]->EducationOrganizationId;
				    $data['SchoolName'] = $user[0]->Institutions[0]->NameOfInstitution;
			    }

			    $this->session->set_userdata($data);
			    redirect('/student');
			}
	     }
	    
	     return $this->render("login",['message' => 'Invalid email/password']);		
	}

    /**
     * logout page
     */
    public function logout(){
        $this->session->sess_destroy();
        $this->load->helper('cookie');
        delete_cookie("G_AUTHUSER_H");
        delete_cookie("G_ENABLED_IDPS");
        delete_cookie("ARRAffinity");
        redirect('/');

    }

    /**
     * access denied page
     */
    public function accessdenied(){
        $this->render("access-denied");
    }
}