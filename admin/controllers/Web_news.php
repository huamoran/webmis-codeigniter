<?php
class Web_news extends MY_Controller {
	/* Index */
	public function index(){
		$this->lang->load('web/web_news',$this->Lang);
		$this->load->library('inc');
		$this->load->helper('my');
		$data = $this->inc->Page($this,array('url'=>'web_news/index.html','model'=>'web_news_m','where'=>array('in'=>array('0','1','2'))));
		/* ClassInfo */
		$this->load->library('menus');
		$this->load->model('class_web_m');
		$data['class'] = $this->class_web_m->getClass();
		$data['adminState'] = $this->menus->getMenu('adminState',$this->Lang);
		$data['LoadJS'] = array('web/web_news.js');
		$data['Menus'] = $this->inc->getMenuAdmin($this);
		if($this->IsMobile) {
			$this->inc->adminView($this,'web/news/index_mo',$data);
		}else {
			$this->inc->adminView($this,'web/news/index',$data);
		}
	}
	/* Search */
	public function search(){
		$this->lang->load('inc',$this->Lang);
		$this->lang->load('web/web_news',$this->Lang);
		$this->load->view('web/news/sea');
	}
	/* Add */
	public function add(){
		$this->lang->load('inc',$this->Lang);
		$this->lang->load('web/web_news',$this->Lang);
		$this->load->view('web/news/add');
	}
	public function addData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('web_news_m');
		if(isset($_POST['content'])) {
			echo $this->web_news_m->add()?'{"status":"y"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
		}
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
		$this->lang->load('web/web_news',$this->Lang);
		$this->load->model('web_news_m');
		$data['edit'] = $this->web_news_m->getOne();
		$this->load->view('web/news/edit',$data);
	}
	public function editData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('web_news_m');
		if(isset($_POST['content'])) {
			echo $this->web_news_m->update()?'{"status":"y"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
		}
	}
	/* Delete */
	public function delData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('web_news_m');
		echo $this->web_news_m->del()?'{"status":"y","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_suc').'","text":"'.$this->lang->line('msg_auto_close').'"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
	}
	/* Audit */
	public function auditData(){
		$this->lang->load('msg',$this->Lang);
		$this->load->model('web_news_m');
		echo $this->web_news_m->audit()?'{"status":"y","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_suc').'","text":"'.$this->lang->line('msg_auto_close').'"}':'{"status":"n","title":"'.$this->lang->line('msg_title').'","msg":"'.$this->lang->line('msg_err').'","text":"'.$this->lang->line('msg_auto_close').'"}';
	}
	/* Chart */
	public function chartData() {
		$this->load->model('class_web_m');
		$this->load->model('web_news_m');
		$i = 0;
		$menus = $this->class_web_m->getMenus('0');
		$color = array('#6FB737','#3A90BA','#3D3D3D');
		foreach($menus as $val){
			$num = $this->web_news_m->count_all(array('class' =>':'.$val->id.':'));
			$num = $num?$num:'1';
			$data[] = array('value'=>$num, 'color'=>$color[$i], 'label'=>$val->title);
			$i++;
		}
		echo json_encode($data);
	}
	/* View */
	public function show(){
		$this->lang->load('web/web_news',$this->Lang);
		$this->load->model('web_news_m');
		$data['show'] = $this->web_news_m->getOne();
		$this->load->view('web/news/show',$data);
	}
	function getImghtml($id='',$num=''){
		$this->lang->load('inc',$this->Lang);
		$this->lang->load('web/web_news',$this->Lang);
		$html = '';
		if(is_numeric($id) && is_numeric($num)){
			$html .= '<tr id="ImgCT_'.$num.'">';
			$html .= '<td><a href="" id="ImgShow_'.$num.'" target="_black" title="'.$this->lang->line('web_news_preview').'"><img src="" width="100" height="65" /></a></td>';
			$html .= '<td class="tleft">';
			$html .= '<form action="'.base_url().'web_news/upImgData/'.$num.'.html" method="post" enctype="multipart/form-data" id="upIMG_'.$num.'">';
			$html .= '<div>';
			$html .= '<input type="file" name="upimg_'.$num.'" size="20" />';
			$html .= '<input type="submit" id="verifySub" value="'.$this->lang->line('inc_up').'" />';
			$html .= '<input type="hidden" id="ImgInput_'.$num.'" name="img_url" value="" />';
			$html .= '<input type="hidden" name="id" value="'.$id.'" />';
			$html .= '</div>';
			$html .= '</form>';
			$html .= '<div style="padding-top: 5px;">'.$this->lang->line('web_news_url').'：<span id="ImgURL_'.$num.'"></span></div>';
			$html .= '</td>';
			$html .= '<td><a href="" onclick="RemoveIMG(\''.$num.'\');return false;">'.$this->lang->line('inc_remove').'</a></td>';
			$html .= '</tr>';
		}
		echo $html;
	}
	/* Upload Image */
	public function upImgData($num=''){
		$config['upload_path'] = '../upload/images/news/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '600';
		$this->load->library('upload', $config);
		//执行
		if (!$this->upload->do_upload('upimg_'.$num)){
			$data = $this->upload->display_errors();
			echo false;
		}else {
			//文件信息
			$info = $this->upload->data();
			//上传文件重命名
			$name = date('YmdHis').rand(100,999).$info['file_ext'];
			$F1 = $config['upload_path'].$info['file_name'];
			$F2 = $config['upload_path'].$name;
			//移动文件
			if(rename($F1,$F2)) {
				$this->load->model('web_news_m');
				$file = $this->web_news_m->getOne('upload');
				$url = $this->input->post('img_url');
				//删除原文件
				if($url) {
					@unlink($config['upload_path'].basename($url));
					$data['upload'] = str_replace($url, $name, $file->upload);
				}else{
					$data['upload'] = $file->upload.','.$name;
				}
				echo $this->web_news_m->updateImg($data)?'{"num":"'.$num.'","name":"'.$name.'"}':false;
			}
		}
	}
	//删除图片
	function RemoveIMG($num=''){
		$path = '../upload/images/news/';
		$url = $this->input->post('img_url');
		if($url){
			$this->load->model('web_news_m');
			$file = $this->web_news_m->getOne('upload');
			@unlink($path.$url);
			$data['upload'] = str_replace(','.$url, '', $file->upload);
			echo $this->web_news_m->updateImg($data)?'{"status":"y"}':false;
		}else{echo '{"status":"y"}';}
	}
}