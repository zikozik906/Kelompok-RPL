<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_alat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fungsi->restrict();
		$this->load->model('pengajuan/m_pengajuan_alat');
		$this->load->model('pengajuan/m_periode_pengajuan');
	}

	public function index()
	{
		$this->fungsi->check_previleges('pengajuan_alat');
		$data['pengajuan_alat'] = $this->m_pengajuan_alat->join();
		$this->load->view('pengajuan/pengajuan_alat/v_pengajuan_alat_list',$data);
	}

	public function form($param='')
	{
		$content   = "<div id='divsubcontent'></div>";
		$header    = "Form Pengajuan Alat";
		$subheader = "Pengajuan Alat";
		$buttons[] = button('jQuery.facebox.close()','Tutup','btn btn-default','data-dismiss="modal"');
		echo $this->fungsi->parse_modal($header,$subheader,$content,$buttons,"");
		if($param=='base'){
			$this->fungsi->run_js('load_silent("pengajuan/pengajuan_alat/show_addForm/","#divsubcontent")');	
		}else{
			$base_kom=$this->uri->segment(5);
			$this->fungsi->run_js('load_silent("pengajuan/pengajuan_alat/show_editForm/'.$base_kom.'","#divsubcontent")');	
		}
	}

	public function show_addForm()
	{
		$this->fungsi->check_previleges('pengajuan_alat');
		$this->load->library('form_validation');
		$config = array(
				array(
					'field'	=> 'id_periode',
					'label' => 'id_periode',
					'rules' => 'required'
				)
			);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['status']='';
			$data['periode_pengajuan'] = $this->m_periode_pengajuan->getData();
			$this->load->view('pengajuan/pengajuan_alat/v_pengajuan_alat_add',$data);
		}
		else
		{
			$datapost = get_post_data(array('id_pengajuan','id_periode','pengaju','nama_alat','jumlah','harga_satuan','status'));
			$this->m_pengajuan_alat->insertData($datapost);
			$this->fungsi->run_js('load_silent("pengajuan/pengajuan_alat","#content")');
			$this->fungsi->message_box("Data pengajuan sukses disimpan...","success");
			$this->fungsi->catat($datapost,"Menambah pengajuan dengan data sbb:",true);
		}
	}

	public function show_editForm($id='')
	{
		$this->fungsi->check_previleges('pengajuan_alat');
		$this->load->library('form_validation');
		$config = array(
				array(
					'field'	=> 'id_pengajuan',
					'label' => 'id_pengajuan',
					'rules' => ''
				),
				array(
					'field'	=> 'id_periode',
					'label' => 'id_periode',
					'rules' => 'required'
				)
			);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['edit'] = $this->db->get_where('pengajuan_alat',array('id_pengajuan'=>$id));
			$data['status']='';
			$data['id'] = $this->m_periode_pengajuan->getData();
			$this->load->view('pengajuan/pengajuan_alat/v_pengajuan_alat_edit',$data);
		}
		else
		{
			$datapost = get_post_data(array('id_pengajuan','id_periode','pengaju','nama_alat','jumlah','harga_satuan','status'));
			$this->m_pengajuan_alat->updateData($datapost);
			$this->fungsi->run_js('load_silent("pengajuan/pengajuan_alat","#content")');
			$this->fungsi->message_box("Data Pengajuan sukses diperbarui...","success");
			$this->fungsi->catat($datapost,"Mengedit pengajuan_alat dengan data sbb:",true);
		}
    }
    public function delete($id) 
	{
		$this->fungsi->check_previleges('pengajuan_alat');
		if($id == '' || !is_numeric($id)) die;
		$this->m_pengajuan_alat->deleteData($id);
		$this->fungsi->run_js('load_silent("pengajuan/pengajuan_alat","#content")');
		$this->fungsi->message_box("Data Pengajuan Alat berhasil dihapus...","notice");
		$this->fungsi->catat("Menghapus laporan dengan id ".$id);
	}
	public function pdf(){

		$this->load->library('dompdf_gen');

		//$data['pengajuan_alat'] = $this->m_pengajuan_alat->getData('pengajuan_alat')->result();
		$data['pengajuan_alat'] = $this->m_pengajuan_alat->hitung()->result();
		
		$this->load->view('pengajuan_alat_pdf', $data);

		$paper_size = 'A4';
		$orientation = 'landscape';
		$html = $this->output->get_output();
		$this->dompdf->set_paper($paper_size, $orientation);

		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream("Pengajuan alat SILADU.pdf", array('Attachment'=>0));
	}
	
	public function cetak()
	{
		$data['pengajuan_alat'] = $this->m_pengajuan_alat->hitung()->result();
		$this->load->view('pengajuan/pengajuan_alat/v_cetak_pengajuan_alat', $data);
	}
}