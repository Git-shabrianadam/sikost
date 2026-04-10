<?php

class PenghuniModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    # GET ALL penghuni
    public function getAll()
    {
        $query = "
            SELECT *
            FROM v_master_penghuni
            ORDER BY id_penghuni
        ";

        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    # GET penghuni BY ID
    public function getById($id)
    {
        $query = "
            SELECT *
            FROM v_master_penghuni
            WHERE id_penghuni = :id_penghuni
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ":id_penghuni" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    # CREATE penghuni + assign kamar
    public function create($data)
    {
        try {

            $this->db->beginTransaction();


            # Prevent duplicate NIK
            $checkQuery = "
                SELECT COUNT(*)
                FROM penghuni
                WHERE nik = :nik
            ";

            $stmtCheck = $this->db->prepare($checkQuery);

            $stmtCheck->execute([
                ":nik" => $data["nik"]
            ]);

            if ($stmtCheck->fetchColumn() > 0) {

                $this->db->rollBack();

                return [
                    "error" => "NIK sudah terdaftar"
                ];
            }


            # Insert penghuni
            $queryPenghuni = "
                INSERT INTO penghuni
                (
                    nama_penghuni,
                    is_aktif,
                    telp,
                    nik,
                    alamat
                )
                VALUES
                (
                    :nama_penghuni,
                    true,
                    :telp,
                    :nik,
                    :alamat
                )
                RETURNING *
            ";

            $stmtPenghuni = $this->db->prepare($queryPenghuni);

            $stmtPenghuni->execute([
                ":nama_penghuni" => $data["nama_penghuni"],
                ":telp" => $data["telp"],
                ":nik" => $data["nik"],
                ":alamat" => $data["alamat"]
            ]);

            $penghuni = $stmtPenghuni->fetch(PDO::FETCH_ASSOC);

            $id_penghuni = $penghuni["id_penghuni"];


            # Find kamar
            $queryKamar = "
                SELECT id_kamar
                FROM kamar
                WHERE nama_kamar = :nama_kamar
            ";

            $stmtKamar = $this->db->prepare($queryKamar);
            $stmtKamar->execute([
                ":nama_kamar" => $data["nama_kamar"]
            ]);

            $kamar = $stmtKamar->fetch(PDO::FETCH_ASSOC);

            if (!$kamar) {

                $this->db->rollBack();

                return [
                    "error" => "Kamar tidak ditemukan"
                ];
            }

            # Insert kamar_penghuni
            $queryKP = "
                INSERT INTO kamar_penghuni
                (
                    id_kamar,
                    id_penghuni,
                    tanggal_masuk,
                    is_aktif
                )
                VALUES
                (
                    :id_kamar,
                    :id_penghuni,
                    :tanggal_masuk,
                    true
                )
            ";

            $stmtKP = $this->db->prepare($queryKP);
            $stmtKP->execute([
                ":id_kamar" => $kamar["id_kamar"],
                ":id_penghuni" => $id_penghuni,
                ":tanggal_masuk" => $data["tanggal_masuk"]
            ]);


            $this->db->commit();

            return [
                "penghuni" => $penghuni,
                "kamar" => $data["nama_kamar"]
            ];

        } catch (Exception $e) {

            $this->db->rollBack();

            return [
                "error" => $e->getMessage()
            ];
        }
    }


    # UPDATE penghuni
    public function update($id, $data)
    {
        try {

            $query = "
                UPDATE penghuni
                SET
                    nama_penghuni = :nama_penghuni,
                    telp = :telp,
                    nik = :nik,
                    alamat = :alamat
                WHERE id_penghuni = :id_penghuni
                RETURNING *
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ":id_penghuni" => $id,
                ":nama_penghuni" => $data["nama_penghuni"],
                ":telp" => $data["telp"],
                ":nik" => $data["nik"],
                ":alamat" => $data["alamat"]
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            return [
                "error" => $e->getMessage()
            ];
        }
    }


    # DELETE penghuni (SAFE)
    public function delete($id)
    {
        try {

            $this->db->beginTransaction();


            # Deactivate active occupancy first
            $queryDeactivate = "
                UPDATE kamar_penghuni
                SET
                    is_aktif = false,
                    tanggal_keluar = CURRENT_DATE
                WHERE id_penghuni = :id_penghuni
                AND is_aktif = true
            ";

            $stmtDeactivate = $this->db->prepare($queryDeactivate);
            $stmtDeactivate->execute([
                ":id_penghuni" => $id
            ]);


            # Delete penghuni
            $queryDelete = "
                DELETE FROM penghuni
                WHERE id_penghuni = :id_penghuni
            ";

            $stmtDelete = $this->db->prepare($queryDelete);
            $stmtDelete->execute([
                ":id_penghuni" => $id
            ]);


            $this->db->commit();
            return [
                "message" => "Penghuni berhasil dihapus"
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