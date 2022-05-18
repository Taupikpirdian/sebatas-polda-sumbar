<?php

namespace App\Http\Livewire\Perkara;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\PerkaraTotalListService;
use Auth;
use App\Group;
use App\Perkara;
use Carbon\Carbon;

class PerkaraTotalList extends Component
{
    use WithPagination;

    public $perPage;
    public $month;
    public $query_no_lp     = '';
    public $query_satker    = '';
    public $query_petugas   = '';
    public $query_korban    = '';
    public $query_bukti     = '';
    public $query_kejadian  = '';
    public $query_pidana    = '';
    public $query_status    = '';
    public $query_daterange = '';

    public function mount()
    {
        $this->perPage         = 10;
        $this->month           = 3;
    }

    public function render()
    {
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->select('groups.name AS group')
            ->first();

        $role_group = $login->group;

        if($this->month){
            if($this->month == 3){
                $param_mount = date('Y-m-d', strtotime('-3 months'));
            }elseif($this->month == 6){
                $param_mount = date('Y-m-d', strtotime('-6 months'));
            }elseif($this->month == 12){
                $param_mount = date('Y-m-d', strtotime('-12 months'));
            }
        }

        // filter data
        $param = [
            'query_no_lp'       => $this->query_no_lp,
            'query_satker'      => $this->query_satker,
            'query_petugas'     => $this->query_petugas,
            'query_korban'      => $this->query_korban,
            'query_bukti'       => $this->query_bukti,
            'query_kejadian'    => $this->query_kejadian,
            'query_pidana'      => $this->query_pidana,
            'query_status'      => $this->query_status,
            'query_daterange'   => $this->query_daterange
        ];

        if($login->group != 'Admin'){
            $perkaras = (new PerkaraTotalListService())->showNotAdmin($this->perPage, $param, $param_mount);
        }else{
            $perkaras = (new PerkaraTotalListService())->showAdmin($this->perPage, $param, $param_mount);
        }

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
        
        return view('livewire.perkara.perkara-total-list', compact('perkaras', 'role_group', 'paginate_content'));
    }
}
