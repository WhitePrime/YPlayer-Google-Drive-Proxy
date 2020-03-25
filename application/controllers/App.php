<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function login() {
	    if(isset($this->session->userdata["has_login"])) redirect("/dashboard");
	    if($this->input->post("username")) {
	        $this->db->where("username", $this->input->post("username"));
	        $this->db->where("password", md5($this->input->post("password")));
	        $data = $this->db->get("user")->row_array();
	        if($data) {
	            $this->session->userdata["has_login"] = true; 
	            $this->session->userdata["userdata"] = $data; 
	            if($data["password"] === md5("admin")) redirect("/user/change-password");
	            redirect("/dashboard");
	        }
	        $this->session->set_flashdata("msg","Wrong Credentials");
	    }
	    
	    $this->load->view("auth/login");
	}
	
	public function logout() {
	    $this->session->sess_destroy();
	    $this->session->set_flashdata("msg","Logout success"); 
	    redirect("/auth/login");
	}
	
	public function dashboard() {
	    if(!isset($this->session->userdata["has_login"])) redirect("/auth/login");
	    
	    $this->load->view("include/header");
	    $this->load->view("dashboard",["data"=> $this->db->get("link")->result_array()]);
	    $this->load->view("include/footer");
	}
	
	public function changePassword() {
	    if(!isset($this->session->userdata["has_login"])) redirect("/auth/login");
	    
	    if($this->input->post("password")) {
	        $this->db->where("username", $this->session->userdata["userdata"]["username"]);
	        if($this->input->post("password") === $this->input->post("password2")) {
	            $this->db->update("user",["password" => md5($this->input->post("password"))]);
	            $this->session->set_flashdata("head-msg","Change Password successful");
	            redirect("/dashboard");
	        }
	        $this->session->set_flashdata("msg","Confirm password should match your newly created password");
	    }
	    $this->load->view("include/header");
	    $this->load->view("change-password");
	    $this->load->view("include/footer");
	}
	
	public function addVideo() {
	    if(!isset($this->session->userdata["has_login"])) redirect("/auth/login");
	    
	    if($this->input->post("vid")) {
	        $data = @file_get_contents(sprintf("https://content.googleapis.com/drive/v2/files/%s?key=AIzaSyD739-eb6NzS_KbVJq1K8ZAxnrMfkIqPyw",$this->input->post("vid")));
	        $json = json_decode($data,true);
	        if(!$data) {
	            $this->session->set_flashdata("msg","Please input a valid ID");
	        } else if(!isset($json["title"])) {
	            $this->session->set_flashdata("msg","File not found");
	        } else if(explode("/",$json["mimeType"])[0] != "video") {
	            $this->session->set_flashdata("msg","Please input a valid video");
	        } else {
	            $this->db->insert("link", [
	                    "drive_id" => $this->input->post("vid"),
	                    "filename" => $json["title"],
	                    "shortlink" =>  random_string('alnum', 16)
	                ]);
	            $this->session->set_flashdata("head-msg","Add video successful");
	            redirect("/dashboard");
	        }
	        
	    }
	    $this->load->view("include/header");
	    $this->load->view("add-video");
	    $this->load->view("include/footer");
	}
	
	public function deleteVideo($id = 1) {
	    if(!isset($this->session->userdata["has_login"])) redirect("/auth/login");
	    $this->db->where("id",$id)->delete("link");
        $this->session->set_flashdata("head-msg","Delete video successful");
        redirect("/dashboard");
	}
	
	public function viewVideo($id = 1) {
	    $data = $this->db->where("shortlink",$id)->get("link")->row_array();
        if($data) {
            $this->ydrive->setID($data["drive_id"]);
            $res_list = $this->ydrive->getResolution(true);
            foreach($res_list as $a => $b){
                $s[] = ["type"=>"mp4","label"=>$b,"file" => base_url("/storage/video/{$id}/".trim($b,"p").".mp4")];
            }
            $thumbnail = base_url("/embed/{$id}/thumb");
            $player_source = json_encode($s,JSON_UNESCAPED_SLASHES);
	        $this->load->view("player-video",["data" => $player_source, "thumb" => $thumbnail]);
        }else{
            die("Not Found");
        }
	}
	
	public function viewVideoThumbnail($id = 1) {
	    $data = $this->db->where("shortlink",$id)->get("link")->row_array();
        if($data) {
            $thumbnail = sprintf('https://drive.google.com/thumbnail?id=%s&authuser=0&sz=w640-h360-n-k-rw', $data["drive_id"]);
            header("Content-type: image/jpeg");
            echo @file_get_contents($thumbnail);
        }else{
            die("Not Found");
        }
	}
	
	public function streamVideo($id = 1,$res = 360) {
	    $data = $this->db->where("shortlink",$id)->get("link")->row_array();
        if($data) {
            $this->ydrive->setID($data["drive_id"]);
            $this->ydrive->setResolution($res);
            $this->ydrive->stream();
        }else{
            die("Not Found");
        }
	}
}
