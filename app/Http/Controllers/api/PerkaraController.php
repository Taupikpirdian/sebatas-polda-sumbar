<?php

namespace App\Http\Controllers\api;

use App\Perkara;
use App\Korban;
use App\BuktiLain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->request = $request;
    }
    
    public function update(Request $request)
    {
        $user_id   = $request->user_id;
        $satker_id = $request->satker_id;

        $data = Perkara::where('user_id', $user_id)->get();

        foreach($data as $value){
            // print_r($value);
            $value->kategori_bagian_id = $satker_id;
            $value->save();
        }

        return response()->json([
            'status' => true,
            'message' => "update data success",
            'data' => $polda,
        ], 200);
    }

    public function updateParamKorban()
    {
        $perkaras = Perkara::select(
            'perkaras.id',
            'perkaras.no_lp',
            'korbans.no_lp as no_lp_on_korbans'
        )->join('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
         ->get();

        foreach ($perkaras as $key => $data) {
            $korban = Korban::where('no_lp', $data->no_lp)->first();
            $korban->perkara_id = $data->id;
            $korban->save();
        }

        return response()->json([
            'status' => true,
            'message' => "update param korban success",
        ], 200);
    }

    public function polda()
    {
        $polda = Perkara::where('kategori_id', '1')->where('kategori_bagian_id', 1)->whereYear('date', date('Y'))->count();
        return response()->json([
            'status' => true,
            'message' => "get data success",
            'data' => $polda,
        ], 200);
    }

    public function data()
    {
        $data = Perkara::whereYear('date', date('Y'))->paginate(25);
        return response()->json([
            'status' => true,
            'message' => "get data perkara tahun 2020 success",
            'data' => $data,
        ], 200);
    }

    public function checkNoRangka(Request $request)
    {
        // parameter
        $no_rangka              = $request->no_rangka;
        $param_sec_key          = $request->sec_key;

        if($no_rangka == null){
            return response()->json([
                'status' => false,
                'message' => "Harap masukan parameter no_rangka",
            ], 404);
        }

        if($param_sec_key == null){
            return response()->json([
                'status' => false,
                'message' => "Harap masukan parameter kunci rahasia",
            ], 404);
        }

        // API CEK BARANG BUKTI UNTUK RESKRIM POLDA SUMBAR
        // SERCRET KEY UNTUK ENKRIPSI DAN DEKRIPSI DATA YANG AKAN DIKIRIM
        $sec_key = $param_sec_key;
        $url='http://180.250.40.171/ebb-api/reskrim.php';

        // ARRAY DATA REQUEST YANG AKAN DIKIRIM
        // FORMAT: array[0] : pilihan cek, ada 3 pilihan 'NRKB','NOCHASIS' dan 'NOMESIN'
        //    	   array[1] : data yang dicari
        // CONTOH CEK BERDASARKAN NOCHASIS
        $req=array("NOCHASIS",$no_rangka);
        // DARI ARRAY DIATAS DIENCONE KE JSON STRING
        $req=json_encode($req);
        // SETELAH ITU DI ENCRIPT DENGAN SECURITY KEY YANG TELAH DISEPAKATI
        $hashed_string= $this->encrypt($req, $sec_key);

        // date_default_timezone_set('Asia/Jakarta');
        // $date = date('m/d/Y h:i:s a', time());
        
        $data = array(
            'mode' => 'CEK_BB',
            'data' => $hashed_string
        );
        
        // DATA YANG AKAN DIPOST KE URL
        $post=json_encode($data);

        // POST DATA DENGA curl
        $usecookie = __DIR__ . "/cookie.txt";
        $header[] = 'Content-Type: application/json';
        $header[] = "Accept-Encoding: gzip, deflate";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
            // curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // EXECUTE URL
        $rs = curl_exec($ch);
        if(empty($rs)){
            // JIKA GAGAL
            echo "EKSEKUSI CURL GAGAL";
            var_dump($rs, curl_error($ch));
            curl_close($ch);
            return false;
        }

        // JIKA BERHASIL
        // RESPON BERUPA JSON STRING YANG HARUS KITA DECODE UNTUK MEMUDAHKAN AKSES SEBAGAI ARRAY
        $rs=json_decode($rs);

        if ($rs[0]=='gagal') { // JIKA ADA ERROR DARI SISTEM API EBB
            //FORMAT ARRAY JIKA ADA GAGAL
            //array[0] = 'gagal'
            //array[1] merupakan isi pesan error
            echo 'error : '.$rs[1];
        } else {
            //JIKA array[0] TIDAK SAMA DENGAN 'gagal' MAKA array[0] KITA DECRYPT MENGGUNAKAN FUNTION YANG TELAH DISIAPKAN
            $rs= $this->decrypt($rs[0],$sec_key);
            //FORMAT ARRAY DARI RESPONS ADALAH SEBAHAI BERIKUT
            //array[0] : berisi array request, dalan contoh ini berisi ["NOCHASIS","MH34ST1074K447275"]
            //array[1] : berisi nama kolom dari respons yang diberikan, berisi ["NOLP","NRKB","JENIS","NOCHASIS","NOMESIN","KANTOR","NMKESATUAN"]
            //array[2] : berisi data
        }

        if($rs){
            return response()->json([
                'status' => true,
                'message' => "No Rangka Tersedia",
                'data' => $rs,
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => "No Rangka Tidak Tersedia",
            ], 404);
        }
    }

    // FUNCTION ENCRIPT
    private function encrypt($string, $key) {
        $result = '';
        $string=strrev(time()) . '.' .$string;
        $strls = strlen($string);
        $strlk = strlen($key);
        for($i = 0; $i < $strls; $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % $strlk) , 1);
        $char = chr(ord($char) ^ ord($keychar));
        $result .= $char;
        }
        return strtr(rtrim(base64_encode($result), '='), '+/', '-_');
    }

    // FUNCTION DECRIPT
    private function decrypt($string, $key) {
        $resp_time = 300; // 5 menit
        $result = '';
        $string = base64_decode(strtr(str_pad($string, ceil(strlen($string) / 4) * 4, '=', STR_PAD_RIGHT), '-_', '+/'));
        $strls = strlen($string);
        $strlk = strlen($key);
        for($i = 0; $i < $strls; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % $strlk) , 1);
            $char = chr(ord($char) ^ ord($keychar));
            $result .= $char;
        }
        list($timestamp, $data) = array_pad(explode('.', $result, 2), 2, null);
        if (abs(strrev($timestamp) - time()) <= $resp_time) {
            return json_decode($data, true);
        }
        return null;
    }

    public function perkaraCuranmor(Request $request)
    {
        // key
        $sec_key = 'f1cab2298100127fab2b32';
        // get all data
        $input = $this->request->all();

        if($input['mode'] == "CEK_PERKARA"){
            // deskripsi
            $data_post_decrypt= $this->decrypt($input['data'], $sec_key);

            $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
                ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
                ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
                ->leftJoin('bukti_lains', 'perkaras.id', '=', 'bukti_lains.perkara_id')
                ->select(
                    'perkaras.id',
                    'perkaras.no_lp',
                    'perkaras.date_no_lp',
                    'kategori_bagians.name as satuan',
                    'perkaras.divisi',
                    'perkaras.nama_petugas',
                    'perkaras.pangkat',
                    'perkaras.no_tlp as no_tlp_petugas',
                    'perkaras.tkp',
                    'perkaras.lat',
                    'perkaras.long',
                    'perkaras.uraian as detail_kejadian',
                    'perkaras.date as tgl_kejadian',
                    'perkaras.time as waktu_kejadian',
                    'jenis_pidanas.name as pidana',
                    'statuses.name as status_perkara',
                    'korbans.nama as nama_korban',
                    'korbans.umur_korban',
                    'korbans.pendidikan_korban',
                    'korbans.pekerjaan_korban',
                    'korbans.asal_korban',
                    'korbans.saksi',
                    'korbans.pelaku',
                    'korbans.barang_bukti',
                    'bukti_lains.no_rangka',
                    'bukti_lains.no_mesin',
                    'bukti_lains.kode_kendaraan'
                )->whereIn('perkaras.jenis_pidana', [35, 112])
                ->when(($data_post_decrypt[0] == "NO_LP"), function ($query) use ($data_post_decrypt) {
                    $query->where('perkaras.no_lp', "$data_post_decrypt[1]");
                })
                ->when(($data_post_decrypt[0] == "NO_RANGKA"), function ($query) use ($data_post_decrypt) {
                    $query->where('bukti_lains.no_rangka', "$data_post_decrypt[1]");
                })
                ->when(($data_post_decrypt[0] == "NO_MESIN"), function ($query) use ($data_post_decrypt) {
                    $query->where('bukti_lains.no_mesin', "$data_post_decrypt[1]");
                })
                ->first();
            
            if($perkaras == null){
                return response()->json([
                    'status' => 'gagal',
                    'message' => "Failed - Data perkara curanmor tidak ada",
                ], 404);
            }else{
                // ubah format data dlm bentuk json
                $perkaras= json_encode($perkaras);
                // data respon yang akan dikirim
                $perkaras= $this->encrypt($perkaras, $sec_key);
    
                return response()->json([
                    'status' => 'berhasil',
                    'message' => "Success - Data perkara curanmor",
                    'data' => $perkaras,
                ], 200);
            }
            
        }else{
            return response()->json([
                'status' => false,
                'message' => "mode tidak tersedia",
            ], 404);
        }
    }

    public function formater(Request $request)
    {
        // get all data
        $input = $this->request->all();

        print_r($input);
        exit();

        return response()->json([
            'data' => $input
        ], 200);
    }
}
