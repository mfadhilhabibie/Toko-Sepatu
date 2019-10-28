<p>
   <a href="<?php echo base_url('admin/user/tambah') ?>" class="btn btn-success btn-lg"> 
   <i class="fa fa-plus"></i> Tambah Baru
   </a>
</p>

<?php
//notifikasi
if($this->session->flashdata('sukses')){
    echo '<p class="lert alert-success">';
    echo $this->session->flashdata('sukses');
    echo '</div>';
}
?>

<table class="table table-bordered" id="example1">
    <caption>table title and/or explantory text</caption>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>EMAIL</th>
            <th>USERNAME</th>
            <th>LEVEL</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($user as $user) { ?>
        <tr>
            <td><?php echo $no ?></td>
            <td><?php echo $user->nama ?></td>
            <td><?php echo $user->email ?></td>
            <td><?php echo $user->username ?></td>
            <td><?php echo $user->akses_level ?></td>
            <td>
            <a href="<?php echo base_url('admin/user/edit/'.$user->id_user) ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</a>
            
            <a href="<?php echo base_url('admin/user/delete/'.$user->id_user) ?>"
             class="btn btn-danger btn-xs" onclick="return confirm('Yakin mau apus data ini??')"><i class="fa fa-trash-o"></i> Hapus</a>
            </td>
        </tr>
        <?php $no++; } ?>
    </tbody>  
</table>