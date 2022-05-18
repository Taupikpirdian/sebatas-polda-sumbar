<?php

namespace App\Http\Livewire\Perkara;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\PerkataListThisYearService;
use Auth;
use App\Group;
use Carbon\Carbon;

class PerkaraThisYearList extends Component
{
    use WithPagination;

    public $perPage;
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
        $this->perPage   = 10;
    }

    public function render()
    {
        $login = Group::select(['groups.name AS group'])
            ->join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->first();
        $role_group = $login->group;

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
            $perkaras = (new PerkataListThisYearService())->showNotAdmin($this->perPage, $param);
        }else{
            $perkaras = (new PerkataListThisYearService())->showAdmin($this->perPage, $param);
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
        
        return view('livewire.perkara.perkara-this-year-list', compact('perkaras', 'paginate_content', 'role_group'));
    }
}
