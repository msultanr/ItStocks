<?php
session_start();
//membuat koneksi kedatabase
$conn = mysqli_connect("localhost", "root", "", "stockbarang");

//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    if($addtotable){
        header('location:home.php');
    }else{
        echo 'Gagal';
        header('location:home.php');
    }
};

//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) values ('$barangnya', '$penerima' , '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock set stock='$tambahstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    }else{
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if($stocksekarang >= $qty){
        //kalau barangnya cukup
        $tambahstocksekarangdenganquantity = $stocksekarang-$qty;

        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) values ('$barangnya', '$penerima' , '$qty')");
        $updatestockmasuk = mysqli_query($conn, "UPDATE stock set stock='$tambahstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtokeluar&&$updatestockmasuk){
            header('location:keluar.php');
        }else{
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        //kalau barangnya gak cukup
        echo '
        <script>
            alert("Stock saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>
        ';
    }
}
//Update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi' WHERE idbarang='$idb' ");
    if($update){
        echo 'Data anda berhasil diupdate';
        header('location:home.php');
    }else{
        echo 'Gagal';
        header('location:home.php');
    
}
}

//Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idb'");
    if($hapus){
        echo 'Data anda berhasil dihapus';
        header('location:home.php');
    }else{
        echo 'Gagal';
        header('location:home.php');
    
}
}
//Edit data barang masuk
if(isset($_POST['updatebarangmasuk'])){
   
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang= '$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrng = $stocknya['stock'];

    $qtyskrng = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrng);
    $qtyskrng = $qtynya['qty'];

    if($qty>$qtyskrng){
        $selisih = $qty-$qtyskrng;
        $kurangin = $stockskrng + $selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
            if($kuranginstocknya&&$updatenya){
                    header('location:masuk.php');
                }else{
                    echo 'Gagal';
                    header('location:masuk.php');

            }
    }else{
        $selisih = $qtyskrng-$qty;
        $kurangin = $stockskrng - $selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
            if($kuranginstocknya&&$updatenya){
                    header('location:masuk.php');
                }else{
                    echo 'Gagal';
                    header('location:masuk.php');

        }
    }
}

// Menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn,"SELECT * from stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok-$qty;

    $update = mysqli_query($conn,"UPDATE stock set stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"DELETE from masuk WHERE idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    }
    else {
        header('location:masuk.php');
    }
}

//Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
   
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang= '$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrng = $stocknya['stock'];

    $qtyskrng = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrng);
    $qtyskrng = $qtynya['qty'];

    if($qty>$qtyskrng){
        $selisih = $qty-$qtyskrng;
        $kurangin = $stockskrng - $selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
            if($kuranginstocknya&&$updatenya){
                    header('location:keluar.php');
                }else{
                    echo 'Gagal';
                    header('location:keluar.php');

            }
    }else{
        $selisih = $qtyskrng-$qty;
        $kurangin = $stockskrng + $selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
            if($kuranginstocknya&&$updatenya){
                    header('location:keluar.php');
                }else{
                    echo 'Gagal';
                    header('location:keluar.php');

        }
    }
}

// Menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn,"SELECT * from stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok+$qty;

    $update = mysqli_query($conn,"UPDATE stock set stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"DELETE from keluar WHERE idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    }
    else {
        header('location:keluar.php');
    }
}

//menambah admin baru
if (isset($_POST['addadmin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn,"insert into login (email, password) values('$email','$password')");

    if($queryinsert){
        //if berhasil
        header('location:admin.php');

    }else {
        //if gagal insert ke db
        header('location:admin.php');
    }
}

//edit data admin
if(isset($_POST['updateadmin'])){
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "update login set email='$emailbaru', password='$passwordbaru' where iduser = '$idnya'");

    if($queryupdate){
        header('location:admin.php');

    }else {
        header('location:admin.php');
    }
}

//hapus admin
if(isset($_POST['hapusadmin'])){
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "delete from login where iduser = '$id'");

    if($querydelete){
        header('location:admin.php');
        
    }else {
        header('location:admin.php');
    }
    
}
?>