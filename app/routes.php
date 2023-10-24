<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    
    //Call ReadApoteker
    $app->get('/Apoteker', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ReadApoteker()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    //Call CreateApoteker
    $app->post('/Apoteker', function(Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();
            if (
                empty($parseBody['id_apoteker']) ||
                empty($parseBody['nama_apoteker']) ||
                empty($parseBody['alamat']) ||
                empty($parseBody['No_Telpon'])
            ) {
                throw new Exception("Harap isi semua field.");
            }
    
            $idApoteker = $parseBody['id_apoteker'];
            $namaApoteker = $parseBody['nama_apoteker'];
            $alamat = $parseBody['alamat'];
            $noTelpon = $parseBody['No_Telpon'];
    
            $db = $this->get(PDO::class);
            $query = $db->prepare('CALL CreateApoteker(?, ?, ?, ?)');
    
            $query->execute([$idApoteker, $namaApoteker, $alamat, $noTelpon]);
    
            $lastId = $idApoteker;
    
            $response->getBody()->write(json_encode(['message' => 'Data Apoteker Tersimpan Dengan ID ' . $lastId]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $errorResponse = ['error' => $e->getMessage()];
            $response = $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode($errorResponse));
            return $response;
        }
    });
    
    //Call UpdateApoteker
    $app->put('/Apoteker/{id_apoteker}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $currentId = $args['id_apoteker'];
        $apotekerName = $parsedBody["nama_apoteker"];
        $alamat = $parsedBody["alamat"];
        $noTelpon = $parsedBody["No_Telpon"];
        
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('CALL UpdateApoteker(?, ?, ?, ?)');
        $query->bindParam(1, $currentId, PDO::PARAM_INT);
        $query->bindParam(2, $apotekerName, PDO::PARAM_STR);
        $query->bindParam(3, $alamat, PDO::PARAM_STR);
        $query->bindParam(4, $noTelpon, PDO::PARAM_STR);
        
        $query->execute();
        
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Apoteker dengan id ' . $currentId . ' telah diupdate'
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
    
    //Call DeleteApoteker 
    $app->delete('/Apoteker/{id_apoteker}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_apoteker'];
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL DeleteApoteker(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
        
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
    
//=============================================================================================================//     
    //Call ReadObat
    $app->get('/Obat', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ReadObat()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    //Call CreateObat
    $app->post('/Obat', function(Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();
            if (
                empty($parseBody['id_obat']) ||
                empty($parseBody['nama_obat']) ||
                empty($parseBody['jenis_obat']) ||
                empty($parseBody['indikasi_obat']) ||
                empty($parseBody['dosis_obat']) ||
                empty($parseBody['efek_samping_obat']) ||
                empty($parseBody['stok_obat']) ||
                empty($parseBody['harga'])
            ) {
                throw new Exception("Harap isi semua field.");
            }
    
            $idObat = $parseBody['id_obat'];
            $namaObat = $parseBody['nama_obat'];
            $jenisObat = $parseBody['jenis_obat'];
            $indikasiObat = $parseBody['indikasi_obat'];
            $dosisObat = $parseBody['dosis_obat'];
            $efekSampingObat = $parseBody['efek_samping_obat'];
            $stokObat = $parseBody['stok_obat'];
            $harga = $parseBody['harga'];
    
            $db = $this->get(PDO::class);
            $query = $db->prepare('CALL CreateObat(?, ?, ?, ?, ?, ?, ?, ?)');
    
            $query->execute([$idObat, $namaObat, $jenisObat, $indikasiObat, $dosisObat, $efekSampingObat, $stokObat, $harga]);
    
            $response->getBody()->write(json_encode(['message' => 'Data Obat Tersimpan Dengan ID ' . $idObat]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $errorResponse = ['error' => $e->getMessage()];
            $response = $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode($errorResponse));
            return $response;
        }
    });
     
    //Call UpdateObat
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
        
        $query = $db->prepare('CALL UpdateObat(?, ?, ?, ?, ?, ?, ?, ?)');
        $query->bindParam(1, $currentId, PDO::PARAM_INT);
        $query->bindParam(2, $namaObat, PDO::PARAM_STR);
        $query->bindParam(3, $jenisObat, PDO::PARAM_STR);
        $query->bindParam(4, $indikasiObat, PDO::PARAM_STR);
        $query->bindParam(5, $dosisObat, PDO::PARAM_INT);
        $query->bindParam(6, $efekSampingObat, PDO::PARAM_STR);
        $query->bindParam(7, $stokObat, PDO::PARAM_INT);
        $query->bindParam(8, $harga, PDO::PARAM_STR);
        
        $query->execute();
        
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Obat dengan id ' . $currentId . ' telah diupdate'
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
    
    //Call DeleteObat
    $app->delete('/Obat/{id_obat}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_obat'];
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL DeleteObat(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
        
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

//=============================================================================================================//
    //Call CreateTransaksi
    $app->get('/Transaksi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ReadTransaksi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->post('/Transaksi', function(Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();
            if (
                empty($parseBody['id_transaksi']) ||
                empty($parseBody['id_apoteker']) ||
                empty($parseBody['id_obat']) ||
                empty($parseBody['jumlah_obat']) ||
                empty($parseBody['tanggalinput'])
            ) {
                throw new Exception("Harap isi semua field.");
            }
    
            $idTransaksi = $parseBody['id_transaksi'];
            $idApoteker = $parseBody['id_apoteker'];
            $idObat = $parseBody['id_obat'];
            $jumlahObat = $parseBody['jumlah_obat'];
            $tanggalInput = $parseBody['tanggalinput'];
    
            $db = $this->get(PDO::class);
    
            // Tambahkan data ke tabel Transaksi dengan id_transaksi
            $queryTransaksi = $db->prepare('CALL CreateTransaksi(?, ?, ?, ?, ?)');
            $queryTransaksi->execute([$idTransaksi, $idApoteker, $idObat, $jumlahObat, $tanggalInput]);
    
            $response->getBody()->write(json_encode(['message' => 'Data Transaksi Tersimpan Dengan ID ' . $idTransaksi]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $errorResponse = ['error' => $e->getMessage()];
            $response = $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode($errorResponse));
            return $response;
        }
    });
    
    //Call UpdateTransaksi
    $app->put('/Transaksi/{id_transaksi}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
         
        $currentId = $args['id_transaksi'];
        $idApoteker = $parsedBody["id_apoteker"];
        $idObat = $parsedBody["id_obat"];
        $jumlahObat = $parsedBody["jumlah_obat"];
        $tanggalInput = $parsedBody["tanggalinput"];

        $db = $this->get(PDO::class);
        
        $query = $db->prepare('CALL UpdateTransaksi(?, ?, ?, ?, ?)');
        $query->bindParam(1, $currentId, PDO::PARAM_INT);
        $query->bindParam(2, $idApoteker, PDO::PARAM_INT);
        $query->bindParam(3, $idObat, PDO::PARAM_INT);
        $query->bindParam(4, $jumlahObat, PDO::PARAM_INT);
        $query->bindParam(5, $tanggalInput, PDO::PARAM_STR);
  
        $query->execute();
        
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

    
    //Call DeleteTransaksi
    $app->delete('/Transaksi/{id_transaksi}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_transaksi'];
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL DeleteTransaksi(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
        
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

//==============================================================================================================//
    $app->get('/Detailtransaksi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ReadDetailtransaksi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->post('/Detailtransaksi', function(Request $request, Response $response) {
        try {
            $parseBody = $request->getParsedBody();
            if (
                empty($parseBody['id_detailtransaksi']) ||
                empty($parseBody['id_transaksi']) ||
                empty($parseBody['id_obat'])
            ) {
                throw new Exception("Harap isi semua field.");
            }
    
            $idDetailTransaksi = $parseBody['id_detailtransaksi'];
            $idTransaksi = $parseBody['id_transaksi'];
            $idObat = $parseBody['id_obat'];
    
            $db = $this->get(PDO::class);
    
            // Tambahkan data ke tabel Detailtransaksi dengan id_detailtransaksi
            $queryDetailTransaksi = $db->prepare('Call CreateDetailtransaksi(?, ?, ?)');
            $queryDetailTransaksi->execute([$idDetailTransaksi, $idTransaksi, $idObat]);
    
            $response->getBody()->write(json_encode(['message' => 'Data Detailtransaksi Tersimpan Dengan ID ' . $idDetailTransaksi]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $errorResponse = ['error' => $e->getMessage()];
            $response = $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode($errorResponse));
            return $response;
        }
    });


    //Call Updatedetailtransaksi
    $app->put('/Detailtransaksi/{id_detailtransaksi}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
         
        $currentId = $args['id_detailtransaksi'];
        $idTransaksi = $parsedBody['id_transaksi'];
        $idObat = $parsedBody['id_obat'];

        $db = $this->get(PDO::class);
        
        $query = $db->prepare('CALL UpdateDetailtransaksi(?, ?, ?)');
        $query->bindParam(1, $currentId, PDO::PARAM_INT);
        $query->bindParam(2, $idTransaksi, PDO::PARAM_INT);
        $query->bindParam(3, $idObat, PDO::PARAM_INT);
     
  
        $query->execute();
        
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail Transaksi dengan id ' . $currentId . ' telah diupdate'
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Gagal mengupdate detail transaksi dengan id ' . $currentId
                ]
            ));
        }
        
        return $response->withHeader("Content-Type", "application/json");
    });

    
    //Call DeleteDetailtransaksi
    $app->delete('/Detailtransaksi/{id_detailtransaksi}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_detailtransaksi'];
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL DeleteDetailtransaksi(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
        
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Detail Transaksi dengan ID ' . $currentId . ' tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Detail Transaksi dengan ID ' . $currentId . ' telah dihapus dari database'
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
