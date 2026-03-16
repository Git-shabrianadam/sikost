<?php

class KamarModel {
    # Properties untuk kamar_model dan koneksi databasenya
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }

    # GET ALL ROOMS
    public function getAll()
    {
        $query = "
            SELECT *
            FROM v_master_kamar
            ORDER BY id_kamar
        ";

        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    # GET ROOM BY ID
    public function getById($id)
    {
        $query = "
            SELECT *
            FROM v_master_kamar
            WHERE id_kamar = :id_kamar
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ":id_kamar" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    # CREATE ROOM
    public function create($data)
    {
        $query = "
            INSERT INTO kamar
            (nama_kamar, harga_kamar, id_tipe_kamar, status_kamar)
            VALUES
            (:nama_kamar, :harga_kamar, :id_tipe_kamar, :status_kamar)
            RETURNING *
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ":nama_kamar" => $data["nama_kamar"],
            ":harga_kamar" => $data["harga_kamar"],
            ":id_tipe_kamar" => $data["id_tipe_kamar"],
            ":status_kamar" => $data["status_kamar"]
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    # UPDATE ROOM
    public function update($id, $data)
    {
        $query = "
            UPDATE kamar
            SET
                nama_kamar = :nama_kamar,
                harga_kamar = :harga_kamar,
                id_tipe_kamar = :id_tipe_kamar,
                status_kamar = :status_kamar
            WHERE id_kamar = :id_kamar
            RETURNING *
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ":id_kamar" => $id,
            ":nama_kamar" => $data["nama_kamar"],
            ":harga_kamar" => $data["harga_kamar"],
            ":id_tipe_kamar" => $data["id_tipe_kamar"],
            ":status_kamar" => $data["status_kamar"]
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    # DELETE ROOM
    public function delete($id)
    {
        $query = "
            DELETE FROM kamar
            WHERE id_kamar = :id_kamar
        ";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ":id_kamar" => $id
        ]);
    }

}
?>