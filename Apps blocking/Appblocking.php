<?php

/* 
 * Author         : Zayaan Khan
 * Author Contact : zayaankhan107@gmail.com
 * Description    : 
 * Date Created   :
 * Version        :
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Installedapps extends CI_Controller {
    
    function __construct() { 
         parent::__construct(); 
         $this->load->model('installedappsmodel');
         $this->load->helper('url'); 
         $this->load->library('pagination');
         
      } 
    
    public function index() {
        if($this->session->userdata('logged_in')){
            $this->load->view('header/header');
            $sessionArray = $this->session->userdata('logged_in');
            $deviceIDValue = $this->session->userdata('deviceID');
            $config = array();
            $config["base_url"] = base_url() . "Installedapps/index";
            $config['total_rows'] = $this->installedappsmodel->record_count($deviceIDValue);
             // Number of items you intend to show per page.
            $config['per_page'] = 20;
            // Use pagination number for anchor URL.
            $config['use_page_numbers'] = TRUE;
            
//            $config["uri_segment"] = 3;
            //Set that how many number of pages you want to view.
            $config["num_links"] = 5;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = '&laquo... ';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            // By clicking on performing PREVIOUS pagination.
            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            // By clicking on performing NEXT pagination.
            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            // Open tag for CURRENT link.
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            // Close tag for CURRENT link.
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
          
            if(ceil($config['total_rows']/$config['per_page'])> 5) {
                $config['last_link']    =   '... &raquo';
            }
            else{
                $config['last_link']    =   '&raquo';
            }
            
            
            // To initialize "$config" array and set to pagination library.
            $this->pagination->initialize($config);
            
            $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["results"] = $this->installedappsmodel->fetch_data($config["per_page"], $data['page'],$deviceIDValue);
             // Create link.
            $data['pagination'] = $this->pagination->create_links();
//            $data['results'] = $this->installedappsmodel->getInstalledApps($deviceIDValue);
           
            $this->load->view('installedapps/installedapps',$data);
            $this->load->view('footer/footer');
        } else {
            redirect('login', 'refresh');
        }
    }
       public function statusupdate() {
        
        if($this->session->userdata('logged_in'))
            {
        
                if ($this->input->method() === 'post') {
            $columnName = $this->input->post('columnName');
                    $id = $this->input->post('appID');
                    $app_status = $this->input->post('statusValue');
                    $status = $this->installedappsmodel->statusUpdate($columnName,$id ,$app_status);
                   echo json_encode($status);
                }else {
                    redirect('installedapps', 'refresh');
                }
            } else {
            redirect('login/login', 'refresh');
        }
        
    }
    
      // Logout Action
    function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        $this->output->delete_cache();
        $this->cache->clean();
        redirect('login', 'refresh');
    }
}