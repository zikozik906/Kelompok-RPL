<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kelola_alat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fungsi->restrict();
		$this->load->model('kelola/m_kelola_alat');
		$this->load->model('master/m_nama_alat');
		$this->load->model('master/m_satuan');
		$this->load->model('master/m_kategori_alat_dan_bahan');
		$this->load->model('master/m_sumber_pendanaan');
		$this->load->model('kelola/m_kelola_penyimpanan');
	}

	public function index()
	{
		$this->fungsi->check_previleges('kelola_alat');
		$data['kelola_alat'] = $this->m_kelola_alat->join();
		$data['data_peminjaman'] = $this->m_kelola_alat->get_peminjaman();
		$this->load->view('kelola/kelola_alat/v_kelola_alat_list1',$data);
	}
    public function form($param='')
	{
		$content   = "<div id='divsubcontent'></div>";
		$header    = "Form Kelola Nama Alat";
		$subheader = "kelola_alat";
		$buttons[] = button('jQuery.facebox.close()','Tutup','btn btn-default','data-dismiss="modal"');
		echo $this->fungsi->parse_modal($header,$subheader,$content,$buttons,"");
		if($param=='base'){
			$this->fungsi->run_js('load_silent("kelola/kelola_alat/show_addForm/","#divsubcontent")');	
		}
		else{
			$base_kom=$this->uri->segment(5);
			$this->fungsi->run_js('load_silent("kelola/kelola_alat/show_editForm/'.$base_kom.'","#divsubcontent")');	
		}
	}
    public function listpinjam($param='',$id)
	{
		$content   = "<div id='divsubcontent'></div>";
		$header    = "Form Kelola Nama Alat";
		$subheader = "kelola_alat";
		$buttons[] = button('jQuery.facebox.close()','Tutup','btn btn-default','data-dismiss="modal"');
		echo $this->fungsi->parse_modal($header,$subheader,$content,$buttons,"");
		if($param=='pinjam'){
			$this->fungsi->run_js('load_silent("kelola/kelola_alat/showpinjam/'.$id.'","#divsubcontent")');	
		}
		else{
			$base_kom=$this->uri->segment(5);
			$this->fungsi->run_js('load_silent("kelola/kelola_alat/showhabis/'.$id.'","#divsubcontent")');	
		}
	}
	public function showpinjam($id)
	{
		$data['detail']=$this->m_kelola_alat->pinjamalat($id);
		$this->load->view('kelola/kelola_alat/dipinjam',$data);
	}
	public function show_addForm()
	{
		$this->fungsi->check_previleges('kelola_alat');
		$this->load->library('form_validation');
		$config = array(
				array(
					'field'	=> 'id_nama_alat',
					'label' => 'id_nama_alat',
					'rules' => 'required'
                ),
                array(
					'field'	=> 'satuan_alat',
					'label' => 'satuan_alat',
                    'rules' => 'required'
				),
                array(
					'field'	=> 'tahun',
					'label' => 'Tahun',
                    'rules' => 'required'
                )
			);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['nama_alat'] = $this->m_nama_alat->getData();
			$data['satuan'] = $this->m_satuan->getData();
			$data['kategori'] = $this->m_kategori_alat_dan_bahan->getData();
			$data['dana'] = $this->m_sumber_pendanaan->getData();
			$data['lokasi'] = $this->m_kelola_penyimpanan->getDatatersedia();
			$data['kondisi'] = $this->m_nama_alat->getDatakondisi();
			// $data['status']='';
			$this->load->view('kelola/kelola_alat/v_kelola_alat_add1',$data);
		}
		else
		{
			$datapost = get_post_data(array('id','id_nama_alat','satuan_alat','kategori', 'stok', 'stok_minimal', 'lokasi', 'pendanaan', 'harga', 'kondisi','tahun','keterangan'));
			$this->m_kelola_alat->insertData($datapost);
			$this->fungsi->run_js('load_silent("kelola/kelola_alat","#content")');
			$this->fungsi->message_box("Data Kelola Nama Alat sukses disimpan...","success");
			$this->fungsi->catat($datapost,"Menambah Kelola kelola_alat dengan data sbb:",true);
		}
	}

	public function show_editForm($id='')
	{
		$this->fungsi->check_previleges('kelola_alat');
		$this->load->library('form_validation');
		$config = array(
			array(
				'field'	=> 'id',
				'label' => 'wes mbarke',
				'rules' => ''
			),
			array(
				'field'	=> 'id_nama_alat',
				'label' => 'id_nama_alat',
				'rules' => 'required'
            ),
            array(
				'field'	=> 'satuan_alat',
				'label' => 'satuan_alat',
				'rules' => 'required'
			)
		);
	$this->form_validation->set_rules($config);
	$this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['edit'] = $this->db->get_where('kelola_alat',array('id'=>$id));
			$data['nama_alat'] = $this->m_nama_alat->getData();
			$data['satuan'] = $this->m_satuan->getData();
			$data['kategori'] = $this->m_kategori_alat_dan_bahan->getData();
            $data['dana'] = $this->m_sumber_pendanaan->getData();
			$data['lokasi'] = $this->m_kelola_penyimpanan->getDatatersedia();
			$data['kondisi'] = $this->m_nama_alat->getDatakondisi();

			$this->load->view('kelola/kelola_alat/v_kelola_alat_edit1',$data);
		}
		else
		{
			$datapost = get_post_data(array('id','id_nama_alat','satuan_alat','kategori', 'stok', 'stok_minimal', 'lokasi', 'pendanaan', 'harga', 'kondisi','tahun','keterangan'));
			$this->m_kelola_alat->updateData($datapost);
			$this->fungsi->run_js('load_silent("kelola/kelola_alat","#content")');
			$this->fungsi->message_box("Data Kelola Nama Alata sukses diperbarui...","success");
			$this->fungsi->catat($datapost,"Mengedit Kelola kelola_alat dengan data sbb:",true);
		}
	}
	public function delete($id)
	{
		$this->fungsi->check_previleges('kelola_alat');
		if($id == '' || !is_numeric($id)) die;
		$this->m_kelola_alat->deleteData($id);
		$this->fungsi->run_js('load_silent("kelola/kelola_alat","#content")');
		$this->fungsi->message_box("Data Kelola alat berhasil dihapus...","notice");
		$this->fungsi->catat("Menghapus laporan dengan id ".$id);
	}
	public function show_view()
	{
		$this->fungsi->check_previleges('kelola_alat');
		$data['kelola_alat'] = $this->m_kelola_alat->getData();
		$this->load->view('kelola/kelola_alat/v_kelola_alat_seri_list',$data);
	}
	
	public function cetak()
	{
		$this->fungsi->check_previleges('kelola_alat');
		$data['kelola_alat'] = $this->m_kelola_alat->join();
		$data['data_peminjaman'] = $this->m_kelola_alat->get_peminjaman();
		$this->load->view('kelola/kelola_alat/v_cetak_kelola_alat',$data);
	}
}