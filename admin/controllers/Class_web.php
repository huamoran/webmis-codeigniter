<?php
class Class_web extends MY_Controller {
	/* Index */
	public function index(){
		$this->lang->load('class/class_web',$this->Lang);
		$this->load->library('inc');
		$this->load->helper('my');
		$this->load->library('menus');
		$data = $this->inc->Page($this,array('url'=>'class_web/index.html','model'=>'class_web_m'));
		$data['adminState'] = $this->menus->getMenu('adminState',$this->Lang);
		$data['LoadJS'] = array('class/class_web.js');
		$data['Menus'] = $this->inc->getMenuAdmin($this);
		if($this->IsMobile) {
			$this->inc->adminView($this,'class/web/index_mo',$data);
		}else {
			$this->inc->adminView($this,'class/web/index',$data);
		}
	}
	/* Search */
	public function search(){
		$this->lang->load('inc',$this->Lang);
		$this->lang->load('class/class_web',$this->Lang);
		$this->load->view('class/web/sea');
	}
	/* Add */
	public function add(){
		$this->lang->load('inc',$this->Lang);
		$this->lang->load('class/class_web',$this->Lang);
		$this->load->view('class/web/add');
	}
	public function addData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('class_web_m');
		echo $this->class_web_m->add()?'{"status":"y"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
	}
	/* GetMenu */
	public function getMenu(){
		$this->load->model('class_web_m');
		$fid = $this->input->post('fid');
		$data = $this->class_web_m->getMenus($fid);
		echo json_encode($data);
	}
	/* Edit */
	public function edit(){
		$this->lang->load('inc',$this->Lang);
		$this->lang->load('class/class_web',$this->Lang);
		$this->load->model('class_web_m');
		$data['edit'] = $this->class_web_m->getOne();
		$this->load->view('class/web/edit',$data);
	}
	public function editData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('class_web_m');
		echo $this->class_web_m->update()?'{"status":"y"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
	}
	/* Delete */
	public function delData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('class_web_m');
		echo $this->class_web_m->del()?'{"status":"y","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_suc').'","text":"'.$this->lang->line('msg_auto_close').'"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
	}
	/* Audit */
	public function auditData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('class_web_m');
		echo $this->class_web_m->audit()?'{"status":"y","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_suc').'","text":"'.$this->lang->line('msg_auto_close').'"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
	}
}