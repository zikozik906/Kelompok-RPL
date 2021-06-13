<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $row = fetch_single_row($edit); ?>

<div class="box-body big">
    <?php echo form_open('',array('name'=>'faddmenugrup','class'=>'form-horizontal','role'=>'form'));?>
        
        <div class="form-group">
            <label class="col-sm-4 control-label">Nama Bahan</label>
            <div class="col-sm-8">
            <?php echo form_hidden('id',$row->id); ?>
            <div>
                <select class="form-control" name="id_nama_bahan">
                <?php foreach ($nama_bahan->result() as $alat): ?>
                    <option value="<?= $alat->id ?>" <?= $alat->id == $row->id_nama_bahan ? "selected" : null ?>><?= $alat->nama_bahan ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <?php echo form_error('nama_alat');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Satuan Bahan</label>
            <div class="col-sm-8">
            <div >
                <select class="form-control" name="satuan_bahan">
                <?php foreach ($satuan->result() as $satuan): ?>
                    <option value="<?= $satuan->id ?>" <?= $satuan->id == $row->satuan_bahan ? "selected" : null ?>><?= $satuan->nama_satuan ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <?php echo form_error('satuan_alat');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Kategori</label>
            <div class="col-sm-8">
            <div >
                <select class="form-control" name="kategori">
                <?php foreach ($kategori->result() as $kategori): ?>
                    <option value="<?= $kategori->id ?>" <?= $kategori->id == $row->kategori ? "selected" : null ?>><?= $kategori->kategori_alat_bahan ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <?php echo form_error('kategori');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Stock</label>
            <div class="col-sm-8">
            <?php echo form_input(array('name'=>'stock','class'=>'form-control', 'value'=>$row->stock));?>
            <?php echo form_error('kode');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Stock Minimal</label>
            <div class="col-sm-8">
            <?php echo form_input(array('name'=>'stock_minimal','class'=>'form-control', 'value'=>$row->stock_minimal));?>
            <?php echo form_error('kode');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Lokasi</label>
            <div class="col-sm-8">
            <div>
                <select class="form-control" name="lokasi">
                <?php foreach ($lokasi->result() as $lokasi): ?>
                    <option value="<?= $lokasi->id ?>" <?= $lokasi->id == $row->lokasi ? "selected" : null ?>><?= $lokasi->nama_penyimpanan ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <?php echo form_error('kode');?>
            <span id="check_data"></span>
            </div>
        </div>            
        <div class="form-group">
            <label class="col-sm-4 control-label">Pendanaan</label>
            <div class="col-sm-8">
            <div >
                <select class="form-control" name="pendanaan">
                <?php foreach ($dana->result() as $dana): ?>
                    <option value="<?= $dana->id ?>" <?= $dana->id == $row->pendanaan ? "selected" : null ?>><?= $dana->sumber_pendanaan ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <?php echo form_error('kode');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Harga</label>
            <div class="col-sm-8">
            <?php echo form_input(array('name'=>'harga','class'=>'form-control', 'value'=>$row->harga));?>
            <?php echo form_error('kode');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Kondisi</label>
            <div class="col-sm-8">
            <div >
                <select class="form-control" name="kondisi">
                <?php foreach ($kondisi->result() as $kondisir): ?>
                    <option value="<?= $kondisir->id ?>" <?= $kondisir->id == $row->kondisi ? "selected" : null ?>><?= $kondisir->kondisi ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <?php echo form_error('kondisi');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Jenis Bahan</label>
            <div class="col-sm-8">
            <div >
                <select class="form-control" name="jenis">
                    <option value="1" <?= $row->kondisi==1 ? "selected" : null ?>>Habis Pakai</option>
                    <option value="2" <?= $row->kondisi==2 ? "selected" : null ?>>Tidak Habis Pakai</option>
                </select>
            </div>
            <?php echo form_error('jenis');?>
        </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Tahun</label>
            <div class="col-sm-8">
            <div >
                <select class="form-control" name="tahun">
                <option >Pilih</option>
                <?php for ($i=date('Y')-5;$i<date('Y')+5;$i++): ?>
                    <option value="<?=$i?>" <?=($row->tahun==$i)?'selected':''?> ><?= $i ?></option>
                <?php endfor; ?>
                </select>
            </div>
            <?php echo form_error('kode');?>
            <span id="check_data"></span>
            </div>
        </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Keterangan</label>
            <div class="col-sm-8">
            <?php echo form_input(array('name'=>'keterangan','class'=>'form-control', 'value'=>$row->keterangan));?>
            <?php echo form_error('keterangan');?>
            <span id="check_data"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Simpan</label>
            <div class="col-sm-8 tutup">
            <?php
            echo button('send_form(document.faddmenugrup,"kelola/kelola_bahan/show_editForm/","#divsubcontent")','Save','btn btn-success')." ";
            ?>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $(".select2").select2();
    $('.tutup').click(function(e) {  
        $('#myModal').modal('hide');
    });
});
</script>