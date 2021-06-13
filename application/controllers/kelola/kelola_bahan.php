<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelola_bahan extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->fungsi->restrict();
        $this->load->model('kelola/m_kelola_bahan');
		$this->load->model('master/m_master_bahan');
		$this->load->model('master/m_satuan');
		$this->load->model('master/m_kategori_alat_dan_bahan');
		$this->load->model('master/m_sumber_pendanaan');
		$this->load->model('kelola/m_kelola_penyimpanan');
		$this->load->model('master/m_nama_alat');
    }

    public function index()
    {
        $this->fungsi->check_previleges('kelola_bahan');
		$data['kelola_bahan'] = $this->m_kelola_bahan->getData();
		$data['data_peminjaman'] = $this->m_kelola_bahan->get_peminjaman();
		$data['data_hapus'] = $this->m_kelola_bahan->get_hapus();
        $this->load->view('kelola/kelola_bahan/kelola_bahan_list', $data);
    }

    public function form($param='')
	{
		$content   = "<div id='divsubcontent'></div>";
		$header    = "Form Kelola Bahan";
		$subheader = "kelola_bahan";
		$buttons[] = button('jQuery.facebox.close()','Tutup','btn btn-default','data-dismiss="modal"');
		echo $this->fungsi->parse_modal($header,$subheader,$content,$buttons,"");
		if($param=='base'){
			$this->fungsi->run_js('load_silent("kelola/kelola_bahan/show_addForm/","#divsubcontent")');	
		}
		else{
			$base_kom=$this->uri->segment(5);
			$this->fungsi->run_js('load_silent("kelola/kelola_bahan/show_editForm/'.$base_kom.'","#divsubcontent")');	
		}
	}

    public function show_addForm()
	{
		$this->fungsi->check_previleges('kelola_bahan');
		$this->load->library('form_validation');
		$config = array(
				array(
					'field'	=> 'id_nama_bahan',
					'label' => 'id_nama_alat',
					'rules' => 'required'
                ),
                array(
					'field'	=> 'satuan_bahan',
					'label' => 'satuan_alat',
                    'rules' => 'required'
                )
			);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['nama_bahan'] = $this->m_master_bahan->getData();
			$data['satuan'] = $this->m_satuan->getData();
			$data['kategori'] = $this->m_kategori_alat_dan_bahan->getData();
			$data['dana'] = $this->m_sumber_pendanaan->getData();
			$data['lokasi'] = $this->m_kelola_penyimpanan->getDatatersedia();
			$data['kondisi'] = $this->m_nama_alat->getDatakondisi();
			// $data['status']='';
			$this->load->view('kelola/kelola_bahan/kelola_bahan_add',$data);
		}
		else
		{
			$datapost = get_post_data(array('id','id_nama_bahan','satuan_bahan','kategori', 'stock', 'stock_minimal', 'lokasi', 'pendanaan', 'harga', 'jenis','kondisi','tahun','keterangan'));
            $this->m_kelola_bahan->insertData($datapost);
			$this->fungsi->run_js('load_silent("kelola/kelola_bahan","#content")');
			$this->fungsi->message_box("Data Kelola Nama Bahan sukses disimpan...","success");
			$this->fungsi->catat($datapost,"Menambah Kelola kelola_bahan dengan data sbb:",true);
		}
    }
	public function listpinjam($param='',$id)
	{
		$content   = "<div id='divsubcontent'></div>";
		$header    = "Form Kelola Bahan";
		$subheader = "kelola bahan";
		$buttons[] = button('jQuery.facebox.close()','Tutup','btn btn-default','data-dismiss="modal"');
		echo $this->fungsi->parse_modal($header,$subheader,$content,$buttons,"");
		if($param=='pinjam'){
			$this->fungsi->run_js('load_silent("kelola/kelola_bahan/showpinjam/'.$id.'","#divsubcontent")');	
		}
		else{
			$base_kom=$this->uri->segment(5);
			$this->fungsi->run_js('load_silent("kelola/kelola_bahan/showhabis/'.$id.'","#divsubcontent")');	
		}
	}
	public function showpinjam($id)
	{
		$data['detail']=$this->m_kelola_bahan->pinjambahan($id);
		$this->load->view('kelola/kelola_bahan/dipinjam',$data);
	}
	public function showhabis($id)
	{
		$data['detail']=$this->m_kelola_bahan->habisbahan($id);
		$this->load->view('kelola/kelola_bahan/habis',$data);
	}
    public function show_editForm($id='')
	{
		$this->fungsi->check_previleges('kelola_bahan');
		$this->load->library('form_validation');
		$config = array(
			array(
				'field'	=> 'id_nama_bahan',
				'label' => 'id_nama_alat',
				'rules' => 'required'
            ),
            array(
				'field'	=> 'satuan_bahan',
				'label' => 'satuan_bahan',
				'rules' => 'required'
			)
		);

	    $this->form_validation->set_rules($config);
	    $this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['edit'] = $this->db->get_where('kelola_bahan',array('id'=>$id));
			$data['nama_bahan'] = $this->m_master_bahan->getData();
			$data['satuan'] = $this->m_satuan->getData();
			$data['kategori'] = $this->m_kategori_alat_dan_bahan->getData();
            $data['dana'] = $this->m_sumber_pendanaan->getData();
			$data['lokasi'] = $this->m_kelola_penyimpanan->getDatatersedia();
			$data['kondisi'] = $this->m_nama_alat->getDatakondisi();

		$this->load->view('kelola/kelola_bahan/kelola_bahan_edit',$data);
		}
		else
		{
			$datapost = get_post_data(array('id','id_nama_bahan','satuan_bahan','kategori', 'stock', 'stock_minimal', 'lokasi', 'pendanaan', 'harga','jenis','kondisi','tahun','keterangan'));
			$this->m_kelola_bahan->updateData($datapost);
			$this->fungsi->run_js('load_silent("kelola/kelola_bahan","#content")');
			$this->fungsi->message_box("Data Kelola Bahan sukses diperbarui...","success");
			$this->fungsi->catat($datapost,"Mengedit Kelola kelola_alat dengan data sbb:",true);
		}
	}

    public function delete($id)
    {
        $this->fungsi->check_previleges('kelola_bahan');
		if($id == '' || !is_numeric($id)) die;
		$this->m_kelola_bahan->deleteData($id);
		$this->fungsi->run_js('load_silent("kelola/kelola_bahan","#content")');
		$this->fungsi->message_box("Data Kelola Bahan Berhasil dihapus...","notice");
		$this->fungsi->catat("Menghapus laporan dengan id ".$id);
    }
	
	public function cetak()
    {
        $this->fungsi->check_previleges('kelola_bahan');
		$data['kelola_bahan'] = $this->m_kelola_bahan->getData();
		$data['data_peminjaman'] = $this->m_kelola_bahan->get_peminjaman();
		$data['data_hapus'] = $this->m_kelola_bahan->get_hapus();
        $this->load->view('kelola/kelola_bahan/cetak_kelola_bahan', $data);
    }
}