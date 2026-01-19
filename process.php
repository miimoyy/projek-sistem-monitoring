<?php

    include 'connect.php';
    session_start();

    if(isset($_POST['execute'])){
    if($_POST['execute'] == "add"){

        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $id_rack = $_POST['id_rack']; 

        $query = "INSERT INTO access (email, username, password, role, id_rack) 
                  VALUES ('$email', '$username', '$password', '$role', '$id_rack')";
        $sql = mysqli_query($conn, $query);

        if($sql){
            header("location: userlists.php");
        } else {
            echo $query;
        }
        
    } else if($_POST['execute'] == "edit"){

        $id_access = $_POST['id_access'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $id_rack = $_POST['id_rack'];

        $query = "UPDATE access 
                  SET email='$email', username='$username', password='$password', role='$role', id_rack='$id_rack' 
                  WHERE id_access='$id_access';";
        $sql = mysqli_query($conn, $query);
    }

    if($sql){
        header("location: userlists.php");
    } else {
        echo $query;
    }
}


    if(isset($_GET['delete'])){
        $id_access = $_GET['delete'];
        $query = "DELETE FROM access WHERE id_access = '$id_access'";
        $sql = mysqli_query($conn, $query);

        if($sql){
            header("location: userlists.php");
        }
        
    }


    if(isset($_POST['execute_rack'])){
        if($_POST['execute_rack'] == "add"){

            $number = $_POST['number'];
            $company = $_POST['company'];
            $description = $_POST['description'];

            $query = "INSERT INTO number_rack (number, company, description) VALUES ('$number', '$company', '$description')";
            $sql = mysqli_query($conn, $query);

            if($sql){
                // $_SESSION['eksekusi'] = "Data berhasil ditambahkan!";
                // echo "<script>alert('Maaf data terlambat datang, akan d!');window.location='monitor.php'</script>";
                // echo '<script>alert("Data berhasil ditambahkan!"); location.href="monitor.php";</script>';
                header("location: rack.php");
            } else {
                echo $query;
            }
            
        } else if($_POST['execute_rack'] == "edit"){
            
            $id_rack = $_POST['id_rack'];
            $number = $_POST['number'];
            $company = $_POST['company'];
            $description = $_POST['description'];

            $query = "UPDATE number_rack SET number='$number', company='$company', description='$description' WHERE id_rack='$id_rack';";
            $sql = mysqli_query($conn, $query);
        }

        if($sql){
            header("location: rack.php");
        } else {
            echo $query;
        }
    }

    if(isset($_GET['delete'])){
        $id_rack = $_GET['delete'];
        $query = "DELETE FROM number_rack WHERE id_rack = '$id_rack'";
        $sql = mysqli_query($conn, $query);

        if($sql){
            header("location: rack.php");
        }
        
    }

        if(isset($_POST['execute_sensor'])){
        if($_POST['execute_sensor'] == "add"){

            $name = $_POST['name'];
            $id_rack = $_POST['id_rack'];

            $query = "INSERT INTO sensor (name, id_rack) VALUES ('$name', '$id_rack')";
            $sql = mysqli_query($conn, $query);

            if($sql){
                // $_SESSION['eksekusi'] = "Data berhasil ditambahkan!";
                // echo "<script>alert('Maaf data terlambat datang, akan d!');window.location='monitor.php'</script>";
                // echo '<script>alert("Data berhasil ditambahkan!"); location.href="monitor.php";</script>';
                header("location: sensor.php");
            } else {
                echo $query;
            }
            
        } else if($_POST['execute_sensor'] == "edit"){
            
            $id_sensor = $_POST['id_sensor'];
            $name = $_POST['name'];
            $id_rack = $_POST['id_rack'];

            $query = "UPDATE sensor SET name='$name', id_rack='$id_rack' WHERE id_sensor='$id_sensor';";
            $sql = mysqli_query($conn, $query);
        }

        if($sql){
            header("location: sensor.php");
        } else {
            echo $query;
        }
    }

    if(isset($_GET['delete'])){
        $id_sensor = $_GET['delete'];
        $query = "DELETE FROM sensor WHERE id_sensor = '$id_sensor'";
        $sql = mysqli_query($conn, $query);

        if($sql){
            header("location: sensor.php");
        }
        
    }

?>