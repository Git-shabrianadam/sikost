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

        $stmt = $this->db->query($query);

        if(!$stmt){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>