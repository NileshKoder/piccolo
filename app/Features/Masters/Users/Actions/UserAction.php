<?php

namespace App\Features\Masters\Users\Actions;

use App\User;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class UserAction
{
    public function getMasterData(): array
    {
        $data['userRoles'] = User::ROLES;

        return $data;
    }

    public function createUser(array $userData)
    {
        return User::persistCreatUser($userData);
    }

    public function updateUser(User $user, array $userData)
    {
        return User::persistUpdateUser($user, $userData);
    }

    public function getUsers(
        ?string $searchValue,
        array $order,
        int $start,
        int $length
    ) {
        $users = User::search($searchValue)
            ->order($order)
            ->limitBy($start, $length)
            ->whereNotIn('id', [User::DEFAULT_USER, User::SYSTEM_USER])
            ->get();
        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = User::where('id', '!=', User::DEFAULT_USER)->count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = User::with('comment')
                ->search($searchValue)
                ->where('id', '!=', User::DEFAULT_USER)
                ->count('*');
        }
        return $this->yajraData($users, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $users, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($users)
            ->skipPaging()
            ->addColumn('action', function ($user) {
                return '<a href="' . route('users.edit', $user->id) . '" target="_blank" title="Edit User">
                            <i class="fas fa-edit text-success"></i>
                        </a>';
            })
            ->editColumn('state', function ($user) {
                if ($user->isActive()) {
                    $badgeClass = "badge-success";
                } else {
                    $badgeClass = "badge-danger";
                }

                return "<span style='width: 120px;>
                            <span class='badge {$badgeClass} font-weight-bold label-lg label-inline changeState' data-id='{$user->id}' data-current-state='{$user->state}'>{$user->state}</span>
                        </span>";
            })
            ->rawColumns(['action', 'state'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function updateState(User $user, string $currenState)
    {
        return User::persistUpdateState($user, $currenState);
    }
}
