<?php
class Errors extends CI_Controller{
    
    function code_404(){
        $this->load->view('errors/404');
    }
    
    function code_404s(){
        $this->load->view("errors/404m");
    }
    
    function code_401(){
        $this->load->view('errors/401');
    }
}
