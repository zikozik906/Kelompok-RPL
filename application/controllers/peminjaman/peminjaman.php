<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fungsi->restrict();
		$this->load->model('peminjaman/m_peminjaman');

	}

	public function index()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getDatapinjam();
		$data['ket']		= "DRAFT";
		$data['isi']		= "index";
		$this->load->view('peminjaman/peminjaman/v_peminjaman_list',$data);
	}
	public function praktikum()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getDatapraktikum();
		$data['ket']		= "DRAFT";
		$this->load->view('peminjaman/peminjaman/v_praktikum_list',$data);
	}
	public function kelola_jatuh_tempo()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getDatajatuhtempo();
		$data['ket']		= "Peminjaman Jatuh Tempo";
		$data['isi']		= "jatuh_tempo";
		$this->load->view('peminjaman/peminjaman/v_peminjaman_list',$data);
	}
	public function kelola_peminjaman()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getData(4);
		$data['ket']		= "Riwayat Semua Peminjaman";
		$data['isi']		= "kelola_peminjaman";
		$this->load->view('peminjaman/peminjaman/v_peminjaman_list',$data);
	}
	public function kelola_penggantian()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getData(3);
		$data['ket']		= "Riwayat Semua Peminjaman";
		$data['isi']		= "kelola_penggantian";
		$this->load->view('peminjaman/peminjaman/v_peminjaman_list',$data);
	}
	function setujui($id)
	{
		#id=idpeminjaman
		$this->m_peminjaman->setujuiPinjaman($id);
		$this->fungsi->run_js('load_silent("peminjaman/peminjaman/detail/'.$id.'","#content")');
		$this->fungsi->message_box("Berhasil Menyetujui peminjaman","success");
	}
	public function kelola()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getData(1);
		$data['ket']		= "Peminjaman Belum Dikembalikan";
		$data['isi']		= "kelola";
		$this->load->view('peminjaman/peminjaman/v_peminjaman_list',$data);
	}
	function kembalikanAll($id)
	{
		$this->m_peminjaman->kembalikanPinjaman($id);
		$this->fungsi->run_js('load_silent("peminjaman/peminjaman/kelola/'.$id.'","#content")');
		$this->fungsi->message_box("Berhasil Mengembalikan semua peminjaman","success");
	}

	public function detail($id='')
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjam'] = $this->m_peminjaman->getDataDet($id)->row();
		$data['bahanlist'] = $this->m_peminjaman->getDetailBahan($id);
		$data['alatlist'] = $this->m_peminjaman->getDetailAlat($id);
		$this->load->view('peminjaman/peminjaman/v_peminjaman_detail',$data);
	}
	public function kembalisebagian($param='',$barang,$idpeminjaman,$iddetail)
	{
		$content   = "<div id='divsubcontent'></div>";
		$header    = "Form Pengembalian Alat Sebagian";
		$subheader = "kelola_alat";
		$buttons[] = button('jQuery.facebox.close()','Tutup','btn btn-default','data-dismiss="modal"');
		echo $this->fungsi->parse_modal($header,$subheader,$content,$buttons,"");
		if($param=='base'){
			$this->fungsi->run_js('load_silent("peminjaman/peminjaman/kembalikansebagianform/'.$barang.'/'.$idpeminjaman.'/'.$iddetail.'","#divsubcontent")');	
		}
	}
	public function kembalikansebagianform($barang,$idpeminjaman,$iddetail)
	{
		$this->fungsi->check_previleges('peminjaman');
		$this->load->library('form_validation');
		$config = array(
				array(
					'field'	=> 'qty',
					'label' => 'Jumlah Dikembalikan',
					'rules' => 'required'
                ),
                array(
					'field'	=> 'qty_hapus',
					'label' => 'satuan_alat',
                    'rules' => 'required'
				),
			);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="error-span">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['barang']=$barang;
			$data['id']=$iddetail;
			$data['detail']=$this->m_peminjaman->get_detail_pinjaman($barang,$iddetail);
			$this->load->view('peminjaman/peminjaman/v_kembali_sebagian',$data);
		}
		else
		{
			
			$datapost = get_post_data(array('id','qty','qty_awal','qty_hapus'));
			$this->m_peminjaman->update_detail_pinjaman($barang,$datapost);
			$id_pin=$this->m_peminjaman->get_detail_pinjaman($barang,$datapost['id'])->id_peminjaman;
			$this->fungsi->run_js('load_silent("peminjaman/peminjaman/detail/'.$id_pin.'","#content")');
			$this->fungsi->message_box("Data Kelola Nama Alat sukses disimpan...","success");
			$this->fungsi->catat($datapost,"Menambah Kelola kelola_alat dengan data sbb:",true);
		}
	}
	public function formAdd($value='')
	{
		$this->fungsi->check_previleges('peminjaman');

		$data = array(
			'bahan'        => $this->m_peminjaman->get_kelola_("bahan")->result(),
			'alat'         => $this->m_peminjaman->get_kelola_("alat")->result(),
			'nomor'        => str_pad($this->m_peminjaman->getNopeminjaman(), 4, '0', STR_PAD_LEFT).date('m').date('Y'),
			 );
			 
		$this->load->view('peminjaman/peminjaman/v_peminjaman_add',$data);
	}
	public function formAddprak($value='')
	{
		$this->fungsi->check_previleges('peminjaman');

		$data = array(
			'bahan'        => $this->m_peminjaman->get_kelola_("bahan")->result(),
			'alat'         => $this->m_peminjaman->get_kelola_("alat")->result(),
			'nomor'        => str_pad($this->m_peminjaman->getNopeminjaman(), 4, '0', STR_PAD_LEFT).date('m').date('Y'),
			 );
			 
		$this->load->view('peminjaman/peminjaman/v_praktikum_add',$data);
	}

	public function formEdit($id='')
	{
		$this->fungsi->check_previleges('peminjaman');
		$data = array(
			'bahan'        => $this->m_peminjaman->get_kelola_("bahan")->result(),
			'alat'         => $this->m_peminjaman->get_kelola_("alat")->result(),
			 );
		$data['lol'] = $this->m_peminjaman->getDataDet($id)->row();
		$data['bahanlist'] = $this->m_peminjaman->getDetailBahan($id);
		$data['alatlist'] = $this->m_peminjaman->getDetailAlat($id);
		$this->load->view('peminjaman/peminjaman/v_peminjaman_edit',$data);
	}
	public function formEditprak($id='')
	{
		$this->fungsi->check_previleges('peminjaman');
		$data = array(
			'bahan'        => $this->m_peminjaman->get_kelola_("bahan")->result(),
			'alat'         => $this->m_peminjaman->get_kelola_("alat")->result(),
			 );
		$data['lol'] = $this->m_peminjaman->getDataDet($id)->row();
		$data['bahanlist'] = $this->m_peminjaman->getDetailBahan($id);
		$data['alatlist'] = $this->m_peminjaman->getDetailAlat($id);
		$this->load->view('peminjaman/peminjaman/v_praktikum_edit',$data);
	}

	public function savepeminjaman($value='')
	{
		$this->fungsi->check_previleges('peminjaman');
	  	$datapost = array(
			'no_peminjaman' => $this->input->post('nomor'),		
			'peminjam'      => $this->input->post('peminjam'),		
			'status_peminjam'=> $this->input->post('status_peminjam'),		
			'kontak'		=> $this->input->post('kontak'),		
			'instansi'		=> $this->input->post('instansi'),		
			'jen_id'		=> $this->input->post('jen_id'),		
			'jenis_pinjaman'=> $this->input->post('jenis_pinjaman'),		
			'id_peminjam'   => $this->input->post('id_peminjam'),		
			'tgl'           => $this->input->post('tgl'),		
			'jam_pinjam'    => $this->input->post('jam_pinjam'),		
			'tgl_kembali'   => $this->input->post('tgl_kembali'),
			'jam_kembali'    => $this->input->post('jam_kembali'),			
			);

	  	$this->m_peminjaman->insertpeminjaman($datapost);
		$data['id'] = $this->db->insert_id();


		$this->fungsi->catat($datapost,"Menyimpan peminjaman sbb:",true);
		$data['msg'] = "Tgl peminjaman Disimpan";
		echo json_encode($data);
	}
	public function editpeminjaman($value='')
	{
		$this->fungsi->check_previleges('peminjaman');
	  	$datapost = array(
			'id' 			=> $this->input->post('id'),		
			'no_peminjaman' => $this->input->post('nomor'),		
			'peminjam'      => $this->input->post('peminjam'),		
			'status_peminjam'=> $this->input->post('status_peminjam'),		
			'kontak'		=> $this->input->post('kontak'),		
			'instansi'		=> $this->input->post('instansi'),	
			'jenis_pinjaman'=> $this->input->post('jenis_pinjaman'),			
			'jen_id'		=> $this->input->post('jen_id'),		
			'id_peminjam'   => $this->input->post('id_peminjam'),		
			'tgl'           => $this->input->post('tgl'),		
			'jam_pinjam'    => $this->input->post('jam_pinjam'),		
			'tgl_kembali'   => $this->input->post('tgl_kembali'),
			'jam_kembali'    => $this->input->post('jam_kembali'),
			);

	  	$this->m_peminjaman->editpeminjaman($datapost);
		$data['id'] = $this->db->insert_id();


		$this->fungsi->catat($datapost,"Menyimpan peminjaman sbb:",true);
		$data['msg'] = "Tgl peminjaman Disimpan";
		echo json_encode($data);
	}

	

	public function savepeminjamanbahan($value='')
	{
		$this->fungsi->check_previleges('peminjaman');
	  	$datapost = array(
			'id_peminjaman' => $this->input->post('id_peminjaman'),
			'id_bahan'      => $this->input->post('bahan'),
			'qty'           => $this->input->post('qty_bahan'),
		 );
		
	    $this->m_peminjaman->insertpeminjamanbahan($datapost);
	    $this->fungsi->catat($datapost,"Menyimpan item peminjaman sbb:",true);
		$data['msg'] = "peminjaman Disimpan";
		echo json_encode($data);
	}

	public function savepeminjamanalat($value='')
	{
		$this->fungsi->check_previleges('peminjaman');
	  	$datapost = array(
			'id_peminjaman' => $this->input->post('id_peminjaman'),
			'id_alat'       => $this->input->post('alat'),
			'qty'           => $this->input->post('qty_alat'),
 		);
	    $this->m_peminjaman->insertpeminjamanalat($datapost);
	    $this->fungsi->catat($datapost,"Menyimpan item peminjaman sbb:",true);
		$data['msg'] = "peminjaman Disimpan";
		echo json_encode($data);
	}

	public function delete() {
		$this->fungsi->check_previleges('peminjaman');
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->delete('data_peminjaman_bahan');
		$respon = ['msg' => 'Data Berhasil Dihapus'];
		echo json_encode($respon);
	}

	public function deletealat() {
		$this->fungsi->check_previleges('peminjaman');
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->delete('data_peminjaman_alat');
		$respon = ['msg' => 'Data Berhasil Dihapus'];
		echo json_encode($respon);
	}
	public function deletebahan() {
		$this->fungsi->check_previleges('peminjaman');
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->delete('data_peminjaman_bahan');
		$respon = ['msg' => 'Data Berhasil Dihapus'];
		echo json_encode($respon);
	}
	function hapuspeminjaman($id)
	{
		$this->m_peminjaman->hapuspeminjaman($id);
		$this->fungsi->run_js('load_silent("peminjaman/peminjaman/","#content")');
		$this->fungsi->message_box("Berhasil Mengembalikan peminjaman","success");
	}
	function kembalikan($alatbahan,$peminjaman,$objek)
	{
		$this->m_peminjaman->cekActAlatBahan($alatbahan,$peminjaman,$objek,2);
		$this->m_peminjaman->cekpeminjamanclear($peminjaman);
		$this->fungsi->run_js('load_silent("peminjaman/peminjaman/detail/'.$peminjaman.'","#content")');
		$this->fungsi->message_box("Berhasil Mengembalikan peminjaman","success");
	}
	function cekrusak($alatbahan,$peminjaman,$objek)
	{
		$this->m_peminjaman->cekActAlatBahan($alatbahan,$peminjaman,$objek,3);		
		$this->m_peminjaman->cekpeminjamanclear($peminjaman);
		$this->fungsi->run_js('load_silent("peminjaman/peminjaman/detail/'.$peminjaman.'","#content")');
		$this->fungsi->message_box("Mencek Kerusakan Barang","success");
	}
	public function refresh($alatbahan,$peminjaman,$objek)
	{
		$this->m_peminjaman->cekActAlatBahan($alatbahan,$peminjaman,$objek,1);
		$this->m_peminjaman->cekpeminjamanclear($peminjaman);
		$this->fungsi->run_js('load_silent("peminjaman/peminjaman/detail/'.$peminjaman.'","#content")');
		$this->fungsi->message_box("Berhasil Mengembalikan status","success");
	}
	
	public function cetak_peminjaman()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getDatapinjam();
		$data['ket']		= "DRAFT";
		$this->load->view('peminjaman/peminjaman/v_cetak_peminjaman', $data);
	}
	
	public function cetak_praktikum()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getDatapraktikum();
		$data['ket']		= "DRAFT";
		$this->load->view('peminjaman/peminjaman/v_cetak_praktikum', $data);
	}
	
	public function cetak_kelola()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getData(1);
		$data['ket']		= "Peminjaman Belum Dikembalikan";
		$this->load->view('peminjaman/peminjaman/v_cetak_kelola', $data);
	}
	
	public function cetak_jatuh_tempo()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getDatajatuhtempo();
		$data['ket']		= "Peminjaman Jatuh Tempo";
		$this->load->view('peminjaman/peminjaman/v_cetak_jatuh_tempo', $data);
	}
	
	public function cetak_kelola_peminjaman()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getData(4);
		$data['ket']		= "Peminjaman Belum Dikembalikan";
		$this->load->view('peminjaman/peminjaman/v_cetak_kelola', $data);
	}
	
	public function cetak_kelola_penggantian()
	{
		$this->fungsi->check_previleges('peminjaman');
		$data['peminjaman'] = $this->m_peminjaman->getData(3);
		$data['ket']		= "Peminjaman Belum Dikembalikan";
		$this->load->view('peminjaman/peminjaman/v_cetak_kelola', $data);
	}

}

/* End of file peminjaman.php */
/* Location: ./application/controllers/peminjaman/peminjaman.php */