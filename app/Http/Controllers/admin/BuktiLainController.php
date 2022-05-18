<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BuktiLain;
use App\Perkara;
use Illuminate\Support\Facades\Redirect;
use Auth;
use View;
use Illuminate\Support\Facades\Input;
use App\Group;
use App\Exports\BuktiExport;
use Maatwebsite\Excel\Facades\Excel;

class BuktiLainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // param filter
        $no_lp              = $request->no_lp;
        $satker             = $request->satker;
        $jenis_pidana       = $request->jenis_pidana;
        $no_rangka          = $request->no_rangka;
        $no_mesin           = $request->no_mesin;
        $jenis_kendaraan    = $request->jenis_kendaraan;
        $status_no_rangka   = $request->status_no_rangka;

        $buktis = BuktiLain::select(
            'perkaras.id as perkara_id',
            'perkaras.no_lp',
            'kategori_bagians.name as satuan',
            'jenis_pidanas.name as pidana',
            'bukti_lains.id',
            'bukti_lains.no_rangka',
            'bukti_lains.no_mesin',
            'bukti_lains.kode_kendaraan'
            )->join('perkaras', 'perkaras.id', '=', 'bukti_lains.perkara_id')
             ->join('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
             ->join('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
             ->orderBy('bukti_lains.created_at', 'desc')
             ->where('perkaras.user_id', '=', Auth::user()->id)
             ->when(!empty($request->no_lp), function ($query) use ($request) {
                $query->where('perkaras.no_lp', 'like', "%{$request->no_lp}%");
             })
             ->when(!empty($request->satker), function ($query) use ($request) {
                $query->where('kategori_bagians.name', 'like', "%{$request->satker}%");
             })
             ->when(!empty($request->jenis_pidana), function ($query) use ($request) {
                $query->where('jenis_pidanas.name', 'like', "%{$request->jenis_pidana}%");
             })
             ->when(!empty($request->no_rangka), function ($query) use ($request) {
                $query->where('no_rangka', 'like', "%{$request->no_rangka}%");
             })
             ->when(!empty($request->no_mesin), function ($query) use ($request) {
                $query->where('no_mesin', 'like', "%{$request->no_mesin}%");
             })
             ->when(!empty($request->jenis_kendaraan), function ($query) use ($request) {
                $query->where('kode_kendaraan', 'like', "%{$request->jenis_kendaraan}%");
             })
             ->get();

        // paginate
        $buktis = $buktis->paginate(25);

        // API CEK BARANG BUKTI UNTUK RESKRIM POLDA SUMBAR
        // SERCRET KEY UNTUK ENKRIPSI DAN DEKRIPSI DATA YANG AKAN DIKIRIM
        $sec_key = 'f1cab2298100127fab2b32';
        $url='http://180.250.40.171/ebb-api/reskrim.php';

        foreach ($buktis as $item) {
            // ARRAY DATA REQUEST YANG AKAN DIKIRIM
            // FORMAT: array[0] : pilihan cek, ada 3 pilihan 'NRKB','NOCHASIS' dan 'NOMESIN'
            //    	   array[1] : data yang dicari
            // CONTOH CEK BERDASARKAN NOCHASIS
            $req=array("NOCHASIS",$item->no_rangka);
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

            // Jika data ada dan tiada
            if($rs[2] == null){
                $item->status_no_rangka = 0;
            }else{
                $item->status_no_rangka = 1;
            }

            // jika data no rangka = -
            if($item->no_rangka == "-"){
                $item->status_no_rangka = 3;
            }

        };

        // filter no rangka
        if($request->status_no_rangka){
            $buktis = $buktis->where('status_no_rangka', $request->status_no_rangka);
        }

        return View::make('admin.bukti.list', compact(
            'status_no_rangka',
            'no_lp',
            'satker',
            'jenis_pidana',
            'no_rangka',
            'no_mesin',
            'jenis_kendaraan',
            'buktis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $no_lp = Perkara::whereNotIn('handle_bukti', ['1'])
                ->where('perkaras.user_id', '=', Auth::user()->id)
                ->pluck('no_lp', 'id');
        $no_lp->prepend('=== Pilih No LP ===', '');
        return View::make('admin.bukti.create', compact('no_lp'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bukti = new BuktiLain;
        $bukti->perkara_id       = Input::get('perkara_id');
        $bukti->no_rangka        = Input::get('no_rangka');
        $bukti->no_mesin         = Input::get('no_mesin');
        $bukti->kode_kendaraan   = Input::get('kode_kendaraan');

        $update_perkara = Perkara::where('id', $request->perkara_id)->first();
        $update_perkara->handle_bukti = 1;

        $update_perkara->save();
        $bukti->save();
        
        return Redirect::action('admin\BuktiLainController@index')->with('flash-store','Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $perkara = Perkara::where('id', $id)->first();
        return view('admin.perkara.show', compact('perkara'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bukti = BuktiLain::where('id', $id)->first();

        $update_perkara = Perkara::where('id', $bukti->perkara_id)->first();
        $update_perkara->handle_bukti = 0;
        $update_perkara->save();

        $bukti = BuktiLain::findOrFail($id);
        $bukti->delete();

        return Redirect::action('admin\BuktiLainController@index')->with('flash-destroy','The data has been deleted.');
    }

    public function ViewLaporan(Request $request)
    {
        // param filter
        $no_lp              = $request->no_lp;
        $satker             = $request->satker;
        $jenis_pidana       = $request->jenis_pidana;
        $no_rangka          = $request->no_rangka;
        $no_mesin           = $request->no_mesin;
        $jenis_kendaraan    = $request->jenis_kendaraan;
        $status_no_rangka   = $request->status_no_rangka;

        $buktis = BuktiLain::select(
            'perkaras.id as perkara_id',
            'perkaras.no_lp',
            'kategori_bagians.name as satuan',
            'jenis_pidanas.name as pidana',
            'bukti_lains.id',
            'bukti_lains.no_rangka',
            'bukti_lains.no_mesin',
            'bukti_lains.kode_kendaraan'
            )->join('perkaras', 'perkaras.id', '=', 'bukti_lains.perkara_id')
             ->join('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
             ->join('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
             ->orderBy('bukti_lains.created_at', 'desc')
             ->when(!empty($request->no_lp), function ($query) use ($request) {
                $query->where('perkaras.no_lp', 'like', "%{$request->no_lp}%");
             })
             ->when(!empty($request->satker), function ($query) use ($request) {
                $query->where('kategori_bagians.name', 'like', "%{$request->satker}%");
             })
             ->when(!empty($request->jenis_pidana), function ($query) use ($request) {
                $query->where('jenis_pidanas.name', 'like', "%{$request->jenis_pidana}%");
             })
             ->when(!empty($request->no_rangka), function ($query) use ($request) {
                $query->where('no_rangka', 'like', "%{$request->no_rangka}%");
             })
             ->when(!empty($request->no_mesin), function ($query) use ($request) {
                $query->where('no_mesin', 'like', "%{$request->no_mesin}%");
             })
             ->when(!empty($request->jenis_kendaraan), function ($query) use ($request) {
                $query->where('kode_kendaraan', 'like', "%{$request->jenis_kendaraan}%");
             })
             ->get();

        // paginate
        $buktis = $buktis->paginate(25);

        // API CEK BARANG BUKTI UNTUK RESKRIM POLDA SUMBAR
        // SERCRET KEY UNTUK ENKRIPSI DAN DEKRIPSI DATA YANG AKAN DIKIRIM
        $sec_key = 'f1cab2298100127fab2b32';
        $url='http://180.250.40.171/ebb-api/reskrim.php';

        foreach ($buktis as $item) {
            // ARRAY DATA REQUEST YANG AKAN DIKIRIM
            // FORMAT: array[0] : pilihan cek, ada 3 pilihan 'NRKB','NOCHASIS' dan 'NOMESIN'
            //    	   array[1] : data yang dicari
            // CONTOH CEK BERDASARKAN NOCHASIS
            $req=array("NOCHASIS",$item->no_rangka);
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

            // Jika data ada dan tiada
            if($rs[2] == null){
                $item->status_no_rangka = 0;
            }else{
                $item->status_no_rangka = 1;
            }

            // jika data no rangka = -
            if($item->no_rangka == "-"){
                $item->status_no_rangka = 3;
            }

        };

        // filter no rangka
        if($request->status_no_rangka){
            $buktis = $buktis->where('status_no_rangka', $request->status_no_rangka);
        }

        // echo "<pre>";
        // print_r($buktis);
        // echo "</pre>";
        // exit();

        return View::make('admin.bukti.view_laporan', compact(
            'status_no_rangka',
            'no_lp',
            'satker',
            'jenis_pidana',
            'no_rangka',
            'no_mesin',
            'jenis_kendaraan',
            'buktis'
        ));
    }

    public function exportExcel(Request $request)
    {
      $arr = [$request->no_lp, $request->satker, $request->jenis_pidana, $request->no_rangka, $request->no_mesin, $request->jenis_kendaraan];
      $b = implode(", ",$arr);
      $date = date("Y-m-d-h:i:sa");
      return Excel::download(new BuktiExport($b), 'bukti-'.$date.'.xlsx');
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

    public function checkApi(Request $request)
    {
        // API CEK DATA PERKARA
        // SERCRET KEY UNTUK ENKRIPSI DAN DEKRIPSI DATA YANG AKAN DIKIRIM
        $sec_key = 'f1cab2298100127fab2b32';
        $url='http://mappingcrimepoldasumbar.com/api/perkara-curanmor';

        // ARRAY DATA REQUEST YANG AKAN DIKIRIM
        // FORMAT: array[0] : pilihan cek, ada 3 pilihan 'NO_LP','NO_RANGKA' dan 'NO_MESIN'
        //    	   array[1] : data yang dicari
        // CONTOH CEK BERDASARKAN NO_LP
        $req=array("NO_LP", 'LP/322/B/VI/2020/RESTA SPKT-UNIT II');
        // DARI ARRAY DIATAS DIENCONE KE JSON STRING
        $req=json_encode($req);
        // SETELAH ITU DI ENCRIPT DENGAN SECURITY KEY YANG TELAH DISEPAKATI
        $hashed_string= $this->encrypt($req, $sec_key);

        $data = array(
            'mode' => 'CEK_PERKARA',
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
        // RESPON BERUPA JSON
        $rs=json_decode($rs);

        if ($rs->status == 'gagal') { // JIKA ADA ERROR DARI SISTEM API Sisdakrim
            //FORMAT Object JIKA ADA GAGAL
            // [status] => gagal
            // [message] => Failed - Data perkara curanmor tidak ada
            echo "<pre>";
            print_r($rs);
            echo "</pre>";
            exit();
        } else {
            //JIKA status TIDAK SAMA DENGAN gagal, MAKA KITA DECRYPT MENGGUNAKAN FUNTION YANG TELAH DISIAPKAN
            $rs= $this->decrypt($rs->data,$sec_key);
            //FORMAT ARRAY DARI RESPONS ADALAH SEBAHAI BERIKUT
            //array[0] : berisi array request, dalan contoh ini berisi ["NOCHASIS","MH34ST1074K447275"]
            //array[1] : berisi nama kolom dari respons yang diberikan, berisi ["NOLP","NRKB","JENIS","NOCHASIS","NOMESIN","KANTOR","NMKESATUAN"]
            //array[2] : berisi data
        }

        echo "<pre>";
        print_r($rs);
        echo "</pre>";
        exit();

        return View::make('admin.bukti.list', compact(
            'status_no_rangka',
            'no_lp',
            'satker',
            'jenis_pidana',
            'no_rangka',
            'no_mesin',
            'jenis_kendaraan',
            'buktis'
        ));
    }

}