<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    
     // get
    $app->get('/Apoteker', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ReadApoteker()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // // get by id
    // $app->get('/Apoteker/{id_apoteker}', function (Request $request, Response $response, $args) {
    //     $db = $this->get(PDO::class);

    //     $query = $db->prepare('SELECT * FROM Apoteker WHERE id_apoteker=?');
    //     $query->execute([$args['id_apoteker']]);
    //     $results = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $response->getBody()->write(json_encode($results));
         
    //     return $response->withHeader("Content-Type", "application/json");
    // });

    

    $app->post('/Apoteker', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $apotekerName = $parsedBody["nama_apoteker"];
        $alamat = $parsedBody["alamat"];
        $noTelpon = $parsedBody["No_Telpon"];
        
        $db = $this->get(PDO::class);
        
        // Ambil ID terakhir yang digunakan
        $queryLastId = $db->query('SELECT MAX(id_apoteker) as max_id FROM Apoteker');
        $lastIdResult = $queryLastId->fetch(PDO::FETCH_ASSOC);
        $lastId = $lastIdResult['max_id'] ? (int)$lastIdResult['max_id'] + 1 : 1;
        
        $query = $db->prepare('INSERT INTO Apoteker (id_apoteker, nama_apoteker, alamat, No_Telpon) VALUES (?, ?, ?, ?)');
        $query->execute([$lastId, $apotekerName, $alamat, $noTelpon]);
        
        $response->getBody()->write(json_encode(
            [
                'message' => 'Apoteker disimpan dengan id ' . $lastId
            ]
        ));
        
        return $response->withHeader("Content-Type", "application/json");
    });
    

    $app->put('/Apoteker/{id_apoteker}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        
        $currentId = $args['id_apoteker'];
        $apotekerName = $parsedBody["nama_apoteker"];
        $alamat = $parsedBody["alamat"];
        $noTelpon = $parsedBody["No_Telpon"];
        
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('UPDATE Apoteker SET nama_apoteker = ?, alamat = ?, No_Telpon = ? WHERE id_apoteker = ?');
        $query->execute([$apotekerName, $alamat, $noTelpon, $currentId]);
        
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Apoteker dengan id ' . $currentId . ' telah diupdate dengan nama ' . $apotekerName
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Gagal mengupdate apoteker dengan id ' . $currentId
                ]
            ));
        }
        
        return $response->withHeader("Content-Type", "application/json");
    });
     
    $app->delete('/Apoteker/{id_apoteker}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_apoteker'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM Apoteker WHERE id_apoteker = ?');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Apoteker dengan ID ' . $currentId . ' tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Apoteker dengan ID ' . $currentId . ' telah dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
        

    $app->get('/Obat', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM Obat');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/Obat/{id_obat}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM Obat WHERE id_obat=?');
        $query->execute([$args['id_obat']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));
         
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->post('/Obat', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $idObat = $parsedBody["id_obat"]; 
        $namaObat = $parsedBody["nama_obat"];
        $jenisObat = $parsedBody["jenis_obat"];
        $indikasiObat = $parsedBody["indikasi_obat"];
        $dosisObat = $parsedBody["dosis_obat"];
        $efekSampingObat = $parsedBody["efek_samping_obat"];
        $stokObat = $parsedBody["stok_obat"];
        $harga = $parsedBody["harga"];
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('INSERT INTO Obat (id_obat, nama_obat, jenis_obat, indikasi_obat, dosis_obat, efek_samping_obat, stok_obat, harga) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$idObat, $namaObat, $jenisObat, $indikasiObat, $dosisObat, $efekSampingObat, $stokObat, $harga]);
    
        $response->getBody()->write(json_encode(
            [
                'message' => 'Obat disimpan dengan id ' . $idObat
            ]
        ));
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    
    $app->put('/Obat/{id_obat}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        
        $currentId = $args['id_obat'];
        $namaObat = $parsedBody["nama_obat"];
        $jenisObat = $parsedBody["jenis_obat"];
        $indikasiObat = $parsedBody["indikasi_obat"];
        $dosisObat = $parsedBody["dosis_obat"];
        $efekSampingObat = $parsedBody["efek_samping_obat"];
        $stokObat = $parsedBody["stok_obat"];
        $harga = $parsedBody["harga"];
        
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('UPDATE Obat SET nama_obat = ?, jenis_obat = ?, indikasi_obat = ?, dosis_obat = ?, efek_samping_obat = ?, stok_obat = ?, harga = ? WHERE id_obat = ?');
        $query->execute([$namaObat, $jenisObat, $indikasiObat, $dosisObat, $efekSampingObat, $stokObat, $harga, $currentId]);
        
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Obat dengan id ' . $currentId . ' telah diupdate dengan nama ' . $namaObat
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Gagal mengupdate obat dengan id ' . $currentId
                ]
            ));
        }
        
        return $response->withHeader("Content-Type", "application/json");
    });
        

    $app->delete('/Obat/{id_obat}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_obat'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM Obat WHERE id_obat = ?');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Obat dengan ID ' . $currentId . ' tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Obat dengan ID ' . $currentId . ' telah dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
        
    $app->get('/Transaksi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM Transaksi');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/Transaksi/{id_transaksi}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM Transaksi WHERE id_transaksi=?');
        $query->execute([$args['id_transaksi']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));
         
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->post('/Transaksi', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $idTransaksi = $parsedBody["id_transaksi"];
        $idApoteker = $parsedBody["id_apoteker"];
        $idObat = $parsedBody["id_obat"];
        $jumlahObat = $parsedBody["jumlah_obat"];
        $tanggalInput = $parsedBody["tanggalinput"];
        
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('INSERT INTO Transaksi (id_transaksi, id_apoteker, id_obat, jumlah_obat, tanggalinput) VALUES (?, ?, ?, ?, ?)');
        $query->execute([$idTransaksi, $idApoteker, $idObat, $jumlahObat, $tanggalInput]);
        
        $response->getBody()->write(json_encode(
            [
                'message' => 'Transaksi disimpan dengan id ' . $idTransaksi
            ]
        ));
        
        return $response->withHeader("Content-Type", "application/json");
    });
    
    $app->put('/Transaksi/{id_transaksi}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        
        $currentId = $args['id_transaksi'];
        $idApoteker = $parsedBody["id_apoteker"];
        $idObat = $parsedBody["id_obat"];
        $jumlahObat = $parsedBody["jumlah_obat"];
        $tanggalInput = $parsedBody["tanggalinput"];
        
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('UPDATE Transaksi SET id_apoteker = ?, id_obat = ?, jumlah_obat = ?, tanggalinput = ? WHERE id_transaksi = ?');
        $query->execute([$idApoteker, $idObat, $jumlahObat, $tanggalInput, $currentId]);
        
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Transaksi dengan id ' . $currentId . ' telah diupdate'
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Gagal mengupdate transaksi dengan id ' . $currentId
                ]
            ));
        }
        
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->delete('/Transaksi/{id_transaksi}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_transaksi'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM Transaksi WHERE id_transaksi = ?');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Transaksi dengan ID ' . $currentId . ' tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Transaksi dengan ID ' . $currentId . ' telah dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    

};
