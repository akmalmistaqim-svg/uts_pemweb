<?php
require_once 'koneksi.php';

class DbSession {
    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
        session_set_save_handler(
            [$this, 'open'], [$this, 'close'],
            [$this, 'read'], [$this, 'write'],
            [$this, 'destroy'], [$this, 'gc']
        );
        register_shutdown_function('session_write_close');
    }

    public function open($path, $name) { return true; }
    public function close() { return true; }

    public function read($id) {
        $id = mysqli_real_escape_string($this->koneksi, $id);
        $result = mysqli_query($this->koneksi, 
            "SELECT data FROM sessions WHERE session_id = '$id'"
        );
        if ($row = mysqli_fetch_assoc($result)) return $row['data'];
        return '';
    }

    public function write($id, $data) {
        $id   = mysqli_real_escape_string($this->koneksi, $id);
        $data = mysqli_real_escape_string($this->koneksi, $data);
        $time = time();
        mysqli_query($this->koneksi, 
            "REPLACE INTO sessions VALUES ('$id', '$data', $time)"
        );
        return true;
    }

    public function destroy($id) {
        $id = mysqli_real_escape_string($this->koneksi, $id);
        mysqli_query($this->koneksi, 
            "DELETE FROM sessions WHERE session_id = '$id'"
        );
        return true;
    }

    public function gc($max) {
        $old = time() - $max;
        mysqli_query($this->koneksi, 
            "DELETE FROM sessions WHERE last_activity < $old"
        );
        return true;
    }
}

new DbSession($koneksi);
session_start();
?>