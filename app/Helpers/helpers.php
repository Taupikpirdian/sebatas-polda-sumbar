<?php

use App\Log;
use App\Perkara;
use App\Lapor;
use App\TrafficAccident;
use Carbon\Carbon;
   
function customTanggal($date,$date_format){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);    
}

function saveLog($req){
  // kamus code
  // - Setiap create data kasus baru == 1
  // - Setiap update data status perkara == 2
  // - Setiap edit data perkara == 3
  // - Setiap update anggaran perkara == 4

  // create logs
  $log = new Log;
  $log ->user_id     = $req['user_id']; // User update
  $log ->perkara_id  = $req['perkara_id'];
  $log ->status      = $req['status'];
  $log ->save();
}

function saveLogLapor($req){
  // kamus code
  // - Setiap create data lapor baru == 5
  if($req['status'] == 'create'){
    $code = '5';
  }
  // - Setiap update data lapor perkara == 6
  // - Setiap edit data lapor == 7
  if($req['status'] == 'edit'){
    $code = '6';
  }
  // - Setiap update anggaran lapor == 8
  // create logs
  $log = new Log;
  $log ->user_id     = $req['user_id']; // User update
  $log ->lapor_id    = $req['lapor_id'];
  $log ->status      = $code;
  $log ->save();
}

function saveLogLakaLantas($req){
  // kamus code
  // - Setiap create data lapor baru == 9
  if($req['status'] == 'create'){
    $code = '9';
  }
  // - Setiap update data lapor perkara == 10
  if($req['status'] == 'update-status'){
    $code = '10';
  }
  // - Setiap edit data lapor == 11
  if($req['status'] == 'edit'){
    $code = '11';
  }
  // - Setiap update anggaran lapor == 12
  // create logs
  $log = new Log;
  $log ->user_id              = $req['user_id']; // User update
  $log ->traffic_accident_id  = $req['traffic_accident_id'];
  $log ->status               = $code;
  $log ->save();
}

function messageToTelegram($req){
  /** kode status */
  // 1 = create data baru
  // 2 = update data status perkara
  // 3 = edit data perkara
  // 4 = update anggaran perkara
  // 5 = error satker induk tidak ada
  // 6 = data master anggaran tidak ada
  // 7 = data anggaran per perkara sudah habis
  // 8 = data anggaran per satker sudah habis
  // set date and time now
  date_default_timezone_set('Asia/Bangkok');
  // waktu sekarang
  $time = date('h:i:s');
  // tanggal sekarang
  $date = date('Y-m-d');
  // modif date
  $modifDate = date("F j, Y");
  // modif time
  $modifTime  = date("g:i:s a", strtotime($time));
  // data chat_id
  $myArrayValue = array("-369140289"); // id akun group operasional sisdakrim
  // token bot
  $tokenBot = '1660878491:AAHdsVCFYAcBna0sxmptu_o2BBV2pU307kI';
  // pesan
  if($req['status'] == 1){
    $text = "Dear Team IT Mapping Crime,\nSaat ini terdeteksi Aktifitas sebagai berikut:\nHal: Create Data Perkara Baru\nNo LP:".$req['no_lp']."\nId Login: ".$req['user_id']."\nId Satker Login: ".$req['satker_id']."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nAtas perhatian dan kerjasama yang baik kami ucapkan terima kasih.";
  }

  if($req['status'] == 2){
    $text = "Dear Team IT Mapping Crime,\nSaat ini terdeteksi Aktifitas sebagai berikut:\nHal: Update Data Status Perkara\nNo LP:".$req['no_lp']."\Status Perkara:".$req['status_perkara']."\nId Login: ".$req['user_id']."\nId Satker Login: ".$req['satker_id']."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nAtas perhatian dan kerjasama yang baik kami ucapkan terima kasih.";
  }

  if($req['status'] == 3){
    $text = "Dear Team IT Mapping Crime,\nSaat ini terdeteksi Aktifitas sebagai berikut:\nHal: Edit Data Perkara Baru\nNo LP:".$req['no_lp']."\nId Login: ".$req['user_id']."\nId Satker Login: ".$req['satker_id']."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nAtas perhatian dan kerjasama yang baik kami ucapkan terima kasih.";
  }

  if($req['status'] == 4){
    $text = "Dear Team IT Mapping Crime,\nSaat ini terdeteksi Aktifitas sebagai berikut:\nHal: Update Anggaran Perkara\nNo LP:".$req['no_lp']."\nNominal:".$req['nominal']."\Keterangan:".$req['ket']."\nId Login: ".$req['user_id']."\nId Satker Login: ".$req['satker_id']."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nAtas perhatian dan kerjasama yang baik kami ucapkan terima kasih.";
  }

  /** hanya kode 5 dan 6 yang digunakan */
  if($req['status'] == 5){
    $text = "Dear Team IT Mapping Crime,\nSaat ini terdeteksi Error sebagai berikut:\nHal: Satker induk tidak ditemukan (Polres)\nId Login: ".$req['user_id']."\nId Satker Login: ".$req['satker_id']."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nMohon agar dilakukan pengecekan kembali. Atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.";
  }

  if($req['status'] == 6){
    $text = "Dear Team IT Mapping Crime,\nSaat ini terdeteksi Error sebagai berikut:\nHal: Data Master Anggaran tidak ditemukan\nId Login: ".$req['user_id']."\nId Satker Login: ".$req['satker_id']."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nMohon agar dilakukan pengecekan kembali. Atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.";
  }

  foreach($myArrayValue as $item) {
    $postRequest = array(
      'chat_id' => $item,
      'text' => $text
    );
    
    $cURLConnection = curl_init('https://api.telegram.org/bot'.$tokenBot.'/sendMessage');
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    
    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);
  }
}

