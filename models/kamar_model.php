<?php

class KamarModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    # GET ALL kamar
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


    # GET kamar BY ID
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


    # CREATE kamar
    public function create($data)
    {
        try {

            # Prevent duplicate nama_kamar
            $checkQuery = "
                SELECT COUNT(*)
                FROM kamar
                WHERE nama_kamar = :nama_kamar
            ";

            $stmtCheck = $this->db->prepare($checkQuery);

            $stmtCheck->execute([
                ":nama_kamar" => $data["nama_kamar"]
            ]);

            if ($stmtCheck->fetchColumn() > 0) {

                return [
                    "error" => "Nama kamar sudah digunakan"
                ];
            }


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

        } catch (Exception $e) {

            return [
                "error" => $e->getMessage()
            ];
        }
    }

    # UPDATE kamar
    public function update($id, $data)
    {
        try {

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

        } catch (Exception $e) {

            return [
                "error" => $e->getMessage()
            ];
        }
    }


    # DELETE kamar sdh di enforce
    public function delete($id)
    {
        try {

            $this->db->beginTransaction();

            # cek dulu ada penghuni apa gak
            $checkQuery = "
                SELECT COUNT(*)
                FROM kamar_penghuni
                WHERE id_kamar = :id_kamar
                AND is_aktif = true
            ";

            $stmtCheck = $this->db->prepare($checkQuery);

            $stmtCheck->execute([
                ":id_kamar" => $id
            ]);

            $activeCount = $stmtCheck->fetchColumn();

            if ($activeCount > 0) {

                $this->db->rollBack();

                return [
                    "error" => "Kamar masih memiliki penghuni aktif"
                ];
            }

            # Delete di stabilkan tdk bisa dihapus saat masih ada penghuni aktif
            $deleteQuery = "
                DELETE FROM kamar
                WHERE id_kamar = :id_kamar
            ";

            $stmtDelete = $this->db->prepare($deleteQuery);
            $stmtDelete->execute([
                ":id_kamar" => $id
            ]);

            $this->db->commit();

            return [
                "message" => "Kamar berhasil dihapus"
            ];

        } catch (Exception $e) {

            $this->db->rollBack();

            return [
                "error" => $e->getMessage()
            ];
        }
    }
}
?>