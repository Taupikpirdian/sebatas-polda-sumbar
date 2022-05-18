<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use Auth;
use App\Perkara;
use Livewire\WithPagination;
use Carbon\Carbon;

class PerkaraPolsekList extends Component
{
    use WithPagination;
    public $satker_id;
    public $divisi;
    public $perPage;
    public $query           = '';
    public $query_no_lp     = '';
    public $query_petugas   = '';
    public $query_korban    = '';
    public $query_bukti     = '';
    public $query_kejadian  = '';
    public $query_pidana    = '';
    public $query_status    = '';
    public $query_daterange = '';

    public function mount($id)
    {
        $this->perPage   = 10;
        $this->satker_id = $id;
        $this->divisi    = ['Unit Reskrim'];
    }

    public function render()
    {
        $query_daterange    = $this->query_daterange;
        $original_date_from = '';
        $original_date_to   = '';

        if($this->query_daterange){ // masih blm bener
            $arr_date    = explode("-",$this->query_daterange);
            $arr_date[0] = rtrim($arr_date[0]);
            $arr_date[1] = ltrim($arr_date[1]);
            // change format date from
            $replace_from           = str_replace("/","-",$arr_date[0]);
            $original_date_from     = $replace_from;
            // change format date to
            $replace_to           = str_replace("/","-",$arr_date[1]);
            $original_date_to     = $replace_to;
        }

        // param filter
        $query_no_lp        = $this->query_no_lp;
        $query_petugas      = $this->query_petugas;
        $query_korban       = $this->query_korban;
        $query_bukti        = $this->query_bukti;
        $query_kejadian     = $this->query_kejadian;
        $query_pidana       = $this->query_pidana;
        $query_status       = $this->query_status;

        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('kategori_bagian_id', $this->satker_id)
            ->when(!empty($query_no_lp), function ($query) use ($query_no_lp) {
                $query->where('perkaras.no_lp', 'like', "%$query_no_lp%");
            })
            ->when(!empty($query_petugas), function ($query) use ($query_petugas) {
                $query->where('perkaras.nama_petugas', 'like', "%$query_petugas%");
            })
            ->when(!empty($query_korban), function ($query) use ($query_korban) {
                $query->where('korbans.nama', 'like', "%$query_korban%");
            })
            ->when(!empty($query_bukti), function ($query) use ($query_bukti) {
                $query->where('korbans.barang_bukti', 'like', "%$query_bukti%");
            })
            ->when(!empty($query_kejadian), function ($query) use ($query_kejadian) {
                $query->where('perkaras.date', $query_kejadian);
            })
            ->when(!empty($query_pidana), function ($query) use ($query_pidana) {
                $query->where('jenis_pidanas.name', 'like', "%$query_pidana%");
            })
            ->when(!empty($query_status), function ($query) use ($query_status) {
                $query->where('statuses.name', 'like', "%$query_status%");
            })
            ->when(!empty($query_daterange), function ($query) use ($original_date_from, $original_date_to) {
                $query->whereBetween('perkaras.date', [$original_date_from, $original_date_to]);
            })
            ->paginate($this->perPage);

        $this->page > $perkaras->lastPage() ? $this->page = $perkaras->lastPage() : true;

        $count  = $perkaras->count();

        // for calculate the current display of paginated content
        $limit  = $perkaras->perPage();
        $page   = $perkaras->currentPage();
        $total  = $perkaras->total();

        $upper = min( $total, $page * $limit);
        if($count == 0){
            $lower = 0;
        }else{
            $lower = ($page - 1) * $limit + 1;
        }
        $paginate_content = "Showing $lower to $upper of $total";

        return view('livewire.report.perkara-polsek-list', compact('perkaras', 'paginate_content'));
    }
}
