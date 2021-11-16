<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();

        if (request()->has('filters')) {
            $query = $this->buildQuery($query);
        }

        $users = $query->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    /**
     * The idea here is to use a structured query parameter to pass the filters
     * as a key:value pair.
     * 
     *      filters=filterA_key:filterA_value,filterB_key:filterB_value...
     * 
     * Empty values will be assumed as 'true'. This implementation
     * can be extracted out into a class if we want to add more complex logic
     * when filtering (such as range values or multiple values). The filter rely
     * on the ALLOWED_FILTERS const value in the User model. 
     * 
     * In reality though, the approach would be to use a third party library
     * such as spatie/laravel-query-builder (https://spatie.be/docs/laravel-query-builder/v3/introduction)
     * which comes with lots of features to do this work.
     * 
     * @returns Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery($query)
    {
        $filters = request()->get('filters');

        $filtersCollection = collect(explode(',', $filters));

        $mappedFilters = $filtersCollection
            ->map(function ($filter) {
                $keyValuePair = explode(':', $filter);
                $pairLength = count($keyValuePair);

                if ($pairLength == 1) {
                    $keyValuePair[] = true;
                } else if ($pairLength > 2 || $pairLength == 0) {
                    return null;
                }

                return [
                    'key' => $keyValuePair[0],
                    'value' => $keyValuePair[1]
                ];
            })
            ->filter()
            ->reject(function ($filter) {
                return array_search($filter['key'], User::ALLOWED_FILTERS) === false;
            });

        $mappedFilters->each(function ($filter) use (&$query) {
            $query->where($filter['key'], $filter['value']);
        });

        return $query;
    }
}
