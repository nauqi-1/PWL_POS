<!DOCTYPE html>
<html>
    <head>
        <title>Ubah Data User</title>
    </head>
    <body>
        <h1>Form Ubah Data User </h1>
        <a href="{{url('/user')}}">Kembali</a>
        <br> <br>

        <form method="post" action="{{url('/user/ubah_simpan/')}}/{{$data->user_id}}">
            {{ csrf_field() }}
            {{ method_field('PUT')}}

            <label>Username</label>
            <input type="text" name="username" placeholder="Masukan username..." value="{{$data -> username}}">
            <br>
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukan nama..." value="{{$data -> nama}}">
            <br>
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukan password..."value="{{$data -> password}}">
            <br>
            <label>Level ID</label>
            <input type="number" name="level_id" placeholder="Masukan ID Level..."value="{{$data -> level_id}}">
            <br> <br>
            <input type="submit" class="btn btn-success" value = "Ubah">  
        </form>
    </body>
</html>