function reportPerkara(){
  // set date and time now
  date_default_timezone_set('Asia/Bangkok');
  // waktu sekarang
  $time = date('h:i:s');
  // tanggal sekarang
  $date = date('Y-m-d');
  // modif date
  $modifDate = date("F j, Y");
  // modif time
  $modifTime  = date("g:i:s a", strtotime($time));
  // data chat_id
  $myArrayValue = array("-501932091"); // id akun group operasional sisdakrim
  // token bot
  $tokenBot = '1665981374:AAElQue-hYGSwqeuwhxONwIQOsgn0n7GvCQ';

  // Kejahatan
  $reportPerkara = Perkara::whereDate('created_at', '=', date('Y-m-d'))->get();
  $countData = $reportPerkara->count();
  // Laka Lantas
  $reportLantas     = TrafficAccident::whereDate('created_at', '=', date('Y-m-d'))->get();
  $countDataLantas  = $reportLantas->count();
  // Aduan Masyarakat
  $reportAduan      = Lapor::whereDate('created_at', '=', date('Y-m-d'))->get();
  $countDataAduan   = $reportAduan->count();
  // create logs
  \Log::info('Jumlah Data = '.$countData);
  \Log::info('Cron Work');

  // Kasus
  $text = "Dear Team IT Mapping Crime,\nCrimy laporkan jumlah data baru hari ini:\nHal: Laporan Harian\nJumlah Data Kejahatan: ".$countData."\nJumlah Data Kecelakaan Lalu-lintas: ".$countDataLantas."\nJumlah Data Aduan Masyarakat: ".$countDataAduan."\nTanggal: ".$modifDate."\nJam: ".$modifTime."\nAtas perhatian dan kerjasama yang baik, Crimy ucapkan terima kasih.";

  foreach($myArrayValue as $item) {
    $postRequest = array(
      'chat_id' => $item,
      'text' => $text
    );
    
    $cURLConnection = curl_init('https://api.telegram.org/bot'.$tokenBot.'/sendMessage');
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    
    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);
  }
  \Log::info('Success Send Telegram');

}

function testSendEmail(){
  $to_name = 'taupik';
  $to_email = 'pirdiantaupik@gmail.com';
  $data = array('name'=>"Ogbonna Vitalis", "body" => "A test mail");

  Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
    $message->to($to_email, $to_name)->subject('Laravel Test Mail');
    $message->from('noreply@mappingcrime-poldasumbar.id','Test Mail');
  });
}