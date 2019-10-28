
<!-- Content page -->
<section class="bgwhite p-t-55 p-b-65">
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3 p-b-50">
            <div class="leftbar p-r-20 p-r-0-sm">
                <!--  -->
                <?php include('menu.php') ?>     
            </div>
        </div>

        <div class="col-sm-6 col-md-8 col-lg-9 p-b-50">

                <div class="alert alert-success">
                <h1>Selamat Datang <i><strong><?php echo $this->session->userdata('nama_pelanggan'); ?></strong></i></h1>
                </div>

             <?php
            //kalau ada transaksi tampilkan tabelnya
             if($header_transaksi) {                 
             ?>

            <table class="table table-bordered" width="100%">
                <thead>
                    <tr class="bg-success">
                        <th>NO</th>
                        <th>KODE</th>
                        <th>TANGGAL</th>
                        <th>JUMLAH TOTAL</th>
                        <th>JUMLAH ITEM</th>
                        <th>STATUS BAYAR</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($header_transaksi as $header_transaksi) { ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $header_transaksi->kode_transaksi ?></td>
                        <td><?php echo date('d-m-Y' ,strtotime($header_transaksi->tanggal_transaksi)) ?></td>
                        <td><?php echo number_format($header_transaksi->jumlah_transaksi) ?></td>
                        <td><?php echo $header_transaksi->total_item ?></td>
                        <td><?php echo $header_transaksi->status_bayar ?></td>
                        <td>
                            <a href="<?php echo base_url('dasbor/detail/'.$header_transaksi->kode_transaksi) ?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i>Detail</a>
                        </td>
                    </tr>
                    <?php $i++; } ?>
                </tbody>
            </table>

            <?php
            //kalau tidak ada tampilkan notifikasi
             }else{ ?>

                <p class="alert alert-success">
                    <i class="fa fa-warning"></i>Belum ada transaksi
                </p>

            <?php } ?> 
               
        </div>
    </div>
</div>
</section>
