<?php

class PembayaranModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $query = "
            SELECT *
            FROM v_master_pembayaran
        ";

        $result = pg_query($this->db, $query);
        $data = [];
        if ($result) {

            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }
}
?